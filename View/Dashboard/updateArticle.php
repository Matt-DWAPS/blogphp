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

    <?php if (empty($articles->getPicture())) : ?>
        <div class="form-group border rounded p-2 col-2 ">
            <label for="picture_url">Image</label><br/>
            <a class="btn btn-primary d-flex align-items-center" role="button"
               href="<?= "dashboard/pictureArticleUpload/" . $articles->getId() ?>">
                Ajouter
                une image</a>
        </div>
    <?php else : ?>
        <div class="col-12 d-flex justify-content-center">
            <div class="border rounded col-6">
                <div class="form-group  p-2  justify-content-center d-flex">
                    <img class="img-fluid" src="<?= $articles->getPicture() ?>">

                </div>
                <div class="row justify-content-center mb-3">
                    <a class="btn btn-primary d-flex align-items-center" role="button"
                       href="<?= "dashboard/pictureArticleUpload/" . $articles->getId() ?>">
                        Modifier
                        l'image</a>
                </div>
            </div>

        </div>
    <?php endif; ?>
    <div class="row mt-3 d-flex justify-content-around">
        <div>
            <a class=" btn btn-danger" role="button" href="dashboard/"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>
        <div>
            <input type="hidden" name="articleForm" value="updateArticle"/>
            <input class="btn btn-primary" type="submit" value="Enregistrer en tant que brouillon"/>
        </div>
        <div>
            <input class="btn btn-primary" type="submit" name="publish" value="Mettre en ligne">
        </div>

    </div>

</form>
