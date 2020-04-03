<?php $this->title = "Inscription"; ?>

<div class="d-flex justify-content-center">
    <div class="border rounded p-3 col-8 mt-3">
        <h2 class="post-title" id="contenu">Inscription</h2>
        <form method="post">
            <div class="row">
                <div class="col form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input class="form-control" type="text" id="username" name="username"
                           value="<?= isset($post['username']) ? $post['username'] : ''; ?>"/>
                    <p class="text-danger"><?= isset($errorsMsg['username']) ? $errorsMsg['username'] : ''; ?></p>
                </div>
                <div class="col form-group">
                    <label for="email">Adresse Email</label>
                    <input class="form-control" type="text" id="email" name="email"
                           value="<?= isset($post['email']) ? $post['email'] : ''; ?>"/>
                    <p class="text-danger"><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col form-group">
                    <label for="password">Mot de passe</label>
                    <input class="form-control" type="password" id="password" name="password"/>
                    <p class="text-danger"><?= isset($errorsMsg['password']) ? $errorsMsg['password'] : ''; ?></p>
                </div>
                <div class="col form-group">
                    <label for="cPassword"> Retapez votre mot de passe</label>
                    <input class="form-control" type="password" id="cPassword" name="cPassword"/>
                </div>
            </div>
            <input type="hidden" name="registerForm" value="register">
            <input class="btn btn-primary" type="submit" id="submit" value="Valider">
        </form>
    </div>
</div>

