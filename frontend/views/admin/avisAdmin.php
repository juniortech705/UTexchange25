<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des avis — UTexCHANGE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/modals.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .star { color: #facc15; font-size: 13px; }
        .star-empty { color: #e5e7eb; font-size: 13px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/flash.php'; ?>
<?php include __DIR__ . '/../partials/modals.php'; ?>

<!-- Modale consulter avis -->
<div class="modal-overlay" id="modalViewAvis">
    <div class="modal-box" style="max-width:460px;">
        <button class="modal-close" onclick="closeModal('modalViewAvis')">&times;</button>
        <p class="modal-title" style="font-size:1.1rem;">Détail de l'avis</p>
        <div id="modal-avis-body" style="margin-top:14px;"></div>
    </div>
</div>

<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800" style="font-family:'Poppins',sans-serif;">
                <i class="fa-solid fa-star mr-2" style="color:#0056b3;"></i>
                Gestion des avis
            </h1>
            <p class="text-sm text-gray-400 mt-1"><?= count($avis) ?> avis enregistrés</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead style="background:#f8faff;">
            <tr>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Auteur</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Vendeur évalué</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Note</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Commentaire</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Statut</th>
                <th class="text-left px-5 py-4 font-semibold text-gray-500">Date</th>
                <th class="text-right px-5 py-4 font-semibold text-gray-500">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            <?php foreach ($avis as $a): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-800 text-xs">
                            <?= htmlspecialchars($a->getAcheteurNom().' '.$a->getAcheteurPrenom() ?? '—') ?>
                        </p>
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs">
                        <?= htmlspecialchars($a->getVendeurNom().' '.$a->getVendeurPrenom() ?? '—') ?>
                    </td>
                    <td class="px-5 py-4">
                        <div style="display:flex;gap:1px;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="<?= $i <= $a->getNote() ? 'star' : 'star-empty' ?>">★</span>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs"
                        style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <?= !empty(trim($a->getCommentaire() ?? '')) ? htmlspecialchars($a->getCommentaire()) : 'Pas de commentaire' ?>
                    </td>
                    <td class="px-5 py-4">
                        <?php if ($a->getIsActive()): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background:#dcfce7;color:#16a34a;">Actif</span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background:#fee2e2;color:#dc2626;">Désactivé</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-xs">
                        <?= date('d/m/Y', strtotime($a->getCreatedAt())) ?>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-2">
                            <!-- Consulter -->
                            <button onclick='viewAvis(
                            <?= json_encode(trim(($a->getAcheteurNom() ?? "") . " " . ($a->getAcheteurPrenom() ?? ""))) ?>,
                            <?= json_encode(trim(($a->getVendeurNom() ?? "") . " " . ($a->getVendeurPrenom() ?? ""))) ?>,
                            <?= json_encode((int)$a->getNote()) ?>,
                            <?= json_encode($a->getCommentaire() ?? '') ?>,
                            <?= json_encode(date('d/m/Y', strtotime($a->getCreatedAt()))) ?>,
                            <?= json_encode((bool)$a->getIsActive()) ?>
                                    )'
                                    class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition"
                                    title="Consulter">

                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <!-- Désactiver (si actif) -->
                            <?php if ($a->getIsActive()): ?>
                                <form method="POST" action="/ad/avis/deactivate/<?= $a->getId() ?>" class="inline" onsubmit="return confirm('Voulez-vous vraiment désactiver cet avis ?');">
                                    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                                            title="Désactiver">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- Réactiver -->
                                <form method="POST" action="/ad/avis/activate/<?= $a->getId() ?>" class="inline">
                                    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition"
                                            title="Réactiver">
                                        <i class="fa-solid fa-circle-check"></i>
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
    function viewAvis(auteur, vendeur, note, commentaire, date, isActive) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<span style="font-size:1.3rem;color:${i <= note ? '#facc15' : '#e5e7eb'};">★</span>`;
        }

        document.getElementById('modal-avis-body').innerHTML = `
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;">
            <div style="background:#f9fafb;border-radius:8px;padding:10px;">
                <p style="font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Auteur</p>
                <p style="font-weight:600;color:#111;font-size:13px;">${auteur}</p>
            </div>
            <div style="background:#f9fafb;border-radius:8px;padding:10px;">
                <p style="font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Vendeur évalué</p>
                <p style="font-weight:600;color:#111;font-size:13px;">${vendeur}</p>
            </div>
        </div>
        <div style="margin-bottom:14px;">
            <p style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">Note</p>
            <div style="display:flex;align-items:center;gap:8px;">
                ${stars}
                <span style="font-size:13px;color:#6b7280;">(${note}/5)</span>
            </div>
        </div>
        ${commentaire ? `
        <div style="margin-bottom:14px;">
            <p style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">Commentaire</p>
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px;font-size:13px;color:#374151;line-height:1.6;">
                ${commentaire}
            </div>
        </div>` : ''}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px;">
            <p style="font-size:12px;color:#9ca3af;">Posté le ${date}</p>
            <span style="padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700;
                         background:${isActive ? '#dcfce7' : '#fee2e2'};
                         color:${isActive ? '#16a34a' : '#dc2626'};">
                ${isActive ? 'Actif' : 'Désactivé'}
            </span>
        </div>`;

        openModal('modalViewAvis');
    }
</script>
</body>
</html>