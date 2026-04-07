<?php

class User{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $role_id;
    private $telephone;
    private $adresse;
    private $est_actif;
    private $date_ins;
    private $email_verified;

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
        return $this->adresse;
    }

    public function getEstActif()
    {
        return $this->est_actif;
    }

    public function getDateIns()
    {
        return $this->date_ins;
    }

    public function getEmailVerified()
    {
        return $this->email_verified;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

}