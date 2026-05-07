<?php
require_once __DIR__ . '/../model/conversation.php';
require_once __DIR__ . '/../../PgSQL/database.php';

class ConversationService{
    //get-start
    public static function getOrCreate($annonceId, $buyerId, $sellerId){
        // Vérifie si une conversation existe déjà
        $existing = self::getByAnnonceAndBuyer($annonceId, $buyerId);
        if ($existing) {
            return $existing->getId();
        }

        $rq = "INSERT INTO conversations
                (annonce_id, acheteur_id, vendeur_id, status, created_at, last_message_at)
               VALUES
                (:annonce_id, :utilisateur1_id, :utilisateur2_id, 'active', NOW(), NOW())
               RETURNING id";

        $tab = [
            'annonce_id'      => $annonceId,
            'utilisateur1_id' => $buyerId,
            'utilisateur2_id' => $sellerId,
        ];

        $id = Database::insertAndGetId($rq, $tab);

        return $id;
    }
    //getById
    public static function getById($id){
        $rq= "SELECT * FROM conversations WHERE id = :id";

        return Database::find($rq, 'Conversation', ['id' => $id]) ?: null;
    }
    //getByUser
    public static function getByUser($userId){
        $rq = "SELECT * FROM conversations
               WHERE acheteur_id = :id OR vendeur_id = :id2
               ORDER BY last_message_at DESC";

        return Database::query($rq, 'Conversation', ['id' => $userId, 'id2' => $userId]);
    }
    //terminer
    public static function terminer($id){
        $rq= "UPDATE conversations SET status = 'terminee' WHERE id = :id";

        return Database::execute($rq, ['id' => $id]);
    }

    //countNbUnreadMessage
    public static function countUnreadForUser($userId){
        $rq = "SELECT COUNT(*) FROM messages m
               JOIN conversations c ON c.id = m.conversation_id
               WHERE (c.acheteur_id = :id OR c.vendeur_id = :id2)
                 AND m.expediteur_id != :id3
                 AND m.is_read = false";

        return Database::count($rq, ['id' => $userId, 'id2' => $userId, 'id3' => $userId]);
    }
    //getByAnnonceAndBuyer
    public static function getByAnnonceAndBuyer($annonceId, $buyerId){
        $rq="SELECT * FROM conversations WHERE annonce_id = :annonce_id AND acheteur_id = :buyer_id";
        $tab['annonce_id'] = $annonceId;
        $tab['buyer_id'] = $buyerId;
        return Database::find($rq, 'Conversation', $tab) ?: null;
    }

    //helpers
    //isParticipant
    public static function isParticipant($conversation, $userId){
        return $conversation->getAcheteurId() == $userId
            || $conversation->getVendeurId() == $userId;
    }
    //getOtherUser
    public static function getOtherUserId($conversation, $userId){
        return $conversation->getAcheteurId() == $userId
            ? $conversation->getVendeurId()
            : $conversation->getAcheteurId();
    }
    //resetAvis
    public static function resetAvis($conversationId){
        $rq="UPDATE conversations SET avis_laisse = false WHERE id = :id";

        return Database::execute($rq, ['id' => $conversationId]);
    }

}