<h2>Inscription</h2>

<?php if (!empty($flashes)): ?>
    <?php foreach ($flashes as $type => $messages): ?>
        <?php foreach ($messages as $message): ?>
            <p><strong><?= $type ?>:</strong> <?= htmlspecialchars($message) ?></p>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="/register">
    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Prénom :</label><br>
    <input type="text" name="prenom" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>


    <button type="submit">S'inscrire</button>
</form>

<p>Déjà un compte ? <a href="/login">Se connecter</a></p>