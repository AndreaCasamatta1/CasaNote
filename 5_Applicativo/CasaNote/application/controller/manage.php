<?php

class manage
{
    private $noteMapper;
    private $validator;
    function __construct()
    {
        require_once "application/models/Note.php";
        require_once "application/models/NoteMapper.php";
        $this->noteMapper = new \models\NoteMapper();
        require_once "application/libs/Validator.php";
        $this->validator = new Validator();
//        require "application/views/_templates/footer.php";
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
                header("location: " . URL . "home/main");
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
                    $newNote = new \models\Note($id, $title, $noteToUpdate->getDateCreation(),$data_last_update);
                    if ($this->noteMapper->updateNote($noteToUpdate, $newNote)) {
                        header('location:' . URL . 'home/main');
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
                    header('location:' . URL . 'home/main');
                    exit();
                } else {
                    require_once 'application/views/_templates/error.php';
                    $this->goToCreateNotePage();
                }
            }
        }
    }

}