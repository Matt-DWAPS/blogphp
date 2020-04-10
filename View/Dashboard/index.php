<?php $this->title = "Espace membre"; ?>

<h2 class="post-title" id="contenu">Mon Compte</h2>
<?php if (isset($_SESSION['flash'])) : ?>
    <p class="text-center font-weight-bold text-success alert alert-<?= $_SESSION['flash']['alert']; ?>">
        <?= $_SESSION['flash']['message']; ?></p>
<?php endif; ?>
<div class="tableau border mb-3">
    <h2 class="text-center">Infos Utilisateurs</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col" class="text-center">Date d'inscriptions</th>
            <th scope="col">Pseudos</th>
            <th scope="col">Adresses emails</th>
            <th scope="col">Rôles</th>
            <th scope="col">Statuts</th>
            <th scope="col" class="text-center">Modifier</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td class="text-center"><?= $user->created_at ?></td>
                <td><?= $user->username ?></td>
                <td><?= $user->email ?></td>
                <td><?php
                    switch ($user->role) {
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
                <td><?php if ($user->status == 1) {
                        echo "Actif";
                    } elseif ($user->status == 0) {
                        echo "Non actif";
                    } ?></td>
                <td>
                    <a class=" btn btn-primary" role="button"
                       href="<?= "dashboard/adminUpdateUser/" . $user->id ?>">Modifier</a>
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

            as $article) : ?>
            <th scope="row" class="text-center"><?= $article->created_at ?></th>
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
                <p class="text-center font-weight-bold text-success alert alert-<?= $_SESSION['flash']['alert']; ?>">
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

                as $comment) : ?>
                <th scope="row"><?= $comment->created_at ?></th>
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
    <?php unset($_SESSION['flash']); ?>
</div>
