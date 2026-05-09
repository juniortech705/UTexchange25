<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/RoleService.php';
class AuthController extends BaseController{
    //Login
    public function login(){
        $email = $this->input('email');
        $password = $this->input('password');
        if(!$email || !$password){
            Session::flash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('/login');
        }

        if (!self::isAllowedEmailDomain($email)) {
            Session::flash('error', 'Seuls les emails universitaires UT sont autorisés.');
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
        $redirect= Session::get('_redirect_after_login', '/');
        Session::set('_redirect_after_login', null);
        $this->redirect($redirect);
    }

    //Register
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
            $this->redirect('/');
        }

        if (!self::isAllowedEmailDomain($data['email'])) {
            Session::flash('error', 'Seuls les emails universitaires UT sont autorisés.');
            $this->redirect('/');
        }

        $result= UserService::register($data);
        if(!$result){
            Session::flash('error', 'Erreur.');
            $this->redirect('/');
        }


        Session::flash('success', 'Inscription réussie. Connectez-vous.');
        $this->redirect('/');
    }

    //logout
    public function logout(){
        Session::logout();
        Session::flash('success', 'Déconnexion réussie.');
        $this->redirect('/');
    }

    public function form(){
        $this->render('users/login');
    }

    //helpers pour le controle du nom de domaine de email juste pour les UTT
    private function isAllowedEmailDomain($email){
        $allowedDomains = [
            'utbm.fr',
            'utt.fr',
            'utc.fr',
            'uttop.fr',
            'utexchange.fr'
        ];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $domain = substr(strrchr($email, "@"), 1);
        return in_array(strtolower($domain), $allowedDomains);
    }

}
