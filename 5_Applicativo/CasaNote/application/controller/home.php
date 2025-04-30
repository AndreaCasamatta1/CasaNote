<?php
session_start();

class home
{
    public $noteMapper;

    public function index()
    {
        require_once 'application/views/_templates/navbar.php';
        require_once 'application/views/_templates/header.php';
        require_once 'application/views/home/index.php';
    }

    public function __construct()
    {
        require 'application/models/noteMapper.php';
        $this->noteMapper = new \models\noteMapper();
    }

    public function main()
    {

        if (isset($_SESSION['order_by_date'])) {
            $order = $_SESSION['order_by_date'];
        } else {
            $order = 'asc';
        }


        $allNote = $this->noteMapper->fetchAllSortedByDate($order);
        require 'application/views/_templates/navbar2.php';
        require 'application/views/_templates/header.php';
        require 'application/views/manage/note.php';
        require 'application/views/_templates/footer.php';
    }

    public function filter()
    {
        $this->noteMapper = new \models\noteMapper();

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
        unset($_SESSION['filtered_notes']);
        unset($_SESSION['order_by_date']);
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

    public function sortByDate()
    {


        if (isset($_SESSION['order_by_date']) && $_SESSION['order_by_date'] === 'desc') {
            $_SESSION['order_by_date'] = 'asc';
        } else {
            $_SESSION['order_by_date'] = 'desc';
        }
        $this->main();
    }


    public function user()
    {
        require 'application/views/_templates/navbar2.php';
        require 'application/views/_templates/header.php';
        require 'application/views/profile/index.php';
        require 'application/views/_templates/footer.php';
    }





}
