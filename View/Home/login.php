<?php $this->title = "Connexion"; ?>

<h2 class="post-title" id="contenu">Connexion</h2>
<form method="post">
    <label for="email">Adresse Email</label>
    <input type="text" id="email" name="email" placeholder="Entrer l'adresse e-mail :"
           value="<?= isset($post['email']) ? $post['email'] : ''; ?>"/>
    <p class="text-danger"><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>
    <br/>
    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" placeholder="Entrer votre mot de passe :"/>
    <p class="text-danger"><?= isset($errorsMsg['password']) ? $errorsMsg['password'] : ''; ?></p>

    <input type="hidden" name="loginForm" value="login">
    <input type="submit" id="submit" value="Connexion">
</form>
