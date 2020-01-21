<?php $this->title = "Espace membre"; ?>

<h2 class="post-title" id="contenu">Mon Compte</h2>
<div class="tableau">
    <table class="table">
        <thead>
        <h2 class="text-center">Infos Utilisateurs</h2>
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
        <tr><?php foreach ($users

            as $user) : ?>
            <th scope="row" class="text-center"><?= $user->created_at ?></th>
            <td><?= $user->username ?></td>
            <td><?= $user->email ?></td>
            <td>
                <?php if ($user->role == 20) {
                    echo "Membre";
                } elseif ($user->role == 99) {
                    echo "Super Administrateur";
                } elseif ($user->role == 10) {
                    echo "Visiteur";
                } elseif ($user->role == 0) {
                    echo "Banni";
                } elseif ($user->role == 75) {
                    echo "Administrateur";
                } ?>
            </td>
            <td><?php if ($user->status == 1) {
                    echo "Actif";
                } elseif ($user->status == 0) {
                    echo "Non actif";
                } ?></td>
            <td>
                <form class=" row justify-content-center " method="post" action="index.php">
                    <input class="btn btn-primary" type="submit" value="Modifier">
                </form>
            </td>
        </tr><?php endforeach; ?>
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <th scope="row"></th>
        </tr>
        </tbody>

    </table>
</div>
<div class="tableau">
    <table class="table">
        <thead>
        <h2 class="text-center">Articles</h2>
        <tr>
            <th scope="col" class="text-center">Date de publications</th>
            <th scope="col">Titres</th>
            <th scope="col">Extraits</th>
            <th scope="col">Images</th>
            <th scope="col">Statuts</th>
            <th scope="col">Modifier</th>
            <th scope="col" class="text-center">Supprimer</th>
        </tr>
        </thead>
        <tbody>
        <tr><?php foreach ($articles

            as $article) : ?>
            <th scope="row" class="text-center"><?= $article->created_at ?></th>
            <td><?= $article->title ?></td>
            <td><?= $article->excerpt ?></td>
            <td><?= $article->picture_url ?></td>
            <td>
                <?php if ($article->publish == 1) {
                    echo "publié";
                } else {
                    echo "Non publié";
                } ?>
            </td>
            <td>
                <a class=" btn btn-primary" role="button" href="<?= "dashboard/updateArticle" . $article->id ?>">Modifier</a>
            </td>
            <td>
                <a class=" btn btn-primary" role="button" href="<?= "dashboard/deleteArticle" . $article->id ?>">Supprimer</a>
            </td>
        </tr><?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="text-center">
    <a class=" btn btn-primary" role="button" href="dashboard/createArticle">Créer un nouvel article</a>
</div>
