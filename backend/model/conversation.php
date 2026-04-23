<?php

class Conversation{
    private $id;
    private $annonce_id;
    private $acheteur_id;
    private $vendeur_id;
    private $status;
    private $avis_laisse;
    private $dernier_message;
    private $last_message_at;
    private $created_at;

    public function getId()
    {
        return $this->id;
    }

    public function getAnnonceId()
    {
        return $this->annonce_id;
    }

    public function getAcheteurId()
    {
        return $this->acheteur_id;
    }

    public function getVendeurId()
    {
        return $this->vendeur_id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getAvisLaisse()
    {
        return $this->avis_laisse;
    }

    public function getDernierMessage()
    {
        return $this->dernier_message;
    }

    public function getLastMessageAt()
    {
        return $this->last_message_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
