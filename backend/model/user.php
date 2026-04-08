<?php

class User{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $mot_de_passe;
    private $role_id;
    private $telephone;
    private $ville;
    private $est_actif;
    private $date_inscription;
    private $email_verifie;
    private $average_rating;
    private $ratings_count;
    private $modif_inscription;

    public function getAverageRating()
    {
        return $this->average_rating;
    }

    public function getRatingsCount()
    {
        return $this->ratings_count;
    }

    public function getModifInscription()
    {
        return $this->modif_inscription;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function getAdresse()
    {
        return $this->ville;
    }

    public function getEstActif()
    {
        return $this->est_actif;
    }

    public function getDateIns()
    {
        return $this->date_inscription;
    }

    public function getEmailVerified()
    {
        return $this->email_verifie;
    }

    public function getPassword()
    {
        return $this->mot_de_passe;
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

}