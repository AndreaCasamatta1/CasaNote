<?php

namespace models;
require_once "note.php";
namespace models;
require_once "note.php";

class noteMapper
{
    private $validator;
    private $connection;

    public function __construct()
    {
        require_once "application/libs/validator.php";
        $this->validator = new \validator();
        $this->connection = new \mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
    }

    /**
     * @return array
     */
    public function fetchAll(): array
    {
        session_start();
        $userId = $_SESSION['UserId'];
        $selectNote = "SELECT * FROM note WHERE user_id = ? ORDER BY title ASC";
        $stmt = $this->connection->prepare($selectNote);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $allNote = [];
    
        while ($line = $result->fetch_assoc()) {
            $note = new note($line['id'], $line['title'], $line['date_creation'], $line['date_last_update'], $line['user_id']);
            $allNote[] = $note;
        }
    
        return $allNote;
    }
    

    public function addNote(note $note)
    {
        session_start();
        $title = $note->getTitle();
        $dateCreation = $note->getDateCreation();
        $datelastUpdate = $note->getDateLastUpdate();
        $userId = $_SESSION['UserId'];
    
        $addNote = "INSERT INTO note(title, date_creation, date_last_update, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($addNote);
        $stmt->bind_param("sssi", $title, $dateCreation, $datelastUpdate, $userId);
        return $stmt->execute();
    }
    

    public function deleteNote(note $note)
    {
        session_start();
        $id = $note->getId();
        $userId = $_SESSION['UserId'];
    
        $deleteNote = "DELETE FROM note WHERE id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($deleteNote);
        $stmt->bind_param("ii", $id, $userId); // ID nota e ID utente
        $result = $stmt->execute();
        $stmt->close();
    
        return $result;
    }
    

    public function findById($key): ?note
    {
        session_start();
        $userId = $_SESSION['UserId'];
        $selectNote = "SELECT * FROM note WHERE id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($selectNote);
        $stmt->bind_param("ii", $key, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        if ($line = $result->fetch_assoc()) {
            return new note($line['id'], $line['title'], $line['date_creation'], $line['date_last_update'], $line['user_id']);
        } else {
            return null;
        }
    }
    

    public function getAllFiltered($field): array
    {
        session_start();
        $userId = $_SESSION['UserId'];
        $selectNote = "SELECT * FROM note WHERE user_id = ? AND title LIKE ?";
        $stmt = $this->connection->prepare($selectNote);
        $searchTerm = "%$field%";
        $stmt->bind_param("is", $userId, $searchTerm);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $allNote = [];
    
        while ($line = $result->fetch_assoc()) {
            $note = new note($line['id'], $line['title'], $line['date_creation'], $line['date_last_update'], $line['user_id']);
            $allNote[] = $note;
        }
    
        return $allNote;
    }
    

    public function updateNote(note $noteToUpdate, note $newNote)
    {
        session_start();
        $id = $noteToUpdate->getId();
        $title = $newNote->getTitle();
        $dateLastUpdate = $newNote->getDateLastUpdate();
        $userId = $_SESSION['UserId'];
    
        $updateNote = "UPDATE note SET title = ?, date_last_update = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($updateNote);
        $stmt->bind_param("ssii", $title, $dateLastUpdate, $id, $userId);
        $result = $stmt->execute();
        $stmt->close();
    
        return $result;
    }
    

    public function fetchAllSortedByDate($order = 'asc')
    {
        session_start();
        $userId = $_SESSION['UserId'];
    
        $selectNote = "SELECT * FROM note WHERE user_id = ? ORDER BY date_last_update " . 
            ($order === 'desc' ? 'DESC' : 'ASC') . ", date_creation " . 
            ($order === 'desc' ? 'DESC' : 'ASC');
    
        $stmt = $this->connection->prepare($selectNote);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $allNote = [];
        while ($line = $result->fetch_assoc()) {
            $note = new note($line['id'], $line['title'], $line['date_creation'], $line['date_last_update'], $line['user_id']);
            $allNote[] = $note;
        }
    
        return $allNote;
    }

    public function getLastNoteId()
    {
        $selectNote = "SELECT id FROM note ORDER BY id DESC LIMIT 1";
        $result = $this->connection->query($selectNote);
        if ($line = $result->fetch_assoc()) {
            return (int) $line['id'];
        }
        return null;
    }
    

}
