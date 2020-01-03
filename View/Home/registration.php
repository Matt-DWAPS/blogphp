<?php $this->title = "Inscription"; ?>

<h2 class="post-title" id="contenu">Inscription</h2>

<form method="post">
    <label for="username">Nom d'utilisateur</label>
    <input type="text" id="username" name="username" placeholder="Entrer votre nom d'utilisateur : "
           value="<?= isset($post['username']) ? $post['username'] : ''; ?>"/>
    <p><?= isset($errorsMsg['username']) ? $errorsMsg['username'] : ''; ?></p>
    <br/>
    <label for="email">Adresse Email</label>
    <input type="text" id="email" name="email" placeholder="Entrer l'adresse e-mail :"
           value="<?= isset($post['email']) ? $post['email'] : ''; ?>"/>
    <p><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>
    <br/>
    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" placeholder="Entrer votre mot de passe :"/>
    <p><?= isset($errorsMsg['password']) ? $errorsMsg['password'] : ''; ?></p>
    <br/>
    <label for="password"> Retapez votre mot de passe</label>
    <input type="password" id="cPassword" name="cPassword" placeholder="Entrer votre mot de passe :"/>

    <input type="hidden" name="registerForm" value="register">
    <input type="submit" id="submit" value="Valider">
</form>