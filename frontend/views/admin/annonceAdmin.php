<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des annonces — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <link rel="stylesheet" href="/frontend/css/admin.css">
    <link rel="icon" type="image/png" href="/Images/favicon_utexchange.png">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#9ca3af;margin-bottom:20px;">
    <a href="/dashboard" style="color:inherit;text-decoration:none;"
       onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#9ca3af'">Admin</a>
    <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
    <span style="color:#374151;font-weight:500;">Annonces</span>
</div>

<!-- Modale consulter annonce -->
<div class="modal-overlay" id="modalViewAnnonce">
    <div class="modal-box" style="max-width:520px;">
        <button class="modal-close" onclick="closeModal('modalViewAnnonce')">&times;</button>
        <p class="modal-title" style="font-size:1.1rem;" id="modal-annonce-title">—</p>
        <div id="modal-annonce-body" style="margin-top:12px;"></div>
    </div>
</div>

<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">
                <i class="fa-solid fa-tag mr-2" style="color:#0056b3;"></i>
                Gestion des annonces
            </h1>
            <p class="text-sm text-gray-400 mt-1"><?= count($annonces) ?> annonces enregistrées</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead style="background:#f8faff;">
            <tr>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Annonce</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Vendeur</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Type</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Statut</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Date</th>
                <th class="text-right px-5 py-4 font-semibold text-gray-500">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            <?php foreach ($annonces as $annonce): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <!-- Miniature -->
                            <div style="width:42px;height:42px;border-radius:8px;overflow:hidden;background:#f4f6f8;flex-shrink:0;">
                                <?php $cover = $covers[$annonce->getId()] ?? null; ?>
                                <?php if ($cover): ?>
                                    <img src="/annonce/photo/<?= $annonce->getId() ?>/<?= urlencode($cover->getNomFichier()) ?>"
                                         style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?>
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#d1d5db;">
                                        <i class="fa-regular fa-image" style="font-size:1rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    <?= htmlspecialchars($annonce->getTitle()) ?>
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <?= $annonce->getType() !== 'don' ? number_format((float)$annonce->getPrice(), 2, ',', ' ') . ' €' : 'Gratuit' ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600 text-xs">
                        <?php $seller = $sellers[$annonce->getUtilisateurId()] ?? null; ?>
                        <?= $seller ? htmlspecialchars($seller->getPrenom() . ' ' . $seller->getNom()) : '—' ?>
                    </td>
                    <td class="px-5 py-4">
                        <span class="pill pill-<?= $annonce->getType() ?>">
                            <?= ucfirst($annonce->getType()) ?>
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="pill pill-<?= $annonce->getStatus() ?>">
                            <?= ucfirst($annonce->getStatus()) ?>
                        </span>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-xs">
                        <?= date('d/m/Y', strtotime($annonce->getCreatedAt())) ?>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-2">
                            <!-- Consulter -->
                            <button onclick='viewAnnonce(
                            <?= json_encode($annonce->getId()) ?>,
                            <?= json_encode($annonce->getTitle()) ?>,
                            <?= json_encode($annonce->getDescription()) ?>,
                            <?= json_encode($annonce->getLocation()) ?>,
                            <?= json_encode(date('d/m/Y', strtotime($annonce->getCreatedAt()))) ?>,
                            <?= json_encode((float)$annonce->getPrice()) ?>,
                            <?= json_encode($annonce->getType()) ?>,
                            <?= json_encode($annonce->getStatus()) ?>
                                    )'
                                    class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition"
                                    title="Consulter">

                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <!-- Signaler -->
                            <?php if($annonce->getStatus() !== 'signale'):  ?>
                                <form method="POST" action="/ad/annonce/report/<?= $annonce->getId() ?>" class="inline" onsubmit="return confirm('Voulez-vous vraiment signaler cette annonce ?');">
                                    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                                            title="Signaler">
                                        <i class="fa-solid fa-flag"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="/frontend/js/script.js"></script>
<script>
    function viewAnnonce(id, title, desc, location, date, price, type, status) {
        document.getElementById('modal-annonce-title').textContent = title;

        const typeLabels = { vente: 'Vente', don: 'Don gratuit', location: 'Location', troc: 'Troc' };
        const statusLabels = { active: 'Active', draft: 'Brouillon', vendu: 'Vendu', expire: 'Expirée', archive: 'Archivée' };

        document.getElementById('modal-annonce-body').innerHTML = `
        <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;">
            <span style="background:#eff6ff;color:#1d4ed8;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700;">${typeLabels[type] ?? type}</span>
            <span style="background:#f3f4f6;color:#6b7280;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700;">${statusLabels[status] ?? status}</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;font-size:13px;">
            <div style="background:#f9fafb;border-radius:8px;padding:10px;">
                <p style="font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Prix</p>
                <p style="font-weight:700;color:#0056b3;">${type === 'don' ? 'Gratuit' : price.toFixed(2).replace('.', ',') + ' €'}</p>
            </div>
            <div style="background:#f9fafb;border-radius:8px;padding:10px;">
                <p style="font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Lieu</p>
                <p style="font-weight:600;color:#374151;">${location}</p>
            </div>
            <div style="background:#f9fafb;border-radius:8px;padding:10px;">
                <p style="font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Publiée le</p>
                <p style="font-weight:600;color:#374151;">${date}</p>
            </div>
        </div>
        <div style="margin-top:4px;">
            <p style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">Description</p>
            <p style="font-size:13px;color:#4b5563;line-height:1.7;white-space:pre-line;">${desc}</p>
        </div>
        `;

        openModal('modalViewAnnonce');
    }

</script>
</body>
</html>