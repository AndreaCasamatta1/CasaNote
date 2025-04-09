<?php

namespace models;

class Attachment
{
    private $id;
    private $fileName;
    private $filePath;
    private $mimeType;
    private $noteId;

    public function __construct($id, $fileName, $filePath, $mimeType, $noteId)
    {
        $this->id = $id;
        $this->fileName = $fileName;
        $this->filePath = $filePath;
        $this->mimeType = $mimeType;
        $this->noteId = $noteId;
    }

    // Getter e Setter
    public function getFileName() {
        return $this->fileName;
    }

    public function getFilePath() {
        return $this->filePath;
    }

    public function getMimeType() {
        return $this->mimeType;
    }

    public function getNoteId() {
        return $this->noteId;
    }
}
