<?php

class Annonce{
    private $id;
    private $user_id;
    private $categorie_id;
    private $title;
    private $description;
    private $price;
    private $type;
    private $status;
    private $location;
    private $view_count;
    private $expires_at;
    private $created_at;
    private $updated_at;

    public function getId()
    {
        return $this->id;
    }

    public function getUtilisateurId()
    {
        return $this->user_id;
    }

    public function getCategorieId()
    {
        return $this->categorie_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getViewCount()
    {
        return $this->view_count;
    }

    public function getExpiresAt()
    {
        return $this->expires_at;
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