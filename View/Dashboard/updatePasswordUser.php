<?php
/**
 * @var $user
 */
$this->title = "Espace membre - Modifier mon mot de passe"; ?>

<h2 class="post-title" id="contenu">Modifier mon mot de passe</h2>
<div class="col-6 border p-3">
    <form method="post">
        <div class="form-group">
            <label for="password">Modifier le mot de passe</label>
            <input class="form-control" type="password" name="password" id="password"/>
            <p class="text-danger"><?= isset($errorsMsg['password']) ? $errorsMsg['password'] : ''; ?></p>
            <label for="cPassword">Retapez le mot de passe</label>
            <input class="form-control" name="cPassword" type="password" id="cPassword"/>
        </div>

        <div class="d-flex">
            <div class="col text-left">
                <a class="btn btn-primary" role="button"
                   href="dashboard">Retour</a>
            </div>
            <div class="col text-right">
                <input type="hidden" name="userForm" value="updateUserPassword"/>
                <input class="btn btn-primary" type="submit"
                       value="Enregistrer les modifications">
            </div>
        </div>
    </form>
</div>
