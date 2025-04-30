<?php

namespace models;

class attachmentMapper
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
        $this->security = new \libs\security();
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

        $encryptedFileName = $this->security->encrypt($fileName);
        $encryptedFilePath = $this->security->encrypt($filePath);

        $query = "INSERT INTO attachment (nome_file, percorso_file, mime_type, note_id) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);

        if ($stmt === false) {
            \logger::error("Errore nella preparazione della query: " . $this->connection->error);
            return false;
        }

        $stmt->bind_param("sssi", $encryptedFileName, $encryptedFilePath, $mimeType, $noteId);
        \logger::info("Bind dei parametri eseguito");

        $result = $stmt->execute();
        if (!$result) {
            \logger::error("Errore nell'esecuzione della query: " . $stmt->error);
            return false;
        }

        // Commit della transazione
        $this->connection->commit();
        \logger::info("Attachment salvato correttamente nel database");

        return true;
    }

    public function getAttachment($attachmentId)
    {
        $query = "SELECT nome_file, percorso_file, mime_type, note_id FROM attachment WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $attachmentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $line = $result->fetch_assoc()) {
            // Decriptiamo il nome del file e il percorso
            $decryptedFileName = $this->security->decrypt($line['nome_file']);
            $decryptedFilePath = $this->security->decrypt($line['percorso_file']);
            return [
                'fileName' => $decryptedFileName,
                'filePath' => $decryptedFilePath,
                'mimeType' => $line['mime_type'],
                'noteId' => $line['note_id'],
            ];
        }

        return null;
    }
}
