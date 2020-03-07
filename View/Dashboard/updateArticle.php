<?php $this->title = "Modifier l'article"; ?>

<h2 class="post-title" id="contenu">Modifier un article</h2>
<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title input-lg">Titre de l'article</label>
        <input class="form-control input-lg" type="text" id="title" name="title"
               placeholder="Modifier le titre de l'article : "
               value="<?= $articles->getTitle(); ?>"/>
        <p style="color: red"><?= isset($errorsMsg['title']) ? $errorsMsg['title'] : ''; ?></p>
    </div>
    <br/>
    <div class="form-group">
        <label for="content inputlg">Modifier le contenu de l'article</label>
        <textarea aria-label="content" class="form-control input-lg" rows="5" id="content"
                  name="content"><?= $articles->getContent(); ?></textarea>
        <p class="text-danger"><?= isset($errorsMsg['content']) ? $errorsMsg['content'] : ''; ?></p>
    </div>
    <div class="form-group">
        <label for="excerpt inputlg">Modifier l'extrait de l'article</label>
        <textarea aria-label="excerpt" class="form-control input-lg" rows="2" id="excerpt"
                  name="excerpt"><?= $articles->getExcerpt(); ?></textarea>
        <p class="text-danger"><?= isset($errorsMsg['excerpt']) ? $errorsMsg['excerpt'] : ''; ?></p>
    </div>
    <div class="form-group border rounded p-2 col-5 ">
        <label for="picture_url">Ajouter une image</label><br/>
        <input type="file" size="30" class="btn btn-primary" id="picture_url" name="picture_url"
               accept="image/png, image/jpeg, image/jpg"
               value=" <?= $articles->getPictureUrl(); ?>"/>


        <p class="text-danger"><?= isset($errorsMsg['picture_url']) ? $errorsMsg['picture_url'] : ''; ?></p>
    </div>

    <div class=" mt-3 d-flex justify-content-around">
        <div class="col-3">
            <a class=" btn btn-danger" role="button" href="dashboard/"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>
        <div class="col-4">
            <input type="hidden" name="articleForm" value="updateArticle"/>
            <input class="btn btn-primary" type="submit" value="Enregistrer en tant que brouillon"/>
        </div>
        <div class="col-3 d-flex justify-content-end">
            <input class="btn btn-primary" type="submit" name="publish" value="Mettre en ligne">
        </div>

    </div>

</form>
