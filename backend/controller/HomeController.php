<?php
require_once __DIR__ . '/BaseController.php';
class HomeController extends BaseController {
    //page d'accueil
    public function index() {
        $this->render('home');
    }

    //Dashboard
    public function dashboard() {
        $userId = Session::userId();
        $this->render('dashboard',['userId' => $userId]); //test
    }

}
