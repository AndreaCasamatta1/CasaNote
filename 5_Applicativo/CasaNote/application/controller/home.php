<?php


class home
{
    public $noteMapper;

    public function index()
    {
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/header.php';
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
        require 'application/views/_templates/navbar2.php';
        require 'application/views/_templates/header.php';
        require 'application/views/manage/note.php';
        require 'application/views/_templates/footer.php';
    }

    public function filter()
    {
        session_start();
        if (isset($_POST['field'])) {
            $field = $_POST['field'];
            $this->noteMapper = new \models\NoteMapper();
            $allNote = $this->noteMapper->getAllFiltered($field);
        }
        header("location: " . URL . "home/main");

    }

}
