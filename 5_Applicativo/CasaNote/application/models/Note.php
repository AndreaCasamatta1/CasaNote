<?php

namespace models;

class Note
{
    private $id;
    private $title;
    private $dateCreation;


    private $dateLastUpdate;

    /**
     * @param $title
     * @param $dateCreation
     * @param $dateLastUpdate
     */
    public function __construct($title, $dateCreation, $dateLastUpdate)
    {
        $this->id = id;
        $this->title = $title;
        $this->dateCreation = $dateCreation;
        $this->dateLastUpdate = $dateLastUpdate;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param mixed $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return mixed
     */
    public function getDateLastUpdate()
    {
        return $this->dateLastUpdate;
    }

    /**
     * @param mixed $dateLastUpdate
     */
    public function setDateLastUpdate($dateLastUpdate)
    {
        $this->dateLastUpdate = $dateLastUpdate;
    }


}