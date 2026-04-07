<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTexCHANGE- Accueil</title>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>

    <?php
        include 'frontend/views/partials/header.php';
        include 'frontend/views/partials/nav.php';


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
                include 'frontend/views/annonces/index.php';
            }
        ?>
    </div>
</main>

    <?php include 'frontend/views/partials/footer.php'; ?>

    <script src="/frontend/js/script.js"></script>
</body>
</html>


