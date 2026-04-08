<h2>Connexion</h2>

<?php include  'frontend/views/partials/flash.php'; ?>

<form method="POST" action="/login">
    <input type="hidden" name="_csrf_token" value="<?= Session::csrfToken() ?>">

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

<p>Pas de compte ? <a href="/register">S'inscrire</a></p>