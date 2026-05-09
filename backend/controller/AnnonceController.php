<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/AnnonceService.php';
require_once __DIR__ . '/../services/CategorieService.php';
require_once __DIR__ . '/../services/PhotoService.php';
require_once __DIR__ . '/../services/FavoriService.php';
require_once __DIR__ . '/../services/UserService.php';

class AnnonceController extends BaseController {
    //show
    public function show($id) {
        $annonce = AnnonceService::getById($id);
        if (!$annonce) {
            $this->redirect('/404');
        }

        //incrementation du nombre de vues
        AnnonceService::incrementView($id);

        $seller = UserService::getById($annonce->getUtilisateurId());
        $photos = PhotoService::getByAnnonce($id);
        $cover = PhotoService::getCover($id);
        $this->render('annonce/show', [
            'annonce' => $annonce,
            'photos' => $photos,
            'cover' => $cover,
            'seller' => $seller,
        ]);
    }
    //add
    public function addForm() {
        $categories = CategorieService::getAll();
        $this->render('annonce/create', ['categories' => $categories]);
    }
    public function add() {
        $data = [
            'user_id' => Session::userId(),
            'categorie_id'   => $this->input('categorie_id'),
            'title'          => $this->input('title'),
            'description'    => $this->input('description'),
            'price'          => $this->input('price'),
            'type'           => $this->input('type'),
            'status'         => $this->input('status'),
            'location'       => $this->input('location'),
        ];
        $annonce_id= AnnonceService::add($data);
        if (!$annonce_id) {
            Session::flash('error', 'Erreur lors de la création de l\'annonce.');
            $this->redirect('/annonce/create');
        }

        //upload des photos
        if (!empty($_FILES['photos'])) {
            $upload = PhotoService::upload($_FILES['photos'], $annonce_id);

            if (!$upload['success']) {
                Session::flash('warning', implode('<br>', $upload['errors']));
            }
        }

        Session::flash('success', 'Annonce créée avec succès');
        $this->redirect('/annonce/' . $annonce_id);

    }
    //update
    public function editForm($id) {
        $annonce = AnnonceService::getById($id);
        $categories = CategorieService::getAll();
        if (!$annonce) {
            $this->redirect('/404');
        }
        //check si annonce est pour user
        $this->checkOwner($annonce);

        $photos = PhotoService::getByAnnonce($id);

        $this->render('annonce/edit', ['annonce' => $annonce, 'photos' => $photos, 'categories' => $categories]);
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
            'status'       => $this->input('status'),
            'location'     => $this->input('location'),
        ];

        $result= AnnonceService::update($id, $data);
        if (!$result) {
            Session::flash('error', 'Erreur modification');
            $this->redirect('/annonce/edit/' . $id);
        }

        //upload des nouvelles photos
        if (!empty($_FILES['photos']) && $_FILES['photos']['error'][0] !== UPLOAD_ERR_NO_FILE){
            $upload = PhotoService::upload($_FILES['photos'], $id);
            if (!$upload['success']) {
                Session::flash('warning', implode('<br>', $upload['errors']));
            }
        }

        Session::flash('success', 'Annonce modifiée');
        $this->redirect('/annonce/' . $id);
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

        $covers=[];
        foreach ($annonces as $annonce) {
            $covers [$annonce->getId()] = PhotoService::getCover($annonce->getId());
            $name= UserService::getById($annonce->getUtilisateurId())->getNom().' '.UserService::getById(Session::userId())->getPrenom();
        }
        $this->render('annonce/myannonces', ['annonces' => $annonces, 'covers' => $covers, 'name' => $name]);
    }
    //deletePhoto (Ajax)
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
    //photo de couverture (Ajax)
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
        $fic= urldecode($fichier);
        PhotoService::serve($annonceId, $fic);
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
    //toggle (Ajax)
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
    //isFavori (Ajax)
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
        $covers=[];
        foreach ($annonces as $annonce) {
            $covers [$annonce->getId()] = PhotoService::getCover($annonce->getId());
        }

        $this->render('annonce/favoris', ['annonces' => $annonces, 'covers' => $covers]);
    }

    //index
    public function index(){
        $filters = [
            'cat_id'    => $this->input('cat_id') ?? null,
            'search'    => $this->input('search') ?? null,
            'min_price' => $this->input('min_price') ?? null,
            'max_price' => $this->input('max_price')?? null,
            'type' => $this->input('type')?? null,
        ];

        $annonces = AnnonceService::filter($filters);

        $covers=[];
        foreach ($annonces as $annonce) {
            $covers [$annonce->getId()] = PhotoService::getCover($annonce->getId());
        }

        $currentCat = null;
        if (!empty($filters['cat_id'])) {
            $categories = $GLOBALS['nav_categories'] ?? [];
            foreach ($categories as $parent) {
                // C'est un parent
                if ((int) $parent['id'] === (int) $filters['cat_id']) {
                    $currentCat = $parent;
                    break;
                }
                // C'est un enfant
                foreach ($parent['enfants'] ?? [] as $enfant) {
                    if ((int) $enfant['id'] === (int) $filters['cat_id']) {
                        $currentCat = array_merge($enfant, ['parent' => [
                            'id'  => $parent['id'],
                            'nom' => $parent['nom'],
                        ]]);
                        break 2;
                    }
                }
            }
        }

        $this->render('annonce/index', [
            'annonces'   => $annonces,
            'covers'     => $covers,
            'filters'    => $filters,
            'currentCat' => $currentCat,
        ]);
    }
}