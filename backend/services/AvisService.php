<?php
require_once __DIR__ . '/../model/avis.php';
require_once __DIR__ . '/../model/avisWithUser.php';
require_once __DIR__ . '/../../PgSQL/database.php';
require_once __DIR__ . '/ConversationService.php';

class AvisService{
    //add
    public static function add(int $conversationId, int $acheteurId, int $vendeurId, int $note, string $commentaire = ''){
        if ($note < 1 || $note > 5) {
            return false;
        }

        // Vérifie que la conversation est bien terminée avant d'appeler la base
        $conv = ConversationService::getById($conversationId);
        if (!$conv || $conv->getStatus() !== 'terminee') {
            return false;
        }

        // Vérifie que l'acheteur n'a pas déjà laissé un avis
        if ($conv->getAvisLaisse()) {
            return false;
        }

        $rq = "INSERT INTO avis (conversation_id, acheteur_id, vendeur_id, note, commentaire, created_at)
               VALUES (:conversation_id, :acheteur_id, :vendeur_id, :note, :commentaire, NOW())";

        $tab=[
            'conversation_id' => $conversationId,
            'acheteur_id'     => $acheteurId,
            'vendeur_id'      => $vendeurId,
            'note'            => $note,
            'commentaire'     => trim($commentaire),
        ];

        $result= Database::execute($rq, $tab);
        return $result;
    }
    //getByVendeur (les avis qu'on a laissé au vendeur)
    public static function getByVendeur($vendeurId){
        $rq = "
        SELECT 
            a.*,
            u.nom,
            u.prenom,
            an.title AS annonce_title
        FROM avis a
        JOIN utilisateurs u ON u.id = a.acheteur_id
        JOIN conversations c ON c.id = a.conversation_id
        JOIN annonces an ON an.id = c.annonce_id
        WHERE a.vendeur_id = :vendeur_id AND a.is_active = TRUE
        ORDER BY a.created_at DESC
    ";

        return Database::query($rq, 'AvisWithUser', ['vendeur_id' => $vendeurId]);
    }
    //remove
    public static function delete($id){
        $rq = "DELETE FROM avis WHERE id = :id";

        return Database::execute($rq, ['id' => $id]);
    }
    //stats
    public static function getStats($vendeurId){
        $rq = "SELECT COUNT(*) as total, ROUND(AVG(note), 1) as moyenne
               FROM avis WHERE vendeur_id = :id";

        $stm = Database::find($rq, 'stdClass', ['id' => $vendeurId]);

        return [
            'total'   => $stm ? (int) $stm->total : 0,
            'moyenne' => $stm ? (float) $stm->moyenne : 0,
        ];
    }
    //getByConversation
    public static function getByConversationId($conversationId){
        $rq = "SELECT * FROM avis WHERE conversation_id = :id AND is_active=TRUE ORDER BY created_at DESC";
        $tab['id'] = $conversationId;
        return Database::find($rq, 'Avis', $tab);

    }
    //getAll (pour admin)
    public static function getAll(){
        $rq = "SELECT * FROM avis ORDER BY created_at DESC";

        return Database::query($rq, 'Avis', []);
    }

    //deactivate
    public static function deactivate($id){
        $rq = "UPDATE avis SET is_active = FALSE WHERE id = :id";
        return Database::execute($rq, ['id' => $id]);
    }

}