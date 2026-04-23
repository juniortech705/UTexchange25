<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/AnnonceService.php';
require_once __DIR__ . '/../services/PhotoService.php';
require_once __DIR__ . '/../services/CategorieService.php';
class HomeController extends BaseController {
    //page d'accueil
    public function index() {
        $annonces = AnnonceService::getAll();

        $covers=[];
        foreach ($annonces as $annonce) {
            $covers [$annonce->getId()] = PhotoService::getCover($annonce->getId());
        }

        $annonces_by_cat = CategorieService::getParentsWithChildren();
        foreach ($annonces_by_cat as &$cat) {
            $cat['annonces'] = AnnonceService::getByCategorieId($cat['id']);
            foreach ($cat['annonces'] as $a) {
                $covers[$a->getId()] = PhotoService::getCover($a->getId());
            }
        }

        $this->render('home', ['covers' => $covers, 'annonces_by_cat' => $annonces_by_cat]);
    }

    //Dashboard
    public function dashboard() {
        $userId = Session::userId();
        $this->render('dashboard',['userId' => $userId]); //test
    }

}
