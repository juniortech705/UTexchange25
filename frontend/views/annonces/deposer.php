<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déposer une annonce - UTexCHANGE</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

    <?php 
        include 'frontend/views/partials/header.php';
        include 'frontend/views/partials/nav.php';

    ?>

    <main style="padding: 50px; text-align: center;">
        <h2 style="color: #0056b3; margin-bottom: 20px;">Déposer une annonce</h2>
        
        <form action="backend/actions/create_annonce.php" method="POST" enctype="multipart/form-data" style="max-width: 500px; margin: auto; display: flex; flex-direction: column; gap: 15px; text-align: left;">
            
            <label style="font-weight: bold;">Intitulé de l'annonce</label>
            <input type="text" name="titre" placeholder="ex: Livre de Maths, Veste UT..." style="padding: 12px; border: 1px solid #ccc; border-radius: 5px;" required>

            <label style="font-weight: bold;">Catégorie</label>
            <select name="categorie" style="padding: 12px; border: 1px solid #ccc; border-radius: 5px;" required>
                <option value="" disabled selected>Choisir une catégorie</option>
                <option value="livres">Livres</option>
                <option value="vetements">Vêtements / Chaussures</option>
                <option value="electronique">Électronique</option>
                <option value="electronique">Électroménager</option>
                <option value="dons">Dons</option>
                <option value="autre">Autre</option>
            </select>

            
            <label style="font-weight: bold;">Description</label>
            <textarea name="description" placeholder="Détails sur l'état de l'objet..." rows="5" style="padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-family: sans-serif;"></textarea>

            <label style="font-weight: bold;">Photo de l'objet</label>
            <input type="file" name="image" accept="image/*" style="padding: 10px; border: 1px dashed #0056b3; background: #f9f9f9;">

            <button type="submit" style="background: #0056b3; color: white; padding: 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 1.1rem; margin-top: 10px;">
                Publier l'annonce
            </button>
        </form>
    </main>

    <?php include 'frontend/views/partials/footer.php'; ?>

</body>
</html>