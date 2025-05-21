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
        \logger::info("Metodo: saveAttachmentToDatabase");
        \logger::info("Entrato in funzione SQL");

        \logger::info("nome file in mapper: " . $fileName);
        \logger::info("path file in mapper: " . $filePath);
        \logger::info("tipo: " . $mimeType);
        \logger::info("nome nota id: " . $noteId);
        // Controllo della connessione al database
        if ($this->connection->connect_error) {
            \logger::error("Errore di connessione: " . $this->connection->connect_error);
            return false;
        }
        // Crittografia del nome e percorso del file
        $encryptedFileName = $this->security->encrypt($fileName);
        $encryptedFilePath = $this->security->encrypt($filePath);
        // Preparazione della query di inserimento
        $query = "INSERT INTO attachment (nome_file, percorso_file, mime_type, note_id) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        // Controllo preparazione query
        if ($stmt === false) {
            \logger::error("Errore nella preparazione della query: " . $this->connection->error);
            return false;
        }
        // Associazione dei parametri alla query
        $stmt->bind_param("sssi", $encryptedFileName, $encryptedFilePath, $mimeType, $noteId);
        \logger::info("Bind dei parametri eseguito");
        // Esecuzione della query
        $result = $stmt->execute();
        if (!$result) {
            \logger::error("Errore nell'esecuzione della query: " . $stmt->error);
            return false;
        }

        // Commit della transazione per salvare i dati
        $this->connection->commit();
        \logger::info("Attachment salvato correttamente nel database");

        return true;
    }

    public function getAttachment($attachmentId)
    {
        \logger::info("Metodo: getAttachment");
        // Query per recuperare i dati dell'allegato dal database
        $query = "SELECT nome_file, percorso_file, mime_type, note_id FROM attachment WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        // Associazione del parametro ID alla query
        $stmt->bind_param("i", $attachmentId);
        // Esecuzione della query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $line = $result->fetch_assoc()) {
            // Decrittazione dei dati sensibili
            \logger::info("Allegato trovato, decrittazione dei dati ");
            $decryptedFileName = $this->security->decrypt($line['nome_file']);
            $decryptedFilePath = $this->security->decrypt($line['percorso_file']);
            \logger::info("Allegato decrittato correttamente. Restituzione dei dati.");
            // Restituzione dei dati come array
            return [
                'fileName' => $decryptedFileName,
                'filePath' => $decryptedFilePath,
                'mimeType' => $line['mime_type'],
                'noteId' => $line['note_id'],
            ];
        }
        \logger::info("Nessun allegato trovato con ID: $attachmentId");
        return null;
    }

    public function findAll($id_note)
    {
        \logger::info("Metodo: findAll");
        $query = "SELECT id, nome_file, percorso_file, mime_type FROM attachment WHERE note_id = ?";
        $stmt = $this->connection->prepare($query);
        // Controlla eventuali errori nella preparazione della query
        if ($stmt === false) {
            \logger::error("Errore nella query,  funzione findAll(), attachmentMapper. " . $this->connection->error);
            return [];
        }
        // Associa il parametro e avvia l'esecuzione
        $stmt->bind_param("i", $id_note);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $attachments = [];
            \logger::info("Risultati trovati, elaborazione degli allegati");
            // Cicla i risultati e decripta i dati sensibili
            while ($line = $result->fetch_assoc()) {
                $decryptedFileName = $this->security->decrypt($line['nome_file']);
                $decryptedFilePath = $this->security->decrypt($line['percorso_file']);
                // Crea l'oggetto allegato
                $attachment = new \models\attachment(
                    $line['id'],
                    $decryptedFileName,
                    $decryptedFilePath,
                    $line['mime_type'],
                    $id_note
                );
                $attachments[] = $attachment;
            }
            \logger::info("Totale allegati trovati e decrittati: " . count($attachments));
            return $attachments;
        }
        \logger::info("Nessun allegato trovato per note_id: $id_note");
        return [];
    }
    
    public function deleteAttachmentById($attachmentId)
    {
        \logger::info("Metodo: deleteAttachmentById");
        $query = "DELETE FROM attachment WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        // Verifica se la preparazione della query ha avuto successo
        if ($stmt === false) {
            \logger::error("Errore nella query: " . $this->connection->error);
            return false;
        }
        // Associa il parametro alla query
        $stmt->bind_param("i", $attachmentId);
        // Esegue la query
        $result = $stmt->execute();
        // Verifica il risultato
        if ($result) {
            \logger::info("Allegato: ID $attachmentId eliminato con successo");
        } else {
            \logger::error("Errore nell'eliminazione dell'allegato: ID $attachmentId: " . $stmt->error);
        }
        return $result;
    }
    

    
}
