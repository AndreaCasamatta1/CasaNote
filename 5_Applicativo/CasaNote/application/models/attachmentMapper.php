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
        $query = "INSERT INTO attachment (nome_file, percorso_file, mime_type, note_id)
              VALUES (:nome_file, :percorso_file, :mime_type, :note_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nome_file', $fileName);
        $stmt->bindParam(':percorso_file', $filePath);
        $stmt->bindParam(':mime_type', $mimeType);
        $stmt->bindParam(':note_id', $noteId);

        $stmt->execute();
    }
}