<?php $this->title = "Utilisateur"; ?>
<h2 class="post-title" id="contenu">Informations utilisateur</h2>
<?php if (isset($_SESSION['flash'])) : ?>
    <p class="text-center font-weight-bold text-success alert alert-<?= $_SESSION['flash']['alert']; ?>">
        <?= $_SESSION['flash']['infos']; ?></p>
<?php endif; ?>
<form method="post">
    <div class="border rounded p-3 pb-5 col-12">
        <div class="row p-2 m-2">
            <?php if (empty($users->picture)) : ?>
                <div class="border rounded p-2 col-3">
                    <div class="d-flex justify-content-center border p-2">
                        <i class="fas fa-user fa-10x"></i>
                    </div>
                    <div class="border col mt-3 p-2">
                        <a class="btn btn-primary" role="button"
                           href="<?= "dashboard/pictureUpload/" . $users->id ?>">
                            Ajouter
                            une photo de profil</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="border rounded p-2 col-3">
                    <div class="d-flex justify-content-center border p-2">
                        <img class="img-fluid" src="<?= $users->picture ?>">
                    </div>
                    <div class="border col mt-3 p-2">
                        <a class="btn btn-primary" role="button"
                           href="<?= "dashboard/pictureUpload/" . $users->id ?>">
                            Modifier la photo de profil</a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col">
                <div class="row p-3">
                    <div class="col text-right">
                        <p>Date d'inscription : <?= $users->created_at ?></p>
                    </div>
                </div>
                <div class="row p-3">
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
                <div class="row p-3">
                    <div class="col">
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
                    <div class="col">
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
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-center">
                <a class=" btn btn-danger" role="button" href="dashboard/"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
            <div class="col text-center">
                <input type="hidden" name="userForm" value="updateUser"/>
                <input class="btn btn-primary" type="submit" name="saveUpdate" value="Enregistrer les modifications">
            </div>
        </div>
</form>
