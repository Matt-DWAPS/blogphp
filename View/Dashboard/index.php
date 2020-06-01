<?php
/**
 * @var $user
 */
$this->title = "Espace membre"; ?>

<h2 class="post-title" id="contenu">Mon Compte</h2>
<?php if (isset($_SESSION['flash'])) : ?>
    <p class="text-center font-weight-bold text-success alert alert-<?= $_SESSION['flash']['alert']; ?>">
        <?= $_SESSION['flash']['message']; ?></p>
<?php endif; ?>

<?php if (isset($_SESSION['auth']) && $_SESSION['auth']['role'] == '99' || $_SESSION['auth']['role'] == '75') : ?>
    <div class="tableau border mb-3">
        <h2 class="text-center">Infos Utilisateurs</h2>
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="text-center">Date d'inscription</th>
                <th scope="col"></th>
                <th scope="col">Pseudo</th>
                <th scope="col">Adresses email</th>
                <th scope="col">Rôle</th>
                <th scope="col">Statut</th>
                <th scope="col" class="text-center">Modifier</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user) :
                $createAt = new DateTime($user->getCreatedAt());
                ?>
                <tr>
                    <th scope="row" class="text-center"><?= $createAt->format('d-m-Y'); ?></th>
                    <?php if (empty($user->getPicture())) : ?>
                        <td>
                            <i class="img-fluid icon-user-dashboard fas fa-user fa-6x"></i>
                        </td>
                    <?php else: ?>
                        <td><img class="img-fluid img-user-dashboard" src="<?= $user->getPicture() ?>"></td>
                    <?php endif; ?>
                    <td><?= $user->getUsername() ?></td>
                    <td><?= $user->getEmail() ?></td>
                    <td><?php
                        switch ($user->getRole()) {
                            case 0:
                                $role = 'Banni';
                                break;
                            case 10:
                                $role = 'Visiteur';
                                break;
                            case 20:
                                $role = 'Membre';
                                break;
                            case 75:
                                $role = 'Administrateur';
                                break;
                            case 99:
                                $role = 'Super Administrateur';
                                break;
                            default:
                                $role = 'Rôle non défini';
                        } ?>
                        <?= $role ?>
                    </td>
                    <td><?php if ($user->getStatus() == 1) {
                            echo "Actif";
                        } elseif ($user->getstatus() == 0) {
                            echo "Non actif";
                        } ?></td>
                    <td>
                        <a class=" btn btn-primary" role="button"
                           href="<?= "dashboard/adminUpdateUser/" . $user->getId() ?>">Modifier</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="tableau border mb-3">
        <h2 class="text-center">Articles</h2>
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="text-center">Date de publications</th>
                <th scope="col">Titres</th>
                <th scope="col">Extraits</th>
                <th scope="col">Images</th>
                <th scope="col">Statuts</th>
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <tr><?php foreach ($articles

                as $article) :
                $createAt = new DateTime($article->created_at); ?>
                <th scope="row" class="text-center"><?= $createAt->format('d-m-Y'); ?></th>
                <td><?= $article->title ?></td>
                <td><?= $article->excerpt ?></td>
                <?php if (empty($article->picture_url)) : ?>
                    <td>
                        <a href="<?= "dashboard/pictureArticleUpload/" . $article->id ?>"
                           class="btn btn-primary text-center">Ajouter image</a>
                    </td>
                <?php else: ?>
                    <td><img class="img-fluid" src="<?= $article->picture_url ?>"></td>
                <?php endif; ?>
                <td>
                    <?php if ($article->publish == 1) {
                        echo "publié";
                    } else {
                        echo "Non publié";
                    } ?>
                </td>
                <td>
                    <a class=" btn btn-primary" role="button" href="<?= "dashboard/updateArticle/" . $article->id ?>">Modifier</a>
                </td>
                <td>
                    <a class=" btn btn-danger" role="button" href="<?= "dashboard/deleteArticle/" . $article->id ?>">Supprimer</a>
                </td>
            </tr><?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-center mb-3">
            <a class=" btn btn-primary" role="button" href="dashboard/createArticle">Créer un nouvel article</a>
        </div>
    </div>
    <div class="border">
        <?php if (empty($comments)) : ?>
            <div>
                <h2 class="text-center">Aucun commentaire en attentes</h2>
            </div>
        <?php else: ?>
            <div>
                <h2 class="text-center">Commentaires en attentes</h2>
                <?php if (isset($_SESSION['flash'])) : ?>
                    <p class="text-center font-weight-bold text-success alert">
                        <?= $_SESSION['flash']['infos']; ?></p>
                <?php endif; ?>
            </div>
            <table class="table comments mb-0">
                <thead>
                <tr>
                    <th scope="col">Date de soumission</th>
                    <th scope="col">Écrit par</th>
                    <th scope="col">Contenu</th>
                    <th scope="col">Article</th>
                    <th scope="col">Valider</th>
                    <th scope="col">Supprimer</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border">
                    <?php foreach ($comments

                    as $comment) :
                    $createAt = new DateTime($comment->created_at); ?>

                    <th scope="row"><?= $createAt->format('d-m-Y'); ?></th>
                    <td><?= $comment->username ?></td>
                    <td><?= $comment->content ?></td>
                    <td><?= $comment->title ?></td>
                    <td>
                        <a class=" btn btn-primary" role="button"
                           href="<?= "dashboard/validComment/" . $comment->id ?>">Valider</a>
                    </td>
                    <td>
                        <a class=" btn btn-danger" role="button"
                           href="<?= "dashboard/deleteComment/" . $comment->id ?>">Supprimer</a>
                    </td>
                </tr><?php endforeach; ?>
                </tbody>
            </table>
        <? endif; ?>
    </div>
<?php else : ?>
    <form method="post">
        <div class="border rounded p-3 pb-5 col-12">
            <div class="row p-2 m-2">
                <?php if (!empty($user->getPicture())) : ?>
                    <div class="border rounded p-2 col-3">
                        <div class="d-flex justify-content-center border p-2">
                            <img class="img-fluid" src="<?= $user->getPicture() ?>">
                        </div>
                        <div class="border col mt-3 p-2">
                            <a class="btn btn-primary" role="button"
                               href="<?= "dashboard/pictureUpload/" . $user->getId() ?>">
                                Modifier la photo de profil</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="border rounded p-2 col-3">
                        <div class="d-flex justify-content-center border p-2">
                            <i class="fas fa-user fa-10x"></i>
                        </div>
                        <?php if (isset($_SESSION['auth']) && $_SESSION['auth']['role'] >= '20') : ?>

                            <div class="border col mt-3 p-2">
                                <a class="btn btn-primary" role="button"
                                   href="<?= "dashboard/pictureUpload/" . $user->getId() ?>">
                                    Ajouter
                                    une photo de profil</a>
                            </div>
                        <? endif; ?>
                    </div>
                <?php endif; ?>
                <div class="col">
                    <div class="row p-3">
                        <div class="col text-right">
                            <?php $createdAt = new DateTime($user->getCreatedAt()); ?>
                            <p>Date d'inscription : <?= $createdAt->format('d/m/Y H:i:s') ?></p>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col">
                            <label for="username">Nom d'utilisateur :</label>
                            <input class="form-control" type="text" id="username" name="username"
                                   value="<?= isset($post['username']) ? $post['username
                       '] : $user->getUsername(); ?> "
                                   <?php if (isset($_SESSION['auth']) && $_SESSION['auth']['role'] <= '10') : ?>readonly<? endif; ?>>
                            <p class="text-danger"><?= isset($errorsMsg['username']) ? $errorsMsg['username'] : ''; ?></p>
                        </div>
                        <div class="col">
                            <label for="email">Adresse email :</label>
                            <input class="form-control" type="email" id="email" name="email"
                                   value="<?= isset($post['email']) ? $post['email
                       '] : $user->getEmail(); ?>"
                                   <?php if (isset($_SESSION['auth']) && $_SESSION['auth']['role'] <= '10') : ?>readonly<? endif; ?>>

                            <p class="text-danger"><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>

                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col-6">
                            <label for="role">Rôle :</label>
                            <input class="form-control" name="role" id="role"
                                   value="<?php
                                   switch ($user->getRole()) {
                                       case 0:
                                           $role = 'Banni';
                                           break;
                                       case 10:
                                           $role = 'Visiteur';
                                           break;
                                       case 20:
                                           $role = 'Membre';
                                           break;
                                       case 75:
                                           $role = 'Administrateur';
                                           break;
                                       case 99:
                                           $role = 'Super Administrateur';
                                           break;
                                       default:
                                           $role = 'Rôle non défini';
                                   } ?><?= $role ?>" readonly/>
                        </div>
                        <?php if (isset($_SESSION['auth']) && $_SESSION['auth']['role'] >= '20') : ?>
                            <div class="col-6 text-center pt-4">
                                <a class="btn btn-primary" role="button"
                                   href="<?= "dashboard/updatePasswordUser/" . $user->getId() ?>">Modifier le mot de
                                    passe</a>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['auth']) && $_SESSION['auth']['role'] >= '20') : ?>
                <div class="row mt-3">
                    <div class="col text-right">
                        <input type="hidden" name="userForm" value="updateUser"/>
                        <input class="btn btn-primary" type="submit" name="saveUpdate"
                               value="Enregistrer les modifications">
                    </div>
                </div>
            <? endif; ?>
        </div>
    </form>
    <div class="commentsConfirmed">
        <h2>Mes commentaires postés</h2>
        <?php if (empty($confirmed)) : ?>
            <div class="border">
                <h3 class="text-center">Aucun commentaire posté</h3>
            </div>
        <?php else: ?>
            <?php foreach ($confirmed as $comment) : ?>
                <div class="border rounded p-2 mb-2">
                    <?php $date = new DateTime($comment->getCreatedAt()); ?>
                    <p class="m-0 pb-3">Posté le : <?= $date->format('d-m-Y'); ?> à <?= $date->format('h:i:s'); ?></p>
                    <div class="border">
                        <?php if (empty($user->getPicture())) : ?>
                            <div class="col d-flex">
                                <div class="col-1 mt-2 mb-2">
                                    <i class="fas fa-user fa-3x img-thumbnail"></i>
                                </div>
                                <div class="col-11 d-flex align-items-center">
                                <span><u><?= $user->getUsername() ?></u> Dit
                                : <?= $comment->getContent() ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col d-flex">
                                <div class="col-1 mt-2 mb-2">
                                    <img class="img-fluid img-thumbnail rounded-circle"
                                         src="<?= $user->getPicture() ?>">
                                </div>
                                <div class="col-11 d-flex align-items-center">
                                    <p class=""><u><?= $user->getUsername() ?></u> Dit
                                        : <?= $comment->getContent() ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>


                    <div class="input d-flex justify-content-end">
                        <a class=" btn-link text-success" role="button"
                           href="<?= "articles/read/" . $comment->getArticleId() ?>">Lien vers l'article</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="commentsReported">
        <h2>Mes commentaires signalés</h2>

        <?php if (empty($reported)) : ?>
            <div class="border">
                <h3 class="text-center">Aucun commentaire signalé</h3>
            </div>
        <?php else: ?>
            <?php foreach ($reported as $comment) : ?>
                <div class="border rounded p-2 mb-2">
                    <?php $date = new DateTime($comment->getCreatedAt()); ?>
                    <p class="m-0 pb-3">Posté le : <?= $date->format('d-m-Y'); ?> à <?= $date->format('h:i:s'); ?></p>
                    <div class="border">
                        <?php if (empty($user->getPicture())) : ?>
                            <div class="col d-flex">
                                <div class="col-1 mt-2 mb-2">
                                    <i class="fas fa-user fa-3x img-thumbnail"></i>
                                </div>
                                <div class="col-11 d-flex align-items-center">
                                <span><u><?= $user->getUsername() ?></u> Dit
                                : <?= $comment->getContent() ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col d-flex">
                                <div class="col-1 mt-2 mb-2">
                                    <img class="img-fluid img-thumbnail rounded-circle"
                                         src="<?= $user->getPicture() ?>">
                                </div>
                                <div class="col-11 d-flex align-items-center">
                                    <p class=""><u><?= $user->getUsername() ?></u> Dit
                                        : <?= $comment->getContent() ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input d-flex justify-content-end">
                        <a class=" btn-link text-success" role="button"
                           href="<?= "articles/read/" . $comment->getArticleId() ?>">Lien vers l'article</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php unset($_SESSION['flash']); ?>

