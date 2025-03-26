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
        $this->noteMapper = new \models\NoteMapper();

        if (isset($_POST['field']) && !empty($_POST['field'])) {
            $field = trim($_POST['field']);
            $_SESSION['filtered_notes'] = $this->noteMapper->getAllFiltered($field);
        } else {
            unset($_SESSION['filtered_notes']); // Rimuove il filtro se il campo è vuoto
        }

        $this->main(); // Richiama direttamente `main()`, mantenendo la struttura esistente
    }

    public function resetFilter()
    {
        session_start();
        unset($_SESSION['filtered_notes']); // Rimuove il filtro
        $this->main();
    }
    public function deleteNote($id = null)
    {

        if ($id === null) {
            require_once 'application/views/_templates/error.php';
            $this->index();
            return;
        }

        $note = $this->noteMapper->findById($id);

        if ($note && $this->noteMapper->deleteNote($note)) {
            header("location: " . URL . "home/main");
            exit();
        } else {
            require_once 'application/views/_templates/error.php';
            $this->index();
        }
    }
    public function user()
    {
        require 'application/views/_templates/navbar2.php';
        require 'application/views/_templates/header.php';
        require 'application/views/profile/index.php';
        require 'application/views/_templates/footer.php';
    }





}
