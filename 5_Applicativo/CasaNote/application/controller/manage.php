<?php
session_start();
class manage
{
    private $noteMapper;
    private $validator;

    private $attachmentMapper;
    function __construct()
    {
        require_once "application/models/note.php";
        require_once "application/models/noteMapper.php";
        $this->noteMapper = new \models\noteMapper();
        require_once "application/libs/validator.php";
        $this->validator = new validator();
        require_once "application/models/attachment.php";
        require_once "application/models/attachmentMapper.php";
        $this->attachmentMapper = new \models\attachmentMapper();
    }

    function index()
    {

    }

    function goToCreateNotePage($id = null)
    {
        $note = $this->noteMapper->findById($id);

        require 'application/views/_templates/navbar2.php';
        require "application/views/_templates/header.php";
        require "application/views/manage/createNote.php";
        return $note;
    }

    public function deleteNote($id = null)
    {
        if ($id === null) {
            echo "ID della nota non fornito.";
            return;
        }
        $note = $this->noteMapper->findById($id);
        if ($note) {
            if ($this->noteMapper->deleteNote($note)) {
                header("location: " . URL . "home/resetFilter");
                exit();
            } else {
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }
    }

    public function saveOrUpdateNote($id = null)
    {
        if ($id === null && isset($_POST['id'])) {
            $id = $this->validator->sanitizeInput($_POST['id']);
        }
        if ($id !== null) {
            if (isset($_POST['title'])) {
                $title = $this->validator->sanitizeInput($_POST['title']);
                $noteToUpdate = $this->noteMapper->findById($id);
                $data_last_update = date('Y-m-d H:i:s');
                if ($noteToUpdate) {
                    $userId = $_SESSION['UserId'];
                    $newNote = new \models\note($id, $title, $noteToUpdate->getDateCreation(), $data_last_update, $userId);
                    if ($this->noteMapper->updateNote($noteToUpdate, $newNote)) {
                        header('location:' . URL . 'home/resetFilter');
                        exit();
                    } else {
                        require_once 'application/views/_templates/error.php';
                        $this->index();
                    }
                }
            }
        } else {
            if (isset($_POST['title'])) {
                $title = $this->validator->sanitizeInput($_POST['title']);
                $data_creation = date('Y-m-d H:i:s');
                $data_last_update = date('Y-m-d H:i:s');
                $userId = $_SESSION['UserId'];
                $note = new \models\note(null, $title, $data_creation, $data_last_update, $userId);
                if ($this->noteMapper->addNote($note)) {
                    header('location:' . URL . 'home/resetFilter');
                    exit();
                } else {
                    require_once 'application/views/_templates/error.php';
                    $this->goToCreateNotePage();
                }
            }
        }
    }

    public function saveAttachment()
    {

        if (isset($_POST['attachment_type'])) {
            $attachmentType = $_POST['attachment_type'];
            $noteId = $this->noteMapper->getLastNoteId();

            if ($noteId === null) {
                echo json_encode(['success' => false, 'message' => 'Nota non trovata']);
                logger::error('Nota non trovata');
                exit();
            }
            logger::info('id ' . $noteId);
            $noteFolder = "uploads/note_{$noteId}";
            if (!file_exists($noteFolder)) {
                mkdir($noteFolder, 0777, true);
                logger::info('Cartella creata' . $noteFolder);
            }

            switch ($attachmentType) {
                case 'file':
                    logger::info('Tipo di Attchment : File');
                    if (isset($_FILES['attachment_file']) && $_FILES['attachment_file']) {
                        $fileName = basename($_FILES['attachment_file']['name']);
                        $filePath = $noteFolder . '/' . $fileName;
                        logger::info('POST DATA: ' . $filePath . ' ' . $fileName);
                        if (move_uploaded_file($_FILES['attachment_file']['tmp_name'], $filePath)) {
                            $this->attachmentMapper->saveAttachmentToDatabase($fileName, $filePath, $_FILES['attachment_file']['type'], $noteId, 'file');
                            logger::info('salvataggio file');
                            echo json_encode(['success' => true]);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Errore nel caricamento del file']);
                            logger::error('Errore nel caricamento del file');
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Nessun file caricato']);
                        logger::error('Nessun file caricato');
                    }
                    break;
                case 'text':
                    if (isset($_POST['attachment_content'])) {

                        $timestamp = date("Y-m-d_H-i-s");
                        $textContent = $_POST['attachment_content'];
                        $fileName = "testo_{$noteId}" . "-" . $timestamp . ".txt";
                        $filePath = $noteFolder . '/' . $fileName;
                        logger::info('POST DATA: ' . $filePath . ' ' . $fileName);
                        file_put_contents($filePath, $textContent);
                        $this->attachmentMapper->saveAttachmentToDatabase($fileName, $filePath, 'text/plain', $noteId, 'text');
                        echo json_encode(['success' => true]);
                        logger::info($fileName . " " . $filePath . " salvato");

                        $_SESSION['countFiletxt'] = $this->countFiletxt;  // Salva il nuovo valore del contatore nella sessione
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Nessun testo fornito']);
                        logger::error($this->fileName . " " . $this->filePath . " fallito salvataggio");
                    }
                    break;
                case 'draw':
                    if (isset($_POST['attachment_content'])) {
                        $timestamp = date("Y-m-d_H-i-s");
                        $drawingContent = $_POST['attachment_content'];
                        $fileName = "disegno_{$noteId}". "-" . $timestamp . ".png";
                        $filePath = $noteFolder . '/' . $fileName;
                        $imageData = base64_decode(str_replace('data:image/png;base64,', '', $drawingContent));
                        file_put_contents($filePath, $imageData);
                        $this->attachmentMapper->saveAttachmentToDatabase($fileName, $filePath, 'image/png', $noteId, 'draw');
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Nessun disegno fornito']);
                    }
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Tipo di allegato non valido']);
                    break;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Tipo di allegato mancante']);
        }
    }
    public function zip($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($id !== null) {
                $notaName = "note_{$id}";
                logger::info('nome nota: ' . $notaName);
                $zipFileName = "{$notaName}.zip";
                logger::info('nome cartella: ' . $zipFileName);
                $zip = new ZipArchive();
                $zipPath = 'application/tmp/' . $zipFileName;
                logger::info('zip path: ' . $zipPath);

                //fa parte di un'operazione di apertura (e creazione, se necessario) di un file ZIP utilizzando la classe ZipArchive in PHP.
                //ZipArchive::CREATE è un flag che indica che, se il file ZIP non esiste, deve essere creato.
                // Se il file esiste già, verrà aperto per essere modificato.
                //La funzione open() tenta di aprire il file ZIP e restituirà TRUE se l'operazione è riuscita. Se l'apertura del file fallisce, restituirà FALSE.
                if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                    $directory = 'uploads/' . $notaName;
                    logger::info('directory in uploads: ' . $notaName);

                    //La funzione glob() restituirà un array con i nomi di tutti i file e le cartelle che si trovano in quella directory.
                    //$directory è una variabile che contiene il percorso della directory in cui stai cercando.
                    //
                    //'/*' è il pattern di ricerca
                    $files = glob($directory . '/*');
                    logger::info('files: ' . count($files));
                    foreach ($files as $file) {
                        $zip->addFile($file, basename($file));
                    }
                    $zip->close();
                    //Imposta l'intestazione HTTP per indicare che il contenuto che stai inviando è un file ZIP.
                    // In questo caso, il browser capirà che il file è di tipo application/zip, ossia un file compresso
                    // in formato ZIP.
                    header('Content-Type: application/zip');
                    //Imposta l'intestazione HTTP per indicare che il file dovrebbe essere scaricato invece che visualizzato nel browser.
                    // La parte filename="' . $zipFileName . '"' specifica il nome che il file avrà quando verrà scaricato. $zipFileName
                    // è una variabile che contiene il nome del file ZIP.
                    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
                    //Imposta l'intestazione Content-Length, che comunica al browser la dimensione del file che si sta per scaricare.
                    // $zipPath è il percorso del file ZIP, e filesize($zipPath) restituisce la sua dimensione in byte.
                    //Questo aiuta a determinare la durata del download e permette al browser
                    // di gestire correttamente il processo di scaricamento (come visualizzare la barra di avanzamento).
                    header('Content-Length: ' . filesize($zipPath));
                    //Legge il file ZIP dal server e lo invia al browser.
                    // Questa funzione legge il contenuto del file specificato in $zipPath e lo invia direttamente all'output (il browser, in questo caso).
                    //Momento del trasferimento
                    readfile($zipPath);
                    //Dopo che il file è stato inviato al browser, questa funzione elimina il file dal server.
                    // In questo caso, viene rimosso il file ZIP creato temporaneamente, una volta che è stato scaricato dall'utente,
                    // per evitare di lasciare file inutili sul server.
                    unlink($zipPath);
                } else {
                    echo "Impossibile creare il file ZIP.";
                }
            }
        }
    }
}

