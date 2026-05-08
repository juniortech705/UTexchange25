<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/StatistiqueService.php';
require_once __DIR__ . '/../services/AvisService.php';
require_once __DIR__ . '/../services/AnnonceService.php';
require_once __DIR__ . '/../services/PhotoService.php';
require_once __DIR__ . '/../services/UserService.php';

class AdminController extends BaseController{
    //index pour annonce
    public function allAnnonces(){
        $annonces=AnnonceService::getByAdmin();
        $covers=[];
        foreach ($annonces as $annonce) {
            $covers [$annonce->getId()] = PhotoService::getCover($annonce->getId());
        }
        $sellers=[];
        foreach ($annonces as $annonce) {
            $sellers [$annonce->getUtilisateurId()] = UserService::getById($annonce->getUtilisateurId());
        }

        $this->render('admin/annonceAdmin', ['annonces' => $annonces, 'covers' => $covers, 'sellers' => $sellers]);
    }
    //index pour avis
    public function allAvis(){
        $avis=AvisService::getAll();

        $this->render('admin/avisAdmin', ['avis' => $avis]);
    }
    //statistiques
    public function stats(){
        $annonceStats=StatistiqueService::getAnnonceStats();
        $lastestAnnonces=StatistiqueService::getLatestAnnonces();
        $avisStats=StatistiqueService::getAvisStats();
        $topUsers=StatistiqueService::getTopVendeurs();
        $topCategories=StatistiqueService::getTopCategories();
        $nbUser= StatistiqueService::getNbUsers();
        $nbConv= StatistiqueService::getNbConversations();
        $nbAvis= StatistiqueService::getNbAvis();
        $nbAnnonces= StatistiqueService::getNbAnnonces();

        $covers=[];
        foreach ($lastestAnnonces as $annonce) {
            $covers [$annonce->getId()] = PhotoService::getCover($annonce->getId());
        }


        $this->render('admin/stats', [
            'annonceStats' => $annonceStats,
            'lastestAnnonces' => $lastestAnnonces,
            'avisStats' => $avisStats,
            'topUsers' => $topUsers,
            'topCategories' => $topCategories,
            'nbUsers' => $nbUser,
            'nbConversations' => $nbConv,
            'nbAvis' => $nbAvis,
            'nbAnnonces' => $nbAnnonces,
            'covers' => $covers,
        ]);
    }

    //deactivate avis
    public function deactivate($id){
        if (Session::userRole() !== 'Administrateur') {
            Session::flash('error', 'Accès refusé');

            $this->redirect('/adAvis');
        }

        $result=AvisService::deactivate($id);
        if ($result) {
            Session::flash('success', 'Avis désactivé avec succès');
        } else {
            Session::flash('error', 'Erreur lors de la désactivation de l\'avis');
        }
        $this->redirect('/adAvis');
    }
    //activate
    public function activate($id){
        if (Session::userRole() !== 'Administrateur') {
            Session::flash('error', 'Accès refusé');

            $this->redirect('/adAvis');
        }

        $result=AvisService::activate($id);
        if ($result) {
            Session::flash('success', 'Avis activé avec succès');
        } else {
            Session::flash('error', 'Erreur lors de la activation de l\'avis');
        }
        $this->redirect('/adAvis');
    }
    //signaler annonce
    public function report($id){
        if (Session::userRole() !== 'Administrateur') {
            Session::flash('error', 'Accès refusé');

            $this->redirect('/adAnnonces');
        }

        $annonce=AnnonceService::getById($id);
        if($annonce->getStatus() == 'vendu'){
            Session::flash('warning', 'Impossible de signaler une annonce déjà vendue');
            $this->redirect('/adAnnonces');
        }elseif ($annonce->getStatus() == 'signalé') {
            Session::flash('warning', 'Annonce déjà signalée');
            $this->redirect('/adAnnonces');
        }elseif ($annonce->getStatus() == 'draft') {
            Session::flash('warning', 'Annonce non publiée, impossible de signaler');
            $this->redirect('/adAnnonces');
        }

        $result=AnnonceService::reportAnnonce($id);
        if ($result) {
            Session::flash('success', 'Annonce désactivé avec succès');
        } else {
            Session::flash('error', 'Erreur lors de la désactivation de l\'annonce');
        }
        $this->redirect('/adAnnonces');
    }

}