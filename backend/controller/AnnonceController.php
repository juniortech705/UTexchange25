<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/AnnonceService.php';
require_once __DIR__ . '/../services/PhotoService.php';
require_once __DIR__ . '/../services/FavoriService.php';

class AnnonceController extends BaseController {
    //all
    public function index() {
        $annonces = AnnonceService::getAll();
        $this->render('annonce/index', ['annonces' => $annonces]); //redirection sur home plus tard
    }
    //show
    public function show($id) {
        $annonce = AnnonceService::getById($id);
        if (!$annonce) {
            $this->redirect('/404');
        }

        //incrementation du nombre de vues
        AnnonceService::incrementView($id);

        $photos = PhotoService::getByAnnonce($id);
        $cover = PhotoService::getCover($id);
        $this->render('annonce/show', [
            'annonce' => $annonce,
            'photos' => $photos,
            'cover' => $cover]);
    }
    //add
    public function addForm() {
        $this->render('annonce/create');
    }
    public function add() {
        $data = [
            'user_id' => Session::userId(),
            'categorie_id'   => $this->input('categorie_id'),
            'title'          => $this->input('title'),
            'description'    => $this->input('description'),
            'price'          => $this->input('price', 0),
            'type'           => $this->input('type', 'vente'),
            'status'         => 'active',
            'location'       => $this->input('location'),
        ];
        $annonce_id= AnnonceService::add($data);
        if (!$annonce_id) {
            Session::flash('error', 'Erreur lors de la création de l\'annonce.');
            $this->redirect('/annonces/create');
        }

        //upload des photos
        if (!empty($_FILES['photos'])) {
            $upload = PhotoService::upload($_FILES['photos'], $annonce_id);

            if (!$upload['success']) {
                Session::flash('warning', implode('<br>', $upload['errors']));
            }
        }

        Session::flash('success', 'Annonce créée avec succès');
        $this->redirect('/annonces/' . $annonce_id);

    }
    //update
    public function editForm($id) {
        $annonce = AnnonceService::getById($id);
        if (!$annonce) {
            $this->redirect('/404');
        }
        //check si annonce est pour user
        $this->checkOwner($annonce);

        $photos = PhotoService::getByAnnonce($id);

        $this->render('annonce/edit', ['annonce' => $annonce, 'photos' => $photos]);
    }
    public function edit($id) {
        $annonce = AnnonceService::getById($id);
        $this->checkOwner($annonce);

        $data = [
            'categorie_id' => $this->input('categorie_id'),
            'title'        => $this->input('title'),
            'description'  => $this->input('description'),
            'price'        => $this->input('price', 0),
            'type'         => $this->input('type'),
            'status'       => $this->input('status', $annonce->getStatus()),
            'location'     => $this->input('location'),
        ];

        $result= AnnonceService::update($id, $data);
        if (!$result) {
            Session::flash('error', 'Erreur modification');
            $this->redirect('/annonces/edit/' . $id);
        }

        //upload des nouvelles photos
        if (!empty($_FILES['photos'])) {
            $upload = PhotoService::upload($_FILES['photos'], $id);
            if (!$upload['success']) {
                Session::flash('warning', implode('<br>', $upload['errors']));
            }
        }

        Session::flash('success', 'Annonce modifiée');
        $this->redirect('/annonces/' . $id);
    }
    //delete
    public function delete($id) {
        $annonce = AnnonceService::getById($id);
        $this->checkOwner($annonce);

        PhotoService::deleteByAnnonce($id);
        AnnonceService::delete($id);

        Session::flash('success', 'Annonce supprimée.');
        $this->redirect('/myAnnonces');
    }
    //ByUser
    public function myAnnonces() {
        $annonces= AnnonceService::getAllByUser(Session::userId());
        $this->render('annonce/myAnnonces', ['annonces' => $annonces]);
    }
    //deletePhoto (AJAX)
    public function deletePhoto($id) {
        $photo = PhotoService::getById($id);
        if (!$photo) {
            $this->json(['success' => false, 'message' => 'Photo introuvable.'], 404);
        }
        $annonce = AnnonceService::getById($photo->getAnnonceId());
        if (!$annonce || $annonce->getUtilisateurId() != Session::userId()) {
            $this->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }

        PhotoService::delete($id);
        $this->json(['success' => true, 'message' => 'Photo supprimée.']);
    }
    //photo de couverture (AJAX)
    public function setCover($id) {
        $photo = PhotoService::getById($id);
        if (!$photo) {
            $this->json(['success' => false, 'message' => 'Photo introuvable.'], 404);
        }
        $annonce = AnnonceService::getById($photo->getAnnonceId());
        if (!$annonce || $annonce->getUtilisateurId() != Session::userId()) {
            $this->json(['success' => false, 'message' => 'Non autorisé.'], 403);
        }
        PhotoService::setCover($id, $photo->getAnnonceId());
        $this->json(['success' => true, 'message' => 'Photo de couverture mise à jour.']);
    }
    //renvoie une photo
    public function servePhoto($annonceId, $fichier) {
        PhotoService::serve($annonceId, $fichier);
    }
    //updateType
    public function updateType($id){
        $annonce = AnnonceService::getById($id);
        $this->checkOwner($annonce);

        $type= $this->input('type');
        AnnonceService::updateType($id, $type);
        Session::flash('success', 'Le type de l\'annonce mis à jour.');
        $this->redirect('/myAnnonces');
    }
    //updateStatus
    public function updateStatus($id){
        $annonce = AnnonceService::getById($id);
        $this->checkOwner($annonce);

        $status= $this->input('status');
        AnnonceService::updateStatus($id, $status);
        Session::flash('success', 'Le statut de l\'annonce mis à jour.');
        $this->redirect('/myAnnonces');
    }

    //helper
    private function checkOwner($annonce){
        if (!$annonce || $annonce->getUtilisateurId() != Session::userId()) {
            $this->redirect('/403');
        }
    }

    //Gestion des favoris
    //toggle (AJAX)
    public function toggleFavori($id){
        if (!Session::isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Utilisateur non connecté'], 403);
        }
        $userId = Session::userId();

        $annonce = AnnonceService::getById($id);
        if (!$annonce) {
            $this->json(['success' => false, 'message' => 'Annonce introuvable'], 404);
        }

        $result = FavoriService::toggle($userId, $id);
        $this->json(['success' => true, 'action' => $result]); //added ou removed
    }
    //isFavori (AJAX)
    public function isFavori($id){
        if (!Session::isLoggedIn()) {
            $this->json(['favori' => false]);
        }
        $userId = Session::userId();

        $exists = FavoriService::exists($userId, $id);
        $this->json(['favori' => $exists]);
    }
    //allByUser
    public function favoris(){
        if (!Session::isLoggedIn()) {
            $this->redirect('/login');
        }
        $userId = Session::userId();

        $annonces = FavoriService::getByUser($userId);
        $this->render('annonce/favoris', ['annonces' => $annonces]);
    }
}