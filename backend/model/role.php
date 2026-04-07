<?php

class Role{
    private $id;
    private $nom;
    private $is_active;
    private $description;

    public function getId()
    {
        return $this->id;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getNom()
    {
        return $this->nom;
    }
}