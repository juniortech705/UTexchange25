<h2>Ajouter une catégorie</h2>

<form method="POST" action="/categories/add">

    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

    <div>
        <label>Nom</label>
        <input type="text" name="nom" required>
    </div>

    <div>
        <label>Catégorie parent</label>
        <select name="parent_id">
            <option value="">-- Aucun parent --</option>

            <?php foreach ($parents as $p): ?>
                <option value="<?= $p->getId() ?>">
                    <?= htmlspecialchars($p->getNom()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit">Ajouter</button>
</form>

<br>
<a href="/categories">⬅ Retour</a>