<?php


class Home
{

    public function index()
    {
        require 'application/views/_templates/header.php';

        require 'application/views/home/index.php';
    }

    public function main()
    {
        require 'application/models/NoteMapper.php';
        $noteMapper=new \models\NoteMapper();
        $allNote = $noteMapper->fetchAll();
        require 'application/views/_templates/header.php';
        require 'application/views/home/note.php';
        require 'application/views/_templates/footer.php';
    }
}
