<?php
require_once __DIR__ . '/../model/message.php';
require_once __DIR__ . '/../../PgSQL/database.php';

class MessageService{
    //send
    public static function send($conversationId, $expediteurId, $contenu){
        $contenu = trim($contenu);
        if (empty($contenu)) {
            return false;
        }

        $rq = "INSERT INTO messages (conversation_id, expediteur_id, contenu, created_at)
               VALUES (:conversation_id, :expediteur_id, :contenu, NOW())
               RETURNING id";

        $tab =[
            'conversation_id' => $conversationId,
            'expediteur_id'   => $expediteurId,
            'contenu'         => $contenu,
        ];

        $id = Database::insertAndGetId($rq, $tab);

        return $id ? self::getById((int) $id) : false;
    }
    //getById
    public static function getById($id){
        $rq="SELECT * FROM messages WHERE id = :id";
        $tab['id'] = $id;

        return Database::find($rq, 'Message', $tab) ?: null;
    }
    //getByConversation
    public static function getByConversationId($id){
        $rq = "SELECT * FROM messages WHERE conversation_id = :id ORDER BY created_at ASC";

        return Database::query($rq, 'Message', ['id' => $id]);
    }
    //getNewMessage (Pour Ajax, ainsi on pourra éviter de recharger toute la vue)
    public static function getNewMessages($conversationId, $lastMessageId){
        $rq = "SELECT * FROM messages WHERE conversation_id = :conv_id AND id > :last_id ORDER BY created_at ASC";

        $tab = [
            'conv_id' => $conversationId,
            'last_id' => $lastMessageId,
        ];

        return Database::query($rq, 'Message', $tab);
    }
    //markAsRead
    public static function markAsRead($conversationId, $userId){
        $rq = "UPDATE messages
               SET is_read = true
               WHERE conversation_id = :conv_id
                 AND expediteur_id != :user_id
                 AND is_read = false";

        return Database::execute($rq, ['conv_id' => $conversationId, 'user_id' => $userId]);
    }
    //delete
    public static function delete($id){
        $rq = "DELETE FROM messages WHERE id = :id";
        $tab['id'] = $id;

        return Database::execute($rq, $tab);

    }
    //update
    public static function update($id, $contenu){
        $rq = "UPDATE messages SET contenu = :contenu WHERE id = :id";

        return Database::execute($rq, ['id' => $id, 'contenu' => trim($contenu)]);
    }
    //helpers (pour la conversion de l'objet Message en json que consommera Ajax)
    public static function toArray(object $message): array
    {
        return [
            'id'              => $message->getId(),
            'conversation_id' => $message->getConversationId(),
            'expediteur_id'   => $message->getExpediteurId(),
            'contenu'         => htmlspecialchars($message->getContenu()),
            'is_read'         => $message->getIsRead(),
            'created_at'      => $message->getCreatedAt(),
        ];
    }

    public static function toArrayList(array $messages): array
    {
        return array_map([self::class, 'toArray'], $messages);
    }
}
