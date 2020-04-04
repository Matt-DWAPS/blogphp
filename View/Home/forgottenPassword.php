<?php $this->title = " Nouveau mot de passe"; ?>

<div class="d-flex justify-content-center">
    <div class="border rounded p-3 col-8 mt-3">
        <h2 class="post-title" id="contenu">Redéfinition</h2>
        <form method="post">
            <div class="row">
                <div class="col form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input class="form-control" type="password" id="password" name="password"/>
                    <p class="text-danger"><?= isset($errorsMsg['password']) ? $errorsMsg['password'] : ''; ?></p>
                </div>
                <div class="col form-group">
                    <label for="cPassword"> Retapez votre mot de passe</label>
                    <input class="form-control" type="password" id="cPassword" name="cPassword"/>
                </div>
            </div>
            <input type="hidden" name="passwordForm" value="newPassword">
            <input class="btn btn-primary" type="submit" id="submit" value="Valider">
        </form>
    </div>
</div>