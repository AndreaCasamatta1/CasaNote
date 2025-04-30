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
}

