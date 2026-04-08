<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/RoleService.php';
class UserController extends BaseController{
    //add
    public function addForm(){
        $roles=RoleService::getAll();
        $this->render('users/add', ['roles' => $roles]);
    }
    public function add(){
        $data = [
            'nom' => $this->input('nom'),
            'prenom' => $this->input('prenom'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'role_id' => $this->input('role_id'),
            'telephone' => $this->input('telephone'),
            'adresse' => $this->input('adresse'),
        ];

        $result = UserService::add($data);

        if ($result) {
            Session::flash('success', 'Utilisateur ajouté avec succès');
            $this->redirect('/users');
        } else {
            Session::flash('error', 'Erreur lors de l\'ajout');
            $this->redirect('/users/add');
        }
    }
    //update
    public function editForm($id){
        $user=UserService::getById($id);
        $roles=RoleService::getAll();
        $this->render('users/edit', ['user' => $user, 'roles' => $roles]);
    }
    public function edit(){
        $id = Session::userId();

        $data = [
            'nom' => $this->input('nom'),
            'prenom' => $this->input('prenom'),
            'email' => $this->input('email'),
            'role_id' => $this->input('role_id'),
            'telephone' => $this->input('telephone'),
            'adresse' => $this->input('adresse'),
        ];

        $result = UserService::update($id, $data);

        if ($result) {
            Session::flash('success', 'Utilisateur modifié');
            $this->redirect('/users');
        } else {
            Session::flash('error', 'Erreur modification');
            $this->redirect('/users/edit/' . $id);
        }
    }
    //delete
    public function delete($id){
        if (UserService::delete($id)) {
            Session::flash('success', 'Utilisateur supprimé');
        } else {
            Session::flash('error', 'Erreur suppression');
        }

        $this->redirect('/users');
    }
    //activate
    public function activate($id){
        UserService::activate($id);
        Session::flash('success', 'Utilisateur activé');
        $this->redirect('/users');
    }
    //deactivate
    public function deactivate($id){
        UserService::deactivate($id);
        Session::flash('warning', 'Utilisateur désactivé');
        $this->redirect('/users');
    }
    //all
    public function index(){
        $users=UserService::getAll();
        $this->render('users/index', ['users' => $users]);

    }
    //show (pour profil)
    public function show($id){
        $user=UserService::getById($id);
        $this->render('users/show', ['user' => $user]);

    }
    //update password
    public function passForm(){
        $this->render('users/passForm');
    }
    public function pass(){
        $id = Session::userId(); // user connecté

        $old = $this->input('old_password');
        $new = $this->input('new_password');

        $result = UserService::updatePassword($id, $old, $new);

        if ($result === true) {
            Session::flash('success', 'Mot de passe modifié');
            $this->redirect('/users/profil');
        } else {
            Session::flash('error', $result); // message retourné
            $this->redirect('/users/pass');
        }
    }

}