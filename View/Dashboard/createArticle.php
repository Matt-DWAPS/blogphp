<?php $this->title = "Nouvel article"; ?>
<h2 class="post-title" id="contenu">Créer un nouvel article</h2>

<form method="post">
    <div class="form-group">
        <label for="title inputlg">Titre de l'article</label>
        <input class="form-control input-lg" type="text" id="title" name="title"
               value="<?= isset($post['title']) ? $post['title'] : ''; ?>"/>
        <p class="text-danger"><?= isset($errorsMsg['title']) ? $errorsMsg['title'] : ''; ?></p>
    </div>
    <br/>
    <div class="form-group">
        <label for="content inputlg comment">Contenu de l'article</label>
        <textarea aria-label="content" class="form-control input-lg" rows="5" id="content"
                  name="content"><?= isset($post['content']) ? $post['content'] : ''; ?></textarea>
        <p class="text-danger"><?= isset($errorsMsg['content']) ? $errorsMsg['content'] : ''; ?></p>
    </div>
    <div class="form-group">
        <label for="excerpt inputlg comment">Extrait de l'article</label>
        <textarea aria-label="excerpt" class="form-control input-lg" rows="2" id="excerpt"
                  name="excerpt"><?= isset($post['excerpt']) ? $post['excerpt'] : ''; ?></textarea>
        <p class="text-danger"><?= isset($errorsMsg['excerpt']) ? $errorsMsg['excerpt'] : ''; ?></p>
    </div>
    <div class=" mt-3 d-flex justify-content-around">
        <div class="col-3">
            <a class=" btn btn-danger" role="button" href="dashboard/"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>
        <div class="col-4">
            <input type="hidden" name="articleForm" value="addArticle"/>
            <input class="btn btn-primary" type="submit" value="Enregistrer en tant que brouillon"/>
        </div>
        <div class="col-3 d-flex justify-content-end">
            <input class="btn btn-primary" type="submit" name="publish" value="Mettre en ligne">
        </div>
    </div>
</form>