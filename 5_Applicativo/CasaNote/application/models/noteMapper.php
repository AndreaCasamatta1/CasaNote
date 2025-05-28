<?php

namespace models;
require_once "note.php";


class noteMapper
{
    private $validator;
    private $connection;
    private $security;

    public function __construct()
    {
        require_once "application/libs/validator.php";
        require_once "application/libs/security.php";
        $this->validator = new \validator();
        $this->connection = new \mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
        $this->security = new \libs\security(); // Inizializza la classe di sicurezza
    }

    /**
     * @return array
     */
    public function fetchAll(): array
    {
        \logger::info("Metodo: fetchAll");
        // Avvia la sessione e ottiene l'ID dell'utente loggato
        session_start();
        $userId = $_SESSION['UserId'];
        // Query per recuperare tutte le note dell'utente, ordinate per titolo
        $selectNote = "SELECT * FROM note WHERE user_id = ? ORDER BY title ASC";
        $stmt = $this->connection->prepare($selectNote);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $allNote = [];
        //  crea oggetti Note dopo aver decriptato il titolo
        while ($line = $result->fetch_assoc()) {
            //Decripta il titolo
            \logger::info("Decriptazione del titolo della nota con ID: " . $line['id']);
            $title = $this->security->decrypt($line['title']);
            $note = new note($line['id'], $title, $line['date_creation'], $line['date_last_update'], $line['user_id']);
            $allNote[] = $note;
        }

        return $allNote;
    }

    public function addNote(note $note)
    {
        \logger::info("Metodo: addNote");
        // Avvia la sessione per ottenere l'ID dell'utente loggato
        session_start();
        $title = $note->getTitle();
        $dateCreation = $note->getDateCreation();
        $datelastUpdate = $note->getDateLastUpdate();
        $userId = $_SESSION['UserId'];

        \logger::info("Dettagli della nota - userId: $userId, titolo (originale): $title");

        // Crittografia de titolo prima di inserirlo nel database
        $encryptedTitle = $this->security->encrypt($title);

        $addNote = "INSERT INTO note(title, date_creation, date_last_update, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($addNote);
        $stmt->bind_param("sssi", $encryptedTitle, $dateCreation, $datelastUpdate, $userId);
        return $stmt->execute();
    }

    public function deleteNote(note $note)
    {
        \logger::info("Metodo: deleteNote");
        session_start();
        $id = $note->getId();
        $userId = $_SESSION['UserId'];
        // Prepara la query per eliminare la nota dal database
        $deleteNote = "DELETE FROM note WHERE id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($deleteNote);
        $stmt->bind_param("ii", $id, $userId);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            \logger::info("Nota ID: $id eliminata correttamente dal database.");
            $folderPath = __DIR__ . "/../../uploads/Note_{$id}";
            if (is_dir($folderPath)) {
                \logger::info("Cartella associata alla nota trovata: $folderPath");
                $this->deleteFolder($folderPath);
                \logger::info("Cartella eliminata con successo.");
            }
        }

        return $result;
    }

    private function deleteFolder($folder)
    {
        \logger::info("Metodo: deleteFolder");
        array_map('unlink', glob($folder . '/*'));
        // Elimina la cartella
        rmdir($folder);
    }

    public function findById($key): ?note
    {\logger::info("Metodo: findById");
        session_start();
        $userId = $_SESSION['UserId'];
        // Query per selezionare la nota corrispondente all'ID e all'utente attivo
        $selectNote = "SELECT * FROM note WHERE id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($selectNote);
        $stmt->bind_param("ii", $key, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($line = $result->fetch_assoc()) {
            // Decripta il titolo prima di creare l'oggetto nota
            $title = $this->security->decrypt($line['title']);
            \logger::info("Nota con ID $key recuperata ");
            return new note($line['id'], $title, $line['date_creation'], $line['date_last_update'], $line['user_id']);
        } else {
            return null;
        }
    }

    public function getAllFiltered($field): array
    {
        \logger::info("Metodo: getAllFiltered");
        session_start();
        $userId = $_SESSION['UserId'];

        // Prendi tutte le note per l'utente, senza filtro sul titolo
        $selectNote = "SELECT * FROM note WHERE user_id = ?";
        $stmt = $this->connection->prepare($selectNote);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $allNote = [];
        // Cicla tra le note e applica il filtro dopo aver decriptato il tiitolo
        while ($line = $result->fetch_assoc()) {
            $title = $this->security->decrypt($line['title']);

            // Verifica se il filtro è contenuto nel titolo (case-insensitive)
            if (stripos($title, $field) !== false) {
                \logger::info("Filtro corrispondente trovato nel titolo: $title");
                $note = new note(
                    $line['id'],
                    $title,
                    $line['date_creation'],
                    $line['date_last_update'],
                    $line['user_id']
                );
                $allNote[] = $note;
            }
        }

        return $allNote;
    }


    public function updateNote(note $noteToUpdate, note $newNote)
    {
        \logger::info("Metodo: updateNote");
        session_start();
        //  ID della nota da aggiornare
        $id = $noteToUpdate->getId();
        // Recupero nuovi dati della nota
        $title = $newNote->getTitle();
        $dateLastUpdate = $newNote->getDateLastUpdate();
        $userId = $_SESSION['UserId'];
        \logger::info("Aggiornamento nota ");
        // Crittografia del titolo prima dell'aggiornamento
        $encryptedTitle = $this->security->encrypt($title);
        // Preparazione della query di aggiornamento
        $updateNote = "UPDATE note SET title = ?, date_last_update = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($updateNote);
        $stmt->bind_param("ssii", $encryptedTitle, $dateLastUpdate, $id, $userId);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function fetchAllSortedByDate($order = 'asc')
    {\logger::info("Metodo: fetchAllSortedByDate ");
        session_start();
        $userId = $_SESSION['UserId'];
        // Costruzione dinamica dell'ORDER BY in base al parametro $order
        $selectNote = "SELECT * FROM note WHERE user_id = ? ORDER BY date_last_update " .
            ($order === 'desc' ? 'DESC' : 'ASC') . ", date_creation " .
            ($order === 'desc' ? 'DESC' : 'ASC');

        $stmt = $this->connection->prepare($selectNote);
        // Binding del parametro user_id e esecuzione della query
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $allNote = [];
        while ($line = $result->fetch_assoc()) {
            // Decrittazione del titolo prima della creazione dell'oggetto nota
            $title = $this->security->decrypt($line['title']);
            \logger::info("Recuperate  note ordinate per data " . $order);
            $note = new note($line['id'], $title, $line['date_creation'], $line['date_last_update'], $line['user_id']);
            $allNote[] = $note;
        }

        return $allNote;
    }

    public function getLastNoteId()
    {
        \logger::info("Metodo: getLastNoteId ");
        // Query per ottenere l'ID della nota più recente
        $selectNote = "SELECT id FROM note ORDER BY id DESC LIMIT 1";
        $result = $this->connection->query($selectNote);
        // Controllo se è stato trovato un risultato
        if ($line = $result->fetch_assoc()) {
            \logger::info("Ultimo ID nota trovato: " . $line['id']);
            return (int) $line['id'];
        }
        return null;
    }

    public function getLastNoteIdByUser($userId)
    {\logger::info("Metodo: getLastNoteIdByUser");
        // Query per ottenere l'ID della nota più recente di un utente
        $selectNote = "SELECT id FROM note WHERE user_id = ? ORDER BY id DESC LIMIT 1";
        $stmt = $this->connection->prepare($selectNote);
        if ($stmt === false) {
            \logger::error('Errore query: ' . $this->connection->error);
            return null;
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($line = $result->fetch_assoc()) {
            return (int) $line['id'];
        }
        return null;
    }

    public function getIdByDateCreation($date_creation, $userId) {
        \logger::info("Metodo: getIdByDateCreation");
        $query = "SELECT id FROM note 
              WHERE date_creation = ? 
                AND user_id = ? 
              LIMIT 1";

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            \logger::error("Errore preparazione statement: " . $this->connection->error);
            return null;
        }

        $stmt->bind_param("si", $date_creation, $userId);
        $stmt->execute();
        $stmt->bind_result($noteId);
        $found = $stmt->fetch();
        $stmt->close();

        if ($found) {
            \logger::info("Nota trovata con ID: $noteId");
            return $noteId;
        } else {
            \logger::info("Nessuna nota trovata con i parametri forniti");
            return null;
        }
    }


}