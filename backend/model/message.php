<?php

class Message{
    private $id;
    private $conversation_id;
    private $expediteur_id;
    private $contenu;
    private $is_read;
    private $created_at;

    public function getId()
    {
        return $this->id;
    }

    public function getConversationId()
    {
        return $this->conversation_id;
    }

    public function getExpediteurId()
    {
        return $this->expediteur_id;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function getIsRead()
    {
        return $this->is_read;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
}