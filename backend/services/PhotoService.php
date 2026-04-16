<?php
require_once __DIR__ . '/../model/photo.php';
require_once __DIR__ . '/../../PgSQL/database.php';
require_once __DIR__ . '/../../storage/PhotoHelper.php';

class PhotoService{
    //getByAnnonce
    public static function getByAnnonce($annonceId){
        $rq = "SELECT * FROM photos WHERE annonce_id = :annonce_id";
        $tab = ["annonce_id" => $annonceId];
        return Database::query($rq, "Photo", $tab);
    }
    //getCover
    public static function getCover($annonceId){
        $rq = "SELECT * FROM photos WHERE annonce_id = :annonce_id AND is_cover = TRUE LIMIT 1";
        $tab = ["annonce_id" => $annonceId];
        return Database::find($rq, "Photo", $tab);
    }
    //setCover
    public static function setCover($id, $annonceId){
        //On retire cover à l'annonce
        $rq1="UPDATE photos SET is_cover = FALSE WHERE annonce_id = :annonce_id";
        $tab1["annonce_id"] = $annonceId;
        Database::execute($rq1, $tab1);

        //On met le cover à la photo
        $rq2= "UPDATE photos SET is_cover = true WHERE id = :id";
        $tab2["id"] = $id;
        Database::execute($rq2, $tab2);
    }
    //deleteByAnnonce
    public static function deleteByAnnonce($annonceId){
        $photos = self::getByAnnonce($annonceId);
        foreach($photos as $photo){
            //suppression de chaque fichier
            (new PhotoHelper)->deleteFile($photo->getCheminFichier());
        }
        //suppression du dossier
        (new PhotoHelper)->deleteAnnonceDir($annonceId);

        $rq="DELETE FROM photos WHERE annonce_id = :annonce_id";
        $tab["annonce_id"] = $annonceId;
        return Database::execute($rq, $tab);
    }
    //delete
    public static function delete($id){
        $photo = self::getById($id);
        if(!$photo){
            return false;
        }
        //suppression physique
        (new PhotoHelper)->deleteFile($photo->getCheminFichier());

        $rq= "DELETE FROM photos WHERE id = :id";
        $tab["id"] = $id;
        return Database::execute($rq, $tab);
    }
    //getById
    public static function getById($id){
        $rq = "SELECT * FROM photos WHERE id = :id";
        $tab = ["id" => $id];
        return Database::find($rq, "Photo", $tab);
    }
    //countByAnnonce (nombre de photos)
    public static function countByAnnonce($annonceId){
        $rq = "SELECT COUNT(*) FROM photos WHERE annonce_id = :annonce_id";
        $tab = ["annonce_id" => $annonceId];
        return Database::count($rq, $tab);
    }
    //upload
    public static function upload($files, $annonceId){
        $nb= self::countByAnnonce($annonceId);
        $result= (new PhotoHelper)->processUpload($files, $annonceId, $nb);

        if (empty($result['files'])) {
            return ['success' => false, 'errors' => $result['errors']];
        }

        $hasCover= self::getCover($annonceId) !==null; //check si on a déjà une photo de couverture
        $isFirst = !$hasCover; // true si on a pas encore de couverture
        foreach ($result['files'] as $fileData) {
            self::save([
                'annonce_id'     => $annonceId,
                'chemin_fichier' => $fileData['chemin_fichier'],
                'nom_fichier'    => $fileData['nom_fichier'],
                'taille_fichier' => $fileData['taille_fichier'],
                'is_cover'       => $isFirst,
            ]);
            $isFirst = false;
        }

        return ['success' => true, 'errors' => $result['errors']];
    }
    //save
    private static function save($data){
        $rq="INSERT INTO photos (annonce_id, chemin_fichier, nom_fichier, taille_fichier, is_cover)
            VALUES (:annonce_id, :chemin_fichier, :nom_fichier, :taille_fichier, :is_cover)";

        $tab = [
            'annonce_id' => $data['annonce_id'],
            'chemin_fichier' => $data['chemin_fichier'],
            'nom_fichier'=> $data['nom_fichier'],
            'taille_fichier' => $data['taille_fichier'],
            'is_cover' => $data['is_cover'] ? 'true' : 'false',
        ];

        return Database::execute($rq, $tab);
    }
    //URL
    public static function url($path){
        return PhotoHelper::url($path);
    }
    //serve
    public static function serve($annonceId, $nomFichier){
        $path = 'annonces/' . $annonceId . '/' . $nomFichier;

        //vérification du chemin dans la BD
        $rq= "SELECT id FROM photos WHERE chemin_fichier = :path";
        $result= Database::find($rq, "Photo",['path' => $path]);
        if(!$result){
            http_response_code(404);
            exit('Image introuvable.');
        }

        (new PhotoHelper)->serve($path);
    }

}
