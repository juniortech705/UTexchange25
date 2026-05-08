<?php
require_once __DIR__ . '/../model/avis.php';
class AvisAdmin extends Avis
{
    protected $acheteur_nom;
    protected $acheteur_prenom;

    protected $vendeur_nom;
    protected $vendeur_prenom;

    protected $annonce_title;

    public function getAcheteurNom()
    {
        return $this->acheteur_nom;
    }

    public function getAcheteurPrenom()
    {
        return $this->acheteur_prenom;
    }

    public function getVendeurNom()
    {
        return $this->vendeur_nom;
    }

    public function getVendeurPrenom()
    {
        return $this->vendeur_prenom;
    }

    public function getAnnonceTitle()
    {
        return $this->annonce_title;
    }
}