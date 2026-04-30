<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/ConversationService.php';
require_once __DIR__ . '/../services/MessageService.php';
require_once __DIR__ . '/../services/AvisService.php';

class ConversationController extends BaseController{
    /**
     * Concernant les conversations
     */
    //getAll
    public function index(){
        $userId = Session::userId();
        $conversations = ConversationService::getByUser($userId);

        $this->render('conversations/index', ['conversations' => $conversations]);
    }
    //start
    public function start(){
        $userId    = Session::userId();
        $annonceId = $this->input('annonce_id');

        $annonce = AnnonceService::getById($annonceId);
        if (!$annonce) {
           $this->redirect('/500');
        }

        if ($annonce->getUtilisateurId() == $userId) {
            Session::flash('error', 'Vous ne pouvez pas contacter votre propre annonce.');
            $this->redirect('/annonce/' . $annonceId);
        }

        $conversation = ConversationService::getOrCreate($annonceId, $userId, $annonce->getUtilisateurId());

        if (!$conversation) {
            Session::flash('error', 'Impossible d\'ouvrir la conversation.');
            $this->redirect('/annonce/' . $annonceId);
        }

        $this->redirect('/conversations/' . $conversation->getId());
    }
    //show
    public function show($id){
        $userId = Session::userId();
        $conversation = ConversationService::getById($id);

        if (!$conversation || !ConversationService::isParticipant($conversation, $userId)) {
            $this->redirect('/403');
        }

        // Chargement initial des messages
        $messages = MessageService::getByConversationId($id);

        // Marque les messages reçus comme lus à l'ouverture
        MessageService::markAsRead($id, $userId);

        $otherId = ConversationService::getOtherUserId($conversation, $userId);

        $this->render('conversations/show', [
            'conversation' => $conversation,
            'messages'     => $messages,
            'userId'       => $userId,
            'otherId'      => $otherId,
            // id du dernier message pour démarrer le polling JS du front
            'lastMessageId' => !empty($messages) ? end($messages)->getId() : 0,
        ]);

    }
    //terminer
    public function terminate($id){
        $userId = Session::get('user_id');
        $conversation = ConversationService::getById($id);

        if (!$conversation || !ConversationService::isParticipant($conversation, $userId)) {
            $this->redirect('/403');
        }

        ConversationService::terminer($id);
        Session::flash('success', 'Conversation terminée. Vous pouvez maintenant laisser un avis.');
        $this->redirect('/conversations/' . $id);
    }
    //delete
    public function delete($id){
        //implementer plus tard
    }

    /**
     * Concernant les messages (AJAX)
     */
    //send
    public function send($id){
        $userId = Session::userId();
        $conversation = ConversationService::getById($id);

        if (!$conversation || !ConversationService::isParticipant($conversation, $userId)) {
            $this->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }

        if ($conversation->getStatus() === 'terminee') {
            $this->json(['success' => false, 'message' => 'Cette conversation est terminée.'], 400);
        }

        $contenu = trim($this->input('contenu', ''));
        if (empty($contenu)) {
            $this->json(['success' => false, 'message' => 'Le message ne peut pas être vide.'], 400);
        }

        $message = MessageService::send($id, $userId, $contenu);
        if (!$message) {
            $this->json(['success' => false, 'message' => 'Erreur lors de l\'envoi.'], 500);
        }

        $this->json([
            'success' => true,
            'message' => MessageService::toArray($message),
        ]);
    }
    //delete
    public function deleteMessage($id){
        $userId  = Session::userId();
        $message = MessageService::getById($id);

        if (!$message) {
            $this->json(['success' => false, 'message' => 'Message introuvable.'], 404);
        }

        // Seul l'expéditeur peut supprimer son message
        if ($message->getExpediteurId() != $userId) {
            $this->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }

        MessageService::delete($id);
        $this->json(['success' => true]);
    }
    //getMessage
    public function getMessages($id){
        $userId       = Session::userId();
        $conversation = ConversationService::getById($id);

        if (!$conversation || !ConversationService::isParticipant($conversation, $userId)) {
            $this->json(['success' => false], 403);
        }

        $lastId   = (int) $this->input('last_id', 0);
        $messages = MessageService::getNewMessages($id, $lastId);

        // Marque automatiquement comme lus les messages reçus
        if (!empty($messages)) {
            MessageService::markAsRead($id, $userId);
        }

        $this->json([
            'success'  => true,
            'messages' => MessageService::toArrayList($messages),
        ]);
    }
    //markRead*
    public function markAsRead($id){
        $userId       = Session::get('user_id');
        $conversation = ConversationService::getById($id);

        if (!$conversation || !ConversationService::isParticipant($conversation, $userId)) {
            $this->json(['success' => false], 403);
        }

        MessageService::markAsRead($id, $userId);
        $this->json(['success' => true]);
    }
    //countUnread
    public function countUnreadMessages(){
        $count = ConversationService::countUnreadForUser(Session::userId());
        $this->json(['count' => $count]);
    }
    //updateMessage
    public function update($id){
        $userId  = Session::userId();
        $message = MessageService::getById($id);

        if (!$message) {
            $this->json(['success' => false, 'message' => 'Message introuvable.'], 404);
        }

        if ($message->getExpediteurId() != $userId) {
            $this->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }

        $contenu = trim($this->input('contenu', ''));
        if (empty($contenu)) {
            $this->json(['success' => false, 'message' => 'Le contenu ne peut pas être vide.'], 400);
        }

        $result = MessageService::update($id, $contenu);
        if (!$result) {
            $this->json(['success' => false, 'message' => 'Erreur lors de la modification.'], 500);
        }

        $this->json([
            'success' => true,
            'message' => MessageService::toArray(MessageService::getById($id)),
        ]);
    }


    /**
     * Concernant les avis
     */
    //add
    public function addAvis($id){
        $userId       = Session::get('user_id');
        $conversation = ConversationService::getById($id);

        if (!$conversation || !ConversationService::isParticipant($conversation, $userId)) {
            $this->redirect('/403');
        }

        if ($conversation->getStatus() !== 'terminee') {
            Session::flash('error', 'Vous ne pouvez laisser un avis que sur une conversation terminée.');
            $this->redirect('/conversations/' . $id);
        }

        if ($conversation->getAvisLaisse()) {
            Session::flash('error', 'Un avis a déjà été laissé pour cette transaction.');
            $this->redirect('/conversations/' . $id);
        }

        $vendeurId = ConversationService::getOtherUserId($conversation, $userId);
        $note      = (int) $this->input('note');
        $commentaire = $this->input('commentaire', '');

        $success = AvisService::add($id, $userId, $vendeurId, $note, $commentaire);

        if (!$success) {
            Session::flash('error', 'Erreur lors de la soumission de l\'avis.');
        } else {
            Session::flash('success', 'Votre avis a été enregistré.');
        }

        $this->redirect('/conversations/' . $id);
    }
    //delete
    public function deleteAvis($id){
        $userId = Session::userId();
        $conversation = ConversationService::getById($id);

        if (!$conversation || !ConversationService::isParticipant($conversation, $userId)) {
            $this->redirect('/403');
        }

        // Seul l'acheteur (celui qui a laissé l'avis) ou admin peut le supprimer
        if ($conversation->getUtilisateur1Id() != $userId || Session::userRole() != "Administrateur") {
            $this->redirect('/403');
        }

        AvisService::delete($id);

        // Remet avis_laisse à false sur la conversation
        ConversationService::resetAvis($id);

        Session::flash('success', 'Avis supprimé.');
        $this->redirect('/conversations/' . $id);
    }

}