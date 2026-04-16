<?php

class Conversation{
    private $id;
    private $annonce_id;
    private $utilisateur1_id;
    private $utilisateur2_id;
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

    public function getUtilisateur1Id()
    {
        return $this->utilisateur1_id;
    }

    public function getUtilisateur2Id()
    {
        return $this->utilisateur2_id;
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
