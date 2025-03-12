<?php

class manage
{
    function goToCreateNotePage()
    {
        require "application/views/_templates/header.php";
        require "application/views/manage/createNote.php";
//        require "application/views/_templates/footer.php";
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