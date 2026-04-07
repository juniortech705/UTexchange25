<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oups ! - UTxCHANGE</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background-color: #f8f9fa; display: flex; flex-direction: column; min-height: 100vh;">

    <?php 
        include 'frontend/views/partials/header.php';
    ?>

    <main style="flex: 1; display: flex; align-items: center; justify-content: center; text-align: center; padding: 50px;">
        <div class="error-card" style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 500px;">
            
            <i class="fa-solid fa-triangle-excursion" style="font-size: 4rem; color: #ffcc00; margin-bottom: 20px;"></i>
            
            <h2 style="font-size: 2rem; color: #0056b3; margin-bottom: 10px;">Oups, une erreur !</h2>
            
            <p style="color: #666; margin-bottom: 30px;">
                La page que vous cherchez n'existe pas ou un problème technique est survenu. 
                Ne vous inquiétez pas, même les meilleurs ingénieurs font des erreurs !
            </p>

            <a href="index.php" style="background: #0056b3; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; transition: background 0.3s;">
                <i class="fa-solid fa-house" style="margin-right: 8px;"></i> Retourner à l'accueil
            </a>
        </div>
    </main>

    <?php include 'frontend/views/partials/footer.php'; ?>

</body>
</html>