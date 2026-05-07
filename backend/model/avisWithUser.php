<?php
require_once __DIR__ . '/../model/avis.php';
class AvisWithUser extends Avis
{
    protected $nom;
    protected $prenom;
    protected $annonce_title;

    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getAnnonceTitle() { return $this->annonce_title; }
}