<?php
if (!isset($_GET['cat'])) {
    header("Location: index.php");
    exit();
}

$cat_active = $_GET['cat'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($cat_active); ?> - UTexCHANGE</title>
    <link rel="stylesheet" href="front-end/css/style.css">
</head>
<body>

    <?php 
        include 'front-end/views/partials/header.php'; 
        include 'front-end/views/partials/nav.php'; 
    ?>

    <main style="padding: 20px 50px;">
        <h2 style="margin-bottom: 30px; color: #0056b3;">
            Section : <?php echo ucfirst($cat_active); ?>
        </h2>

        <div class="annonces-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            <?php 
            
                $nombre_annonces = 0;
                if($cat_active == 'livres') $nombre_annonces = 4;
                elseif($cat_active == 'electronique') $nombre_annonces = 3;
                elseif($cat_active == 'vetements') $nombre_annonces = 5;
                elseif($cat_active == 'dons') $nombre_annonces = 2;
                else $nombre_annonces = 6;

                if ($nombre_annonces > 0) {
                    for ($i = 0; $i < $nombre_annonces; $i++) {
                        include 'front-end/views/annonces/index.php';
                    }
                } else {
                    echo "<p>Aucune annonce dans cette catégorie pour le moment.</p>";
                }
            ?>
        </div>
    </main>

    <?php include 'front-end/views/partials/footer.php'; ?>

</body>
</html>