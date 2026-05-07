<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau Admin — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
</head>
<body class="bg-gray-50">

<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/flash.php'; ?>
<?php include __DIR__ . '/partials/modals.php'; ?>

<main class="max-w-6xl mx-auto px-4 py-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">
            <i class="fa-solid fa-gauge-high mr-2" style="color:#f59e0b;"></i>
            Panneau Administration
        </h1>
        <p class="text-gray-400 text-sm mt-1">
            Bonjour <?= htmlspecialchars(Session::get('prenom') ?? 'Admin') ?> — Tableau de bord
        </p>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <?php
        $cards = [
                ['label' => 'Utilisateurs',  'value' => $nbUser,         'icon' => 'fa-users',       'color' => '#0056b3', 'bg' => '#eff6ff'],
                ['label' => 'Annonces',      'value' => $nbAnnonces,      'icon' => 'fa-tag',         'color' => '#16a34a', 'bg' => '#dcfce7'],
                ['label' => 'Conversations', 'value' => $nbConv, 'icon' => 'fa-comments',    'color' => '#7c3aed', 'bg' => '#ede9fe'],
                ['label' => 'Avis',          'value' => $nbAvis,          'icon' => 'fa-star',        'color' => '#f59e0b', 'bg' => '#fef9c3'],
        ];
        foreach ($cards as $card): ?>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background:<?= $card['bg'] ?>;">
                        <i class="fa-solid <?= $card['icon'] ?> text-lg" style="color:<?= $card['color'] ?>;"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($card['value']) ?></p>
                <p class="text-sm text-gray-400"><?= $card['label'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Accès rapide -->
    <h2 class="text-lg font-bold text-gray-700 mb-4">Accès rapide</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <?php
        $links = [
                ['href' => '/users',     'icon' => 'fa-users',           'label' => 'Gérer les utilisateurs', 'color' => '#0056b3'],
                ['href' => '/adAnnonces',  'icon' => 'fa-tag',             'label' => 'Gérer les annonces',     'color' => '#16a34a'],
                ['href' => '/categories', 'icon' => 'fa-folder',  'label' => ' Gérer les catégories',             'color' => '#7c3aed'],
                ['href' => '/adAvis','icon' => 'fa-star',            'label' => 'Gérer les avis',       'color' => '#f59e0b'],
                ['href' => '/adStats','icon' => 'fa-chart-bar',      'label' => 'Statistiques',           'color' => '#0891b2'],
        ];
        foreach ($links as $link): ?>
            <a href="<?= $link['href'] ?>"
               class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-50">
                    <i class="fa-solid <?= $link['icon'] ?> text-lg" style="color:<?= $link['color'] ?>;"></i>
                </div>
                <span class="font-semibold text-gray-700 text-sm"><?= $link['label'] ?></span>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300 ml-auto"></i>
            </a>
        <?php endforeach; ?>
    </div>

</main>

<?php include __DIR__ . '/partials/footer.php'; ?>

<script src="/frontend/js/script.js"></script>
</body>
</html>