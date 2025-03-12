<?php


class home
{
    public $noteMapper;
    public function index()
    {
        require 'application/views/_templates/header.php';
        require 'application/views/_templates/navbar.php';
        require 'application/views/home/index.php';
    }

    public function __construct()
    {
        require 'application/models/NoteMapper.php';
        $this->noteMapper = new \models\NoteMapper();
    }
    public function main()
    {
        $allNote = $this->noteMapper->fetchAll();
        require 'application/views/_templates/header.php';
        require 'application/views/manage/note.php';
        require 'application/views/_templates/footer.php';
    }
    public function filter()
    {
        session_start();
            $note = "";
            if (isset($_POST['field'])) {
                $field = $_POST['field'];
                $notes = $this->noteMapper->getAllFIltered($field);
            }
            $allNote = $notes;
            header("location: " . URL . "home/main");

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
}
