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
    { \logger::info("Entrato in funzione SQL");
        \logger::info("nome file in mapper: " . $fileName);
        \logger::info("path file in mapper: " . $filePath);
        \logger::info("tipo: " . $mimeType);
        \logger::info("nome nota id: " . $noteId);

        $query = "INSERT INTO attachment (nome_file, percorso_file, mime_type, note_id)
          VALUES (:nome_file, :percorso_file, :mime_type, :note_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome_file', $fileName, PDO::PARAM_STR);
        $stmt->bindValue(':percorso_file', $filePath, PDO::PARAM_STR);
        $stmt->bindValue(':mime_type', $mimeType, PDO::PARAM_STR);
        $stmt->bindValue(':note_id', $noteId, PDO::PARAM_INT);
        $stmt->execute();

    }
}