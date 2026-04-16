<?php
require_once __DIR__ . '/../model/annonce.php';
require_once __DIR__ . '/../../PgSQL/database.php';

class AnnonceService{
    private static array $types = ['vente', 'don', 'location'];
    private static array $statuses = ['draft', 'active', 'vendu', 'expire', 'archive'];

    //add
    public static function add($data){
        $rq= "INSERT INTO annonces 
        (user_id, categorie_id, title, description, price, type, status, location, created_at)
        VALUES (:user_id, :categorie_id, :title, :description, :price, :type, :status, :location, NOW()) RETURNING id";

        if (!in_array($data['type'], self::$types)) {
            return false;
        }

        if (isset($data['status']) && !in_array($data['status'], self::$statuses)) {
            return false;
        }

        $tab = [
            'user_id' => $data['user_id'],
            'categorie_id' => $data['categorie_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'type' => $data['type'],
            'status' => $data['status'] ?? 'active',
            'location' => $data['location']
        ];
        return Database::insertAndGetId($rq, $tab);
    }
    //update
    public static function update($id, $data){
        $user= self::getById($id);
        if (!$user) {
            return false;
        }

        $rq = "UPDATE annonces SET 
            categorie_id = :categorie_id,
            title = :title,
            description = :description,
            price = :price,
            type = :type,
            status = :status,
            location = :location
            WHERE id = :id";

        if (!in_array($data['type'], self::$types)) {
            return false;
        }

        if (isset($data['status']) && !in_array($data['status'], self::$statuses)) {
            return false;
        }

        $tab=[
            'id' => $id,
            'categorie_id' => $data['categorie_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'type' => $data['type'],
            'status' => $data['status'],
            'location' => $data['location']
        ];
        return Database::execute($rq, $tab);
    }
    //delete
    public static function delete($id){
        $rq= "DELETE FROM annonces WHERE id = :id";
        $tab["id"] = $id;
        return Database::execute($rq, $tab);
    }
    //all
    public static function getAll(){
        $rq= "SELECT * FROM annonces";
        return Database::query($rq, "Annonce");
    }
    //getByID
    public static function getById($id){
        $rq= "SELECT * FROM annonces WHERE id = :id";
        $tab["id"] = $id;
        return Database::find($rq, "Annonce", $tab);
    }
    //getAllByUser
    public static function getAllByUser($userId){
        $rq= "SELECT * FROM annonces WHERE user_id = :user_id";
        $tab["user_id"] = $userId;
        return Database::query($rq, "Annonce", $tab);
    }
    //updateStatuts
    public static function updateStatus($id, $status){
        $rq= "UPDATE annonces SET status = :status WHERE id = :id";

        if (!in_array($status, self::$statuses)) {
            return false;
        }

        $tab["id"] = $id;
        $tab["status"] = $status;
        return Database::execute($rq, $tab);
    }
    //updateType
    public static function updateType($id, $type){
        $rq= "UPDATE annonces SET type = :type WHERE id = :id";
        if (!in_array($type, self::$types)) {
            return false;
        }

        $tab["id"] = $id;
        $tab["type"] = $type;
        return Database::execute($rq, $tab);
    }
    //views (nb de vues se déclenche après chaque show sur l'annonce)
    public static function incrementView($annonceId){
        if (!isset($_SESSION['viewed_annonces'])) {
            $_SESSION['viewed_annonces'] = [];
        }

        if (in_array($annonceId, $_SESSION['viewed_annonces'])) {
            return false; // déjà vu
        }

        $_SESSION['viewed_annonces'][] = $annonceId;

        $rq= "UPDATE annonces SET view_count = view_count + 1 WHERE id = :id";
        $tab["id"] = $annonceId;

        return Database::execute($rq, $tab);
    }

}