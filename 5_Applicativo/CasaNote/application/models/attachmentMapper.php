<?php

namespace models;

class attachmentMapper
{
    private $validator;
    private $connection;

    public function __construct()
    {
        require_once "application/libs/validator.php";
        $this->validator = new \validator();
        $this->connection = new \mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
    }
    public function saveAttachmentToDatabase($fileName, $filePath, $mimeType, $noteId, $attachmentType)
    {
        \logger::info("Entrato in funzione SQL");
        \logger::info("nome file in mapper: " . $fileName);
        \logger::info("path file in mapper: " . $filePath);
        \logger::info("tipo: " . $mimeType);
        \logger::info("nome nota id: " . $noteId);
        if ($this->connection->connect_error) {
            \logger::error("Errore di connessione: " . $this->connection->connect_error);
            return false;
        }
        $query = "INSERT INTO attachment (nome_file, percorso_file, mime_type, note_id) 
              VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);

        if ($stmt === false) {
            \logger::error("Errore nella preparazione della query: " . $this->connection->error);
            return false;
        }

        $stmt->bind_param("sssi", $fileName, $filePath, $mimeType, $noteId);
        \logger::info("Bind dei parametri eseguito");
        $result = $stmt->execute();
//        if ($result) {
            \logger::error("Errore nell'esecuzione della query: " . $stmt->error);
            return false;
//        }

        $this->connection->commit();
        \logger::info("Attachment salvato correttamente nel database");
        return true;
    }

}