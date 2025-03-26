<?php

class manage
{
    private $noteMapper;
    private $validator;
    function __construct()
    {
        require_once "application/models/NoteMapper.php";
        $this->noteMapper = new \models\NoteMapper();
        require_once "application/libs/Validator.php";
        $this->validator = new Validator();
//        require "application/views/_templates/footer.php";
    }
    function index()
    {

    }
    function goToCreateNotePage()
    {
        require 'application/views/_templates/navbar2.php';
        require "application/views/_templates/header.php";
        require "application/views/manage/createNote.php";
    }
    public function deleteNote($id=null)
    {
        $note = $this->noteMapper->findById();
        if ($this->noteMapper->deleteFaq($note)) {
            header("location: " . URL . "home/main");
            exit();
        } else {
            require_once 'application/views/_templates/error.php';
            $this->index();
        }
    }
    public function saveNote()
    {
            if (isset($_POST['title'])) {
                $title = $this->validator->sanitizeInput($_POST['title']);
                    $data_creation = date('Y-m-d H:i:s');
                    $note = new \models\Note($title,$data_creation,$data_creation);

                if ($this->noteMapper->addNote($note, $data_creation,$data_creation)) {
                        header('location:' . URL . 'home/main');
                        exit();
                    } else {
                        require_once 'application/views/_templates/error.php';
                        $this->goToCreateNotePage();
                    }
                }
             else {
                 require_once 'application/views/_templates/error.php';
                 $this->goToCreateNotePage();
             }
    }

    public function updateNote()
    {
        if (!empty($_POST['id'])) {
            $id = $this->validator->sanitizeInput($_POST['id']);
            $noteToUpdate = $this->noteMapper->findById($id);
            $newNote = new Note($id, $title);

            if ($this->faqMapper->updateFaq($faqToUpdate, $newFaq)) {
                header('location:' . URL . 'admin');
                exit();
            } else {
                require_once 'application/views/admin/error.php';
                $this->index();
            }
        }
    }
}