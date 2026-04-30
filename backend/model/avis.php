<?php

class Avis{
    private $id;
    private $conversation_id;
    private $acheteur_id;
    private $vendeur_id;
    private $note;
    private $commentaire;
    private $created_at;

    public function getId()
    {
        return $this->id;
    }

    public function getConversationId()
    {
        return $this->conversation_id;
    }

    public function getAcheteurId()
    {
        return $this->acheteur_id;
    }

    public function getVendeurId()
    {
        return $this->vendeur_id;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
}