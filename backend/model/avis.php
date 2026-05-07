<?php

class Avis{
    protected $id;
    protected $conversation_id;
    protected $acheteur_id;
    protected $vendeur_id;
    protected $note;
    protected $commentaire;
    protected $created_at;
    protected $is_active;

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

    public function getIsActive()
    {
        return $this->is_active;
    }
}