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
        foreach ($note as $line) {
            $note = new Note( $line['id'], $line['title'], $line['date_creation'], $line['date_last_update']);
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


    public function addNote(Note $note)
    {
        $title = $note->getTitle();
        $dateCreation = $note->getDateCreation();
        $datelastUpdate = $note->getDateLastUpdate();

        $addNote = "INSERT INTO note(title,date_creation,date_last_update) VALUES ('$title', '$dateCreation', '$datelastUpdate')";
        return $this->connection->query($addNote);
    }

    public function deleteNote(Note $note)
    {
        $id = $note->getId();
        $deleteNote = "DELETE FROM note WHERE id = ?";

        $stmt = $this->connection->prepare($deleteNote);
        $stmt->bind_param("i", $id); // Bind dell'ID come intero
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function findById($key): ?Note
    {
        $selectNote = "SELECT * FROM note WHERE id = ?";

        $stmt = $this->connection->prepare($selectNote);
        $stmt->bind_param("i", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($line = $result->fetch_assoc()) {
            $note = new Note($line['id'], $line['title'], $line['date_creation'], $line['date_last_update']);
            return $note;
        } else {
            return null; // Se la nota non esiste, restituisce null
        }
    }



    public function getAllFiltered($field): array
    {
        $selectNote = "SELECT * FROM note WHERE title LIKE ?";
        $stmt = $this->connection->prepare($selectNote);
        $searchTerm = "%$field%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();

        $result = $stmt->get_result();
        $allNote = [];

        while ($line = $result->fetch_assoc()) {
            $note = new Note($line['title'], $line['date_creation'], $line['date_last_update']);
            $allNote[] = $note;
        }

        return $allNote;
    }



    public function updateNote(Note $noteToUpdate,Note $newNote)
    {
        $id = $noteToUpdate->getId();
        $question = $newNote->getTitle();
        $dateLastUpdate = $newNote->getDateLastUpdate();

        $updateFaq = "UPDATE note SET title ='title', date_last_update = '$dateLastUpdate' WHERE id =$id";
        return $this->connection->query($updateFaq);
    }
}