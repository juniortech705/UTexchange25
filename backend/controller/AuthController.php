<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/RoleService.php';
class AuthController extends BaseController{
    //Login
    public function loginForm()
    {
        $this->render('auth/login');
    }
    public function login(){
        $email = $this->input('email');
        $password = $this->input('password');
        if(!$email || !$password){
            Session::flash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('/login');
        }

        $user= UserService::login($email, $password);
        if(!$user){
            Session::flash('error', 'Identifiant ou mot de passe incorrect.');
            $this->redirect('/login');
        }

        $roleName= RoleService::getUserRoleById($user->getId());

        //Session
        Session::login([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role_name' => $roleName
        ]);
        Session::flash('success', 'Connexion réussie.');

        //Redirection
        $redirect= Session::get('_redirect_after_login', '/dashboard');
        Session::set('_redirect_after_login', null);
        $this->redirect($redirect);
    }

    //Register
    public function registerForm()
    {
        $this->render('auth/register');
    }
    public function register(){
        $data = [
            'nom' => $this->input('nom'),
            'prenom' => $this->input('prenom'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'role_id' => 1 // user par défaut
        ];
        if (!$data['nom'] || !$data['prenom'] || !$data['email'] || !$data['password']) {
            Session::flash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('/register');
        }

        $result= UserService::register($data);
        if(!$result){
            Session::flash('error', 'Erreur.');
            $this->redirect('/register');
        }


        Session::flash('success', 'Inscription réussie. Connectez-vous.');
        $this->redirect('/login');
    }

    //logout
    public function logout(){
        Session::logout();
        Session::flash('success', 'Déconnexion réussie.');
        $this->redirect('/');
    }

}
