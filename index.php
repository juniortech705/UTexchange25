<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTexCHANGE- Accueil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="front-end/css/style.css">
    <link rel="icon" type="image/png" href="./Images/favicon1.png">
</head>
<body>

    <?php 
        include 'front-end/views/partials/header.php'; 
        include 'front-end/views/partials/nav.php'; 


    ?>

   <main>
    <?php 
        $cat = isset($_GET['cat']) ? $_GET['cat'] : 'Toutes les annonces';
        
        $titre_section = ucfirst($cat);
    ?>

    <h2 style="margin-bottom: 20px;">Section : <?php echo $titre_section; ?></h2>

    <div class="annonces-container">
        <?php 
            $nombre = 6; 
            if($cat == 'livres') $nombre = 2;
            if($cat == 'electronique') $nombre = 3;
            if($cat == 'dons') $nombre = 1;

            for ($i = 0; $i < $nombre; $i++) {
                include 'front-end/views/annonces/index.php';
            }
        ?>
    </div>
</main>

    <?php include 'front-end/views/partials/footer.php'; ?>

    <script src="front-end/js/script.js"></script>
</body>
</html>


