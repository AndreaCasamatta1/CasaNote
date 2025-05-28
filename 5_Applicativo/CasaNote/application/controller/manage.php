<?php
session_start();
class   manage
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
        if (!$this->validator->isUserLoggedIn()) {
            header("location: " . URL . "login");
            exit();
        }

    }

    function goToCreateNotePage($id = null)
    {
        if (!$this->validator->isUserLoggedIn()) {
            header("location: " . URL . "login");
            exit();
        }
        $note = $this->noteMapper->findById($id);
        $_SESSION['note'] = $note?->getId();
        /*il "?" è un optional chanching che chiama la fuzione solo in caso
        la variabile non sia null o undefined
        */
        $attachments = $this->attachmentMapper->findAll($note?->getId());
        require "application/views/_templates/header.php";
        require 'application/views/_templates/navbar2.php';

        require "application/views/manage/createNote.php";
        return [
            'note' => $note,
            'attachments' => $attachments
        ];
    }

    public function deleteNote($id = null)
    {
        if (!$this->validator->isUserLoggedIn()) {
            header("location: " . URL . "login");
            exit();
        }
        if ($id === null) {
            $_SESSION["errors"] [] ="ID della nota mancante.";
            require_once 'application/views/_templates/error.php';
            return;
        }
        $note = $this->noteMapper->findById($id);
        if ($note) {
            if ($this->noteMapper->deleteNote($note)) {
                header("location: " . URL . "home/resetFilter");
                exit();
            } else {
                $_SESSION["errors"] [] ="Errore durante l'eliminazione della nota";
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }
    }

    public function saveOrUpdateNote($id = null)
    {
        if (!$this->validator->isUserLoggedIn()) {
            header("location: " . URL . "login");
            exit();
        }
        if ($id === null && isset($_POST['id'])) {
            $id = $this->validator->sanitizeInput($_POST['id']);
        }
        $data_creation = date('Y-m-d H:i:s');
        $_SESSION['data_creation']=$data_creation;
        if ($id !== null) {
            if (isset($_POST['title'])) {
                $title = $this->validator->sanitizeInput($_POST['title']);
                $_SESSION['title'] = $title;
                $noteToUpdate = $this->noteMapper->findById($id);
                $data_last_update = date('Y-m-d H:i:s');
                if ($noteToUpdate) {
                    $userId = $_SESSION['UserId'];
                    $data_creation = $_SESSION['data_creation'];
                    $newNote = new \models\note($id, $title, $noteToUpdate->getDateCreation(), $data_last_update, $userId);
                    if ($this->noteMapper->updateNote($noteToUpdate, $newNote)) {
                        $_SESSION['noteId'] = $this->noteMapper->getIdByDateCreation($noteToUpdate->getDateCreation(),$userId );
                        header('location:' . URL . 'manage/goToCreateNotePage/' . $_SESSION['noteId']);
                        exit();
                    } else {
                        $_SESSION["errors"] [] ="Errore durante la modifica della nota";
                        require_once 'application/views/_templates/error.php';
                        $this->index();
                    }
                }
            }
        } else {
            if (isset($_POST['title'])) {
                $title = $this->validator->sanitizeInput($_POST['title']);
                $_SESSION['title'] = $title;
                $data_last_update = date('Y-m-d H:i:s');
                $userId = $_SESSION['UserId'];
                $note = new \models\note(null, $title, $data_creation, $data_last_update, $userId);
                if ($this->noteMapper->addNote($note)) {
                    $_SESSION['noteId'] = $this->noteMapper->getIdByDateCreation($_SESSION['data_creation'],$userId );
                    header('location:' . URL . 'manage/goToCreateNotePage/' . $this->noteMapper->getLastNoteId() );
                    exit();
                } else {
                    $_SESSION["errors"] [] ="Errore durante il salvataggio della nota";
                    require_once 'application/views/_templates/error.php';
                    $this->goToCreateNotePage();

                }
            }
        }
    }

    public function saveAttachment()
    {
        if (!$this->validator->isUserLoggedIn()) {
            header("location: " . URL . "login");
            exit();
        }
        /*
        La funzione saveAttachment() gestisce il salvataggio degli allegati collegati ad una annotazione, ottenendo i dati dalla modalità POST. 
        A seconda della tipologia degli allegati (file, testo, disegno) compie diverse operazioni:
        Allegato file.: Se il dato è d'un file verifica l'avvenuto caricamento e lo registra in una cartella propria della nota, 
        aggiornando pure il database col path del file.
        Allegato testo.: Se il dato è un testo scrive il dato in un file di testo e lo registra in stetta cartella della nota attualmente in modifica, 
        andando anche a inserire il path nel DB.
        Allegato disegno : I dati conseguiti sono un'immagine di tipo base64; si decodificano i dati dell'immagine; 
        quindi si salva il file in un file PNG; e s'aggiorna il database di conseguenza.
        */

        // Verifica che il tipo di allegato sia stato fornito attraverso il metodo POST.
        if (isset($_POST['attachment_type'])) {
            $attachmentType = $_POST['attachment_type'];
            $userId = $_SESSION['UserId'];
            $noteId = $_SESSION['note'];
    
            // Se l'ID della nota è nullo, significa che la nota non esiste, quindi segnaliamo un errore andando a specificarlo anche nei logger.
            if ($noteId === null) {
                $_SESSION["errors"] [] ="Nota non trovata";
                require_once 'application/views/_templates/error.php';
                logger::error('Nota non trovata');
                $this->index();
                exit();
            }
            logger::info('id ' . $noteId);
            
            // Crea una cartella per salvare gli allegati, se non esiste già.
            $noteFolder = "uploads/note_{$noteId}";
            if (!file_exists($noteFolder)) {
                // Se la cartella non esiste, la crea con permessi di scrittura.
                mkdir($noteFolder, 0777, true);
                logger::info('Cartella creata' . $noteFolder);
            }
            //SWITCH fatto con l'aiuto di chatgpt
            switch ($attachmentType) {
                case 'file':
                    logger::info('Tipo di Attachment : File');
                    if (isset($_FILES['attachment_file']) && $_FILES['attachment_file']) {
                        //Data una stringa contenente il percorso di un file o di una directory, questa funzione restituirà il componente del nome finale.
                        $fileName = basename($_FILES['attachment_file']['name']);
                        $filePath = $noteFolder . '/' . $fileName;
                        logger::info('POST DATA: ' . $filePath . ' ' . $fileName);
                        /*
                        Questa funzione verifica che il file indicato da fromsia un file di caricamento valido (ovvero che sia stato caricato tramite il meccanismo di caricamento HTTP POST di PHP). Se il file è valido, verrà spostato al nome file specificato da to.
                        Questo tipo di controllo è particolarmente importante se esiste la possibilità che un'operazione eseguita con i file caricati possa rivelarne il contenuto all'utente o addirittura ad altri utenti sullo stesso sistema.    
                        */
                        if (move_uploaded_file($_FILES['attachment_file']['tmp_name'], $filePath)) {
                            $this->attachmentMapper->saveAttachmentToDatabase($fileName, $filePath, $_FILES['attachment_file']['type'], $noteId, 'file');
                            logger::info('Salvataggio file');
                            echo 'File caricato con successo';
                        } else {
                            $_SESSION["errors"] [] ="Errore durante il salvataggio della nota";
                            require_once 'application/views/_templates/error.php';
                            logger::error('Errore nel caricamento del file');
                            $this->index();
                            exit();
                        }
                    } else {
                        $_SESSION["errors"] [] ="Nessun file caricato";
                        require_once 'application/views/_templates/error.php';
                        logger::error('Nessun file caricato');
                        $this->index();
                        exit();
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
                        echo 'File di testo caricato con successo';
                        logger::info($fileName . " " . $filePath . " salvato");
                        $_SESSION['countFiletxt'] = $this->countFiletxt;
                    } else {
                        $_SESSION["errors"] []  = "Nessun testo fornito";
                        require_once 'application/views/_templates/error.php';
                        logger::error($this->fileName . " " . $this->filePath . " fallito salvataggio");
                        $this->index();
                        exit();
                    }
                    break;
            
                case 'draw':
                    if (isset($_POST['attachment_content'])) {
                        $timestamp = date("Y-m-d_H-i-s");
                        $drawingContent = $_POST['attachment_content'];
                        $fileName = "disegno_{$noteId}". "-" . $timestamp . ".png";
                        $filePath = $noteFolder . '/' . $fileName;
                        //Decodifica un file . codificato in base64 string.
                        /*
                        str_replace: questa funzione restituisce una stringa o un array in cui tutte le occorrenze di searchin subject vengono sostituite con il replacevalore specificato.
                        Per sostituire il testo in base a uno schema anziché a una stringa fissa, utilizzare preg_replace() .
                        */
                        $imageData = base64_decode(str_replace('data:image/png;base64,', '', $drawingContent));


                        /*
                        Questa funzione è identica alla chiamata successiva di fopen() , fwrite() e fclose() per scrivere dati in un file.
                        Se filenamenon esiste, il file viene creato. In caso contrario, il file esistente viene sovrascritto, a meno che il FILE_APPENDflag non sia impostato.*/
                        file_put_contents($filePath, $imageData);
                        $this->attachmentMapper->saveAttachmentToDatabase($fileName, $filePath, 'image/png', $noteId, 'draw');
                    } else {
                        $_SESSION["errors"] []  = "Nessun disegno fornito";
                        require_once 'application/views/_templates/error.php';
                        $this->index();
                        exit();
                    }
                    break;
            
                default:
                    $_SESSION["errors"] []  = "Allegato non valido";
                    require_once 'application/views/_templates/error.php';
                    $this->index();
                    break;
            }
            } else {
            $_SESSION["errors"] []  = "Allegato mancante";
            require_once 'application/views/_templates/error.php';
            $this->index();
            exit();
            }
    }
    
    //funzione fatta con l'aiuto di chatgpt
    public function zip($id = null)
    {
        if (!$this->validator->isUserLoggedIn()) {
            header("location: " . URL . "login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($id !== null) {
                $nameTitle= $_SESSION['title'];
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
                    $_SESSION["errors"] []  = "Creazione file zip fallita";
                    require_once 'application/views/_templates/error.php';
                    $this->index();
                    exit();
                }
            }
        }
    }

    public function deleteAttachment()
    {
        if (!$this->validator->isUserLoggedIn()) {
            header("location: " . URL . "login");
            exit();
        }
    
        if (isset($_POST['attachment_id'])) {
            $attachmentId = $_POST['attachment_id'];
            \logger::info("ID allegato: " . $attachmentId);
    
            $attachment = $this->attachmentMapper->getAttachment($attachmentId);
    
            if ($attachment !== null) {
                $filePath = $attachment['filePath'];
                $fileName = $attachment['fileName'];
                //Controlla se un file o una directory esiste.
                //Questa funzione restituirà un risultato falseper i collegamenti simbolici che puntano a file inesistenti.
                //Il controllo viene effettuato utilizzando l'UID/GID reale anziché quello effettivo.
                //poiché il tipo intero di PHP è firmato e molte piattaforme utilizzano interi a 32 bit, alcune funzioni del file system potrebbero restituire risultati inattesi per file più grandi di 2 GB.
                if (file_exists($filePath)) {
                    //Cancella filename. Simile alla funzione C di Unix unlink(). Restituisce true in caso di successo, false in caso di fallimento.
                    unlink($filePath);
                    \logger::info("File fisico eliminato: $fileName ($filePath)");
                } else {
                    \logger::warning("File non trovato sul disco: $filePath");
                }
    
                $result = $this->attachmentMapper->deleteAttachmentById($attachmentId);
                if ($result) {
                    \logger::info("Eliminato allegato: ID $attachmentId");
                    $data_creation =$_SESSION['data_creation'];
                    $userId = $_SESSION['UserId'];
                    header('location:' . URL . 'manage/goToCreateNotePage/' . $this->noteMapper->getIdByDateCreation($data_creation,$userId));
                    exit();
                } else {
                    $_SESSION["errors"][] = "Errore nell'eliminazione dell'attachment";
                    require_once 'application/views/_templates/error.php';
                    \logger::error("Errore DB nell'eliminazione: ID $attachmentId");
                    $this->index();
                    exit();
                }
            } else {
                $_SESSION["errors"][] = "Allegato non trovato";
                require_once 'application/views/_templates/error.php';
                \logger::error("Attachment non trovato: ID $attachmentId");
                $this->index();
                exit();
            }
        } else {
            $_SESSION["errors"][] = "ID allegato mancante";
            require_once 'application/views/_templates/error.php';
            \logger::error("POST attachment_id nullo o mancante");
            $this->index();
            exit();
        }
    }
    
    
    
    
}

