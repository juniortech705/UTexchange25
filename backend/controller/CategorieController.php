<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/CategorieService.php';

class CategorieController extends BaseController{
    //index
    public function index(){
        $categories = CategorieService::getAll();

        //pour avoir le nom de la catégorie
        $parentsMap = [];
        foreach ($categories as $c) {
            $parentsMap[$c->getId()] = [
                'nom' => $c->getNom(),
                'parent_id' => $c->getParentId()
            ];
        }
        $this->render('categories/index', ['categories' => $categories, 'parentsMap' => $parentsMap]);
    }
    //add
    public function addForm(){
        $parents = CategorieService::getAllParents();
        $this->render('categories/add', ['parents' => $parents]);
    }
    public function add(){
        $data["nom"] = $this->input('nom');
        $data["parent_id"] = $this->input('parent_id');

        $result = CategorieService::add($data);
        if($result){
            Session::flash('success', 'Catégorie ajoutée avec succès');
            $this->redirect('/categories');
        }
        else{
            Session::flash('error', 'Erreur lors de l\'ajout');
            $this->redirect('/categories/add');
        }
    }
    //edit
    public function editForm($id){
        $cat= CategorieService::getById($id);
        $parents = CategorieService::getAllParents();
        $this->render('categories/edit', ['categorie'=>$cat, 'parents' => $parents]);
    }
    public function edit($id){
        $data["nom"] = $this->input('nom');
        $data["parent_id"] = $this->input('parent_id') ?: null;

        $result = CategorieService::edit($id, $data);
        if($result){
            Session::flash('success', 'Catégorie modifiée');
            $this->redirect('/categories');
        }
        else{
            Session::flash('error', 'Erreur modification');
            $this->redirect('/categories/edit/' . $id);
        }
    }
    //delete
    public function delete($id){
        $cat= CategorieService::getById($id);
        if(! $cat){
            return false;
        }

        $result = CategorieService::delete($id);
        if($result){
            Session::flash('success', 'Catégorie supprimée');
        }
        else{
            Session::flash('error', 'Erreur suppression');
        }
        $this->redirect('/categories');
    }
    //activate
    public function activate($id){
        $cat= CategorieService::getById($id);
        if(! $cat){
            return false;
        }

        $result = CategorieService::activate($id);
        if($result){
            Session::flash('success', 'Catégorie activée');
        }
        else{
            Session::flash('error', 'Erreur lors de l\'activation');
        }
        $this->redirect('/categories');
    }
    //deactivate
    public function deactivate($id){
        $cat= CategorieService::getById($id);
        if(! $cat){
            return false;
        }

        $result = CategorieService::deactivate($id);
        if($result){
            Session::flash('success', 'Catégorie désactivée');
        }
        else{
            Session::flash('error', 'Erreur lors de la désactivation');
        }
        $this->redirect('/categories');
    }
}