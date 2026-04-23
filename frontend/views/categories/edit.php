<h2>Modifier catégorie</h2>

<form method="POST" action="/categories/edit/<?= $categorie->getId() ?>">

    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

    <!-- Nom -->
    <div>
        <label>Nom</label>
        <input type="text" name="nom"
               value="<?= htmlspecialchars($categorie->getNom()) ?>" required>
    </div>

    <br>

    <!-- Parent -->
    <div>
        <label>Catégorie parent</label>

        <select name="parent_id">
            <option value="">-- Aucun parent --</option>

            <?php foreach ($parents as $p): ?>
                <?php if ($p->getId() == $categorie->getId()) continue; ?>

                <option value="<?= $p->getId() ?>"
                    <?= ($categorie->getParentId() == $p->getId()) ? 'selected' : '' ?>>

                    <?= htmlspecialchars($p->getNom()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>

    <button type="submit">Modifier</button>
</form>

<br>
<a href="/categories">⬅ Retour</a>