<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une catégorie — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#9ca3af;margin-bottom:20px;">
    <a href="/dashboard" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Admin</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <a href="/categories" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Catégories</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <span style="color:#374151;font-weight:500;">Ajouter</span>
</div>

<main class="flex-1 max-w-lg mx-auto w-full px-4 py-10">
    <div style="background:#fff;border:1px solid #f0f0f0;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.04);">
        <div style="margin-bottom:24px;">
            <h1 style="font-size:1.4rem;font-weight:700;color:#111;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-plus" style="color:#0056b3;font-size:1.1rem;"></i>
                Ajouter une catégorie
            </h1>
        </div>
        <form method="POST" action="/categories/add">
            <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">
                    Nom de la catégorie *
                </label>
                <input type="text" name="nom" required
                       placeholder="Ex: Informatique, Vêtements…"
                       style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:10px;
                              font-size:14px;font-family:'Poppins',sans-serif;outline:none;transition:border-color .2s;"
                       onfocus="this.style.borderColor='#0056b3';this.style.boxShadow='0 0 0 3px rgba(0,86,179,.08)'"
                       onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">
                    Catégorie parente
                </label>
                <select name="parent_id"
                        style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:10px;
                               font-size:14px;font-family:'Poppins',sans-serif;outline:none;transition:border-color .2s;background:#fafafa;"
                        onfocus="this.style.borderColor='#0056b3'" onblur="this.style.borderColor='#e5e7eb'">
                    <option value="">— Aucun parent (catégorie racine) —</option>
                    <?php foreach ($parents as $p): ?>
                        <option value="<?= $p->getId() ?>"><?= htmlspecialchars($p->getNom()) ?></option>
                    <?php endforeach; ?>
                </select>
                <p style="font-size:11px;color:#9ca3af;margin-top:5px;">
                    <i class="fa-solid fa-circle-info" style="margin-right:4px;"></i>
                    Laissez vide pour créer une catégorie principale.
                </p>
            </div>

            <div style="display:flex;gap:10px;">
                <a href="/categories"
                   style="flex:1;padding:11px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:13px;
                          font-weight:600;color:#6b7280;text-decoration:none;text-align:center;
                          transition:background .15s;"
                   onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                    Annuler
                </a>
                <button type="submit"
                        style="flex:1;padding:11px;background:#0056b3;color:white;border:none;border-radius:10px;
                               font-size:13px;font-weight:700;cursor:pointer;font-family:'Poppins',sans-serif;
                               transition:background .15s;"
                        onmouseover="this.style.background='#004a99'" onmouseout="this.style.background='#0056b3'">
                    <i class="fa-solid fa-plus" style="margin-right:5px;"></i>Ajouter
                </button>
            </div>
        </form>
    </div>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
</body>
</html>