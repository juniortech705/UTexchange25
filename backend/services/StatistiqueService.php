<?php
require_once __DIR__ . '/../model/annonce.php';
require_once __DIR__ . '/../../PgSQL/database.php';

class StatistiqueService{
    //Les annonces (stats selon type et staut, total, lastest)
    public static function getAnnonceStats(){
        $rq="SELECT
            COUNT(*) FILTER (WHERE type = 'don')      AS dons,
            COUNT(*) FILTER (WHERE type = 'troc')     AS trocs,
            COUNT(*) FILTER (WHERE statut = 'vendu')  AS vendues,
            COUNT(*) FILTER (WHERE statut = 'active') AS actives
        FROM annonces";

        return Database::fetch($rq);
    }
    public static function getLatestAnnonces($limit = 10){
        $rq="SELECT *
        FROM annonces
        ORDER BY created_at DESC
        LIMIT $limit";

        return Database::query($rq, 'Annonce',[]);
    }
    public static function getNbAnnonces(){
        $rq="SELECT COUNT(*) FROM annonces";
        return Database::count($rq,[]);
    }
    //Les avis (stats selon is_active, total)
    public static function getAvisStats(){
        $rq="SELECT
            COUNT(*) FILTER (WHERE is_active = true)  AS actifs,
            COUNT(*) FILTER (WHERE is_active = false) AS signales
        FROM avis";

        return Database::fetch($rq);
    }
    public static function getNbAvis(){
        $rq="SELECT COUNT(*) FROM avis";
        return Database::count($rq,[]);
    }
    //Les users (top 10 vendeurs, total)
    public static function getTopVendeurs($limit = 5){
        $rq="SELECT
            u.id,
            u.nom,
            u.prenom,
            ROUND(AVG(a.note), 1) AS note_moyenne,
            COUNT(a.id)           AS nb_avis
        FROM avis a
        JOIN utilisateurs u ON u.id = a.vendeur_id
        WHERE a.is_active = true
        GROUP BY u.id
        ORDER BY note_moyenne DESC, nb_avis DESC
        LIMIT $limit";

        return Database::fetchAllAssoc($rq);
    }
    public static function getNbUsers(){
        $rq="SELECT COUNT(*) FROM utilisateurs";
        return Database::count($rq,[]);
    }
    //Les categories
    public static function getTopCategories($limit = 5){
        $rq="SELECT
            c.id,
            c.nom,
            COUNT(a.id) AS total_annonces
        FROM categories c
        JOIN annonces a ON a.cat_id = c.id
        GROUP BY c.id
        ORDER BY total_annonces DESC
        LIMIT $limit";

        return Database::fetchAllAssoc($rq);
    }

    //nbConversation
    public static function getNbConversations(){
        $rq="SELECT COUNT(*) FROM conversations";
        return Database::count($rq,[]);
    }
}
