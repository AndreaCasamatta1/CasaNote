<?php

class manage
{
<<<<<<< HEAD
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
=======
    function goToCreateNotePage()
    {        require 'application/views/_templates/navbar2.php';
>>>>>>> 8ac242815941052928340d8dd17646b24a01dca5
        require "application/views/_templates/header.php";
        require "application/views/manage/createNote.php";
    }
<<<<<<< HEAD
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
    public function saveOrUpdateNote()
    {
            //se è una modifica
            if (isset($_POST['title'])) {
                $title = $this->validator->sanitizeInput($_POST['title']);
//                //se è una modifica
//                if (!empty($_POST['id'])) {
//                    $id = $this->validator->sanitizeInput($_POST['id']);
//                    $faqToUpdate = $this->faqMapper->findById($id);
//                    $newFaq = new Faq($id, $question, $answer, $link, $position);
//
//                    if ($this->faqMapper->updateFaq($faqToUpdate, $newFaq)) {
//                        header('location:' . URL . 'admin');
//                        exit();
//                    } else {
//                        require_once 'application/views/admin/error.php';
//                        $this->index();
//                    }
//                } //se è una nuova faq
//                else {
//
                    $data_creation = date('Y-m-d H:i:s');
                    $note = new \models\Note($title,$data_creation,$data_creation);

                    var_dump($this->noteMapper->addNote($note));
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
=======

>>>>>>> 8ac242815941052928340d8dd17646b24a01dca5
}