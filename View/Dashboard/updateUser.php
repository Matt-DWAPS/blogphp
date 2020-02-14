<?php $this->title = "Utilisateur"; ?>
<div>
    <img src=""/>
</div>
<h2 class="post-title" id="contenu">Informations utilisateur</h2>

<form method="post">
    <div class="border rounded p-3 pb-5 col-12">
        <div class="row p-2 m-2 col-12 align-items-center">
            <div class="col border rounded p-3 col-4">
                <label for="picture">Ajouter une photo de profil</label><br/>
                <input type="file" class="btn btn-primary" id="picture" name="picture"
                       accept="image/png, image/jpeg, image/jpg"
                       value="<?= $users->picture, isset($post['picture']) ? $post['picture'] : ''; ?>">
                <p class="text-danger"><?= isset($errorsMsg['picture']) ? $errorsMsg['picture'] : ''; ?></p>

            </div>
            <div class="col-8 text-center">
                <p>Date d'inscription : <?= $users->created_at ?></p>
            </div>
        </div>
        <div class="row p-2">
            <div class="col">
                <label for="username">Nom d'utilisateur :</label>
                <input class="form-control" type="text" id="username" name="username"
                       value="<?= $users->username ?>">
            </div>
            <div class="col">
                <label for="email">Adresse email :</label>
                <input class="form-control" type="email" id="email" name="email"
                       value="<?= $users->email, isset($post['email']) ? $post['email
                       '] : ''; ?>">
                <p class="text-danger"><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>
            </div>
        </div>
        <div class="row p-2 pb-4 d-flex justify-content-around">
            <div class="col-4">
                <label for="status">Statut :</label>
                <select class="form-control" name="status" id="status">
                    <?php

                    foreach ($user_status as $status => $level) : ?>
                        <option value="<?= $level; ?>"
                                <?= ($level === $users->status) ? 'selected' : '' ?>
                        ><?= $status; ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <div class="col-4">
                <label for="password">Nouveau mot de passe :</label>
                <input class="form-control" type="password" id="password" name="password"/>
                <p class="text-danger"><?= isset($errorsMsg['password']) ? $errorsMsg['password'] : ''; ?></p>
            </div>
        </div>
        <div class="row p-2 pb-5 d-flex justify-content-around">
            <div class="col-4">
                <label for="role">Rôle :</label>
                <select class="form-control" name="role" id="role">
                    <?php

                    foreach ($roles as $role => $level) : ?>
                        <option value="<?= $level; ?>"
                                <?= ($level === $users->role) ? 'selected' : '' ?>
                        ><?= $role; ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <div class="col-4">
                <label for="cPassword">Retapez votre mot de passe :</label>
                <input class="form-control" type="password" id="cPassword" name="cPassword"
                />
            </div>
        </div>

    </div>
    <div class="row mt-3">
        <div class="col">
            <a class=" btn btn-danger" role="button" href="dashboard/"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>
        <div class="col text-center">
            <input type="hidden" name="userForm" value="updateUser"/>
            <input class="btn btn-primary" type="submit" name="saveUpdate" value="Enregistrer les modifications">
        </div>
    </div>

</form>
