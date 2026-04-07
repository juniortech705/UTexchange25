<h2>Connexion</h2>

<?php if (!empty($flashes)): ?>
    <?php foreach ($flashes as $type => $messages): ?>
        <?php foreach ($messages as $message): ?>
            <p><strong><?= $type ?>:</strong> <?= htmlspecialchars($message) ?></p>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="/login">
    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

<p>Pas de compte ? <a href="/register">S'inscrire</a></p>