<?php

namespace models;
require_once "Note.php";
class NoteMapper
{
    private $validator;
    private $connection;


    public function __construct()
    {
        require_once "application/libs/validator.php";
        $this->validator = new \Validator();
        $this->connection = new \mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
    }


    /**
     * @return array
     */
    public function fetchAll(): array
    {
        $selectNote = "SELECT * FROM note ORDER BY title ASC";
        $note = $this->connection->query($selectNote);
        $allNote = array();
        var_dump($allNote);
        foreach ($note as $line) {
            $note = new Note($line['title'], $line['date_creation'], $line['date_last_update']);
            $allNote[] = $note;
            unset($note);
        }
        return $allNote;
    }


//    public function findById($key): Note
//    {
//        $selectNote = "SELECT * FROM faq WHERE id=$key";
//        $faqResult = $this->connection->query($selectFaq);
//        $line = $faqResult->fetch_assoc();
//        $faq = new Faq($line['id'], $line['question'], $line['answer'], $line['link'], $line['position']);
//        return $note;
//    }


//    public function addFaq(Faq $faq)
//    {
//        $question = $faq->getQuestion();
//        $answer = $faq->getAnswer();
//        $link = $faq->getLink();
//        $position = $faq->getPosition();
//
//        $addFaq = "INSERT INTO faq (question,answer,link,position) VALUES ('$question', '$answer', '$link', $position)";
//        return $this->connection->query($addFaq);
//    }
//
    public function deleteNote(Note $note)
    {
        $id = $note->getId();
        $deleteNote = "DELETE from note WHERE id = $id";
        return $this->connection->query($deleteNote);
    }
    public function findById($key): Note
    {
        $selectNote = "SELECT * FROM note WHERE id=$key";
        $faqResult = $this->connection->query($selectNote);
        $line = $faqResult->fetch_assoc();
        $note = new Note($line['title'], $line['date_creation'], $line['date_last_update']);;
        return $note;
    }


    public function getAllFIltered($field)
    {
        $selectQuery = "SELECT * FROM note WHERE title LIKE '%$field%' ";
        return $this->connection->query($selectQuery);

    }

//
//    public function updateFaq(Faq $faqToUpdate,Faq $newFaq)
//    {
//        $id = $faqToUpdate->getId();
//        $question = $newFaq->getQuestion();
//        $answer = $newFaq->getAnswer();
//        $link = $newFaq->getLink();
//        $position = $newFaq->getPosition();
//
//        $updateFaq = "UPDATE faq SET question ='$question', answer = '$answer',link = '$link',position = $position WHERE id =$id";
//        return $this->connection->query($updateFaq);
//    }
}