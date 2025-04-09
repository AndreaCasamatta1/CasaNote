<?php

class manage
{
    private $noteMapper;
    private $validator;

    private $attachmentMapper;
    function __construct()
    {
        require_once "application/models/Note.php";
        require_once "application/models/NoteMapper.php";
        $this->noteMapper = new \models\NoteMapper();
        require_once "application/libs/Validator.php";
        $this->validator = new Validator();
//        require "application/views/_templates/footer.php";
        require_once "application/models/Attachment.php";
        require_once "application/models/AttachmentMapper.php";
        $this->attachmentMapper = new \models\AttachmentMapper();
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
                    $newNote = new \models\Note($id, $title, $noteToUpdate->getDateCreation(), $data_last_update);
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
                $note = new \models\Note(null, $title, $data_creation, $data_last_update);
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
            $noteId = $this->noteMapper->getLastNoteId() + 1;

            if ($noteId === null) {
                echo json_encode(['success' => false, 'message' => 'Nota non trovata']);
                Logger::error('Nota non trovata');
                exit();
            }
            Logger::info('id ' . $noteId);
            $noteFolder = "uploads/note_{$noteId}";
            if (!file_exists($noteFolder)) {
                mkdir($noteFolder, 0777, true);
                Logger::info('Cartella creata' . $noteFolder);
            }

            switch ($attachmentType) {
                case 'file':
                    Logger::info('Tipo di Attchment : File');
                    if (isset($_FILES['attachment_file']) && $_FILES['attachment_file']) {
                        $fileName = basename($_FILES['attachment_file']['name']);
                        $filePath = $noteFolder . '/' . $fileName;
                        Logger::info('POST DATA: ' . $filePath . ' ' . $fileName);
                        if (move_uploaded_file($_FILES['attachment_file']['tmp_name'], $filePath)) {
                            $this->attachmentMapper->saveAttachmentToDatabase($fileName, $filePath, $_FILES['attachment_file']['type'], $noteId, 'file');
                            Logger::info('salvataggio file');
                            echo json_encode(['success' => true]);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Errore nel caricamento del file']);
                            Logger::error('Errore nel caricamento del file');
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Nessun file caricato']);
                        Logger::error('Nessun file caricato');
                    }
                    break;
                case 'text':
                    if (isset($_POST['attachment_content'])) {

                        $timestamp = date("Y-m-d_H-i-s");
                        $textContent = $_POST['attachment_content'];
                        $fileName = "testo_{$noteId}" . "-" . $timestamp . ".txt";
                        $filePath = $noteFolder . '/' . $fileName;
                        Logger::info('POST DATA: ' . $filePath . ' ' . $fileName);
                        file_put_contents($filePath, $textContent);
                        $this->attachmentMapper->saveAttachmentToDatabase($fileName, $filePath, 'text/plain', $noteId, 'text');
                        echo json_encode(['success' => true]);
                        Logger::info($fileName . " " . $filePath . " salvato");

                        $_SESSION['countFiletxt'] = $this->countFiletxt;  // Salva il nuovo valore del contatore nella sessione
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Nessun testo fornito']);
                        Logger::error($this->fileName . " " . $this->filePath . " fallito salvataggio");
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
    function generateRandomNumber($min,$max) {
        static $arrayNumeri = [];

        do {
            $numero = rand($min, $max);
        } while (in_array($numero, $arrayNumeri));

        $arrayNumeri[] = $numero;
        return $numero;
    }
}

