<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page introuvable — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .error-banner {
            background: linear-gradient(135deg, #0056b3 0%, #004a99 100%);
            border-radius: 20px;
            padding: 60px 40px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }
        .error-banner::before {
            content: '';
            position: absolute; top: -60px; right: -60px;
            width: 260px; height: 260px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
        }
        .error-banner::after {
            content: '';
            position: absolute; bottom: -80px; left: 40px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 900;
            opacity: .15;
            line-height: 1;
            position: absolute;
            top: 16px; right: 32px;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php
// Header si possible, sinon fallback minimal
if (function_exists('Session') || class_exists('Session')) {
    include __DIR__ . '/../partials/header.php';
}
?>

<main class="flex-1 flex items-center justify-center px-4 py-16">
    <div class="error-banner">
        <span class="error-code">404</span>
        <div style="position:relative;z-index:1;">
            <div style="width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,.15);
                        display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:2rem;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h1 style="font-size:1.6rem;font-weight:700;margin-bottom:12px;line-height:1.3;">
                On n'a rien trouvé par ici
            </h1>
            <p style="opacity:.8;font-size:14px;line-height:1.7;margin-bottom:28px;max-width:420px;margin-left:auto;margin-right:auto;">
                La page que vous cherchez a peut-être été déplacée, renommée ou n'a jamais existé.
                Vérifiez l'adresse ou retournez sur la plateforme.
            </p>
            <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                <a href="/"
                   style="background:white;color:#0056b3;padding:11px 22px;border-radius:10px;
                          font-weight:700;font-size:13px;text-decoration:none;transition:opacity .15s;"
                   onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                    <i class="fa-solid fa-house" style="margin-right:6px;"></i>Accueil
                </a>
                <a href="/annonces"
                   style="background:rgba(255,255,255,.15);color:white;padding:11px 22px;
                          border:1.5px solid rgba(255,255,255,.35);border-radius:10px;
                          font-weight:600;font-size:13px;text-decoration:none;transition:background .15s;"
                   onmouseover="this.style.background='rgba(255,255,255,.25)'"
                   onmouseout="this.style.background='rgba(255,255,255,.15)'">
                    Voir les annonces
                </a>
            </div>
        </div>
    </div>
</main>

<?php
if (function_exists('Session') || class_exists('Session')) {
    include __DIR__ . '/../partials/footer.php';
}
?>
<script src="/frontend/js/script.js"></script>
</body>
</html>