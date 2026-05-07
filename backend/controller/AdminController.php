<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/StatistiqueService.php';
require_once __DIR__ . '/../services/AvisService.php';
require_once __DIR__ . '/../services/AnnonceService.php';

class AdminController extends BaseController{
    //index pour annonce
    public function allAnnonces(){
        $annonces=AnnonceService::getByAdmin();

        $this->render('admin/annoncesAdmin', ['annonces' => $annonces]);
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

        $this->render('admin/stats', [
            'annonceStats' => $annonceStats,
            'lastestAnnonces' => $lastestAnnonces,
            'avisStats' => $avisStats,
            'topUsers' => $topUsers,
            'topCategories' => $topCategories
        ]);
    }

    //deactivate avis
    public function deactivate($id){
        if (Session::userRole() !== 'administrateur') {
            Session::flash('error', 'Accès refusé');

            $this->redirect('/adAvis');
        }

        $result=AvisService::deactivate($id);
        if ($result) {
            Session::flash('success', 'Avis désactivé avec succès');
        } else {
            Session::flash('error', 'Erreur lors de la désactivation de l\'avis');
        }
    }
    //signaler annonce
    public function report($id){
        if (Session::userRole() !== 'administrateur') {
            Session::flash('error', 'Accès refusé');

            $this->redirect('/adAnnonces');
        }

        $result=AnnonceService::reportAnnonce($id);
        if ($result) {
            Session::flash('success', 'Annonce désactivé avec succès');
        } else {
            Session::flash('error', 'Erreur lors de la désactivation de l\'annonce');
        }
    }

}