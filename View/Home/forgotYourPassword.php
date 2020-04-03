<?php $this->title = "Mot de passe oublier"; ?>

<h2 class="post-title" id="contenu">Mot de passe oublier</h2>
<p>Saisissez l'adresse mail associée à votre compte.<br/>
    Nous allons envoyer à cette adresse un lien vous permettant de réinitialiser facilement votre mot de passe.</p>
<form method="post">
    <div class="d-flex justify-content-center">
        <div class="border rounded p-3 col-8">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input class="form-control" type="text" id="email" name="email"
                       value="<?= isset($post['email']) ? $post['email'] : ''; ?>"/>
                <p class="text-danger"><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>
            </div>
            <div>
                <input type="hidden" name="forgotYourPasswordForm" value="sendPassword">
                <input class="btn btn-primary" type="submit" id="submit" value="Valider">
            </div>


        </div>
    </div>

</form>
