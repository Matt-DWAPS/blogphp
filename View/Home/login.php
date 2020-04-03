<?php $this->title = "Connexion"; ?>

<h2 class="post-title" id="contenu">Connexion</h2>

<form method="post">
    <div class="d-flex justify-content-center">
        <div class="border rounded p-3 col-8">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input class="form-control" type="text" id="email" name="email"
                       value="<?= isset($post['email']) ? $post['email'] : ''; ?>"/>
                <p class="text-danger"><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input class="form-control" type="password" id="password" name="password"/>
                <p class="text-danger"><?= isset($errorsMsg['password']) ? $errorsMsg['password'] : ''; ?></p>
            </div>
            <div class="d-flex justify-content-around">
                <input type="hidden" name="loginForm" value="login">
                <input class="btn btn-primary" type="submit" id="submit" value="Connexion">
                <a class="text-danger" href="home/forgotYourPassword">Mot de passe oublier ?</a>
            </div>


        </div>
    </div>

</form>
