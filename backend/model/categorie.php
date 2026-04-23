<?php

class Categorie{
    private $id;
    private $parent_id;
    private $nom;
    private $is_active;
    private $created_at;
    private $updated_at;

    public function getId()
    {
        return $this->id;
    }

    public function getParentId()
    {
        return $this->parent_id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}