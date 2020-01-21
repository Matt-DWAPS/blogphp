<?php $this->title = "Modifier l'article"; ?>

<h2 class="post-title" id="contenu">Modifier un article</h2>
<form method="post">
    <div class="form-group">
        <label for="title inputlg">Titre de l'article</label>
        <input class="form-control input-lg" type="text" id="title" name="title"
               placeholder="Modifier le titre de l'article : "
               value="<?= $articles->title, isset($post['title']) ? $post['title'] : ''; ?>"/>
        <p style="color: red"><?= isset($errorsMsg['title']) ? $errorsMsg['title'] : ''; ?></p>
    </div>
    <br/>
    <div class="form-group">
        <label for="content inputlg comment">Modifier le contenu de l'article</label>
        <textarea aria-label="content" class="form-control input-lg" rows="5" id="content"
                  name="content"><?= $articles->content ?></textarea>
        <p style="color: red"><?= isset($errorsMsg['content']) ? $errorsMsg['content'] : ''; ?></p>
    </div>
    <div class="form-group">
        <label for="excerpt inputlg comment">Modifier l'extrait de l'article</label>
        <textarea aria-label="excerpt" class="form-control input-lg" rows="2" id="excerpt"
                  name="excerpt"><?= $articles->excerpt ?></textarea>
        <p style="color: red"><?= isset($errorsMsg['excerpt']) ? $errorsMsg['excerpt'] : ''; ?></p>
    </div>
    <div class="form-group">
        <label for="picture_url">Ajouter une image</label><br/>
        <input type="file" class="btn" id="picture_url" name="picture_url"
               accept="image/png, image/jpeg, image/jpg"
               value="<?= $articles->picture_url, isset($post['picture_url']) ? $post['picture_url'] : ''; ?>">
    </div>
    <input type="hidden" name="no_publish" value="rough_draft"/>
    <input class="btn btn-primary" type="submit" id="no_publish" value="Enregistrer en tant que brouillon"/>

    <input type="hidden" name="articleForm" value="updateArticle">
    <input class="btn btn-primary" type="submit" id="submit" value="Mettre en ligne">
</form>
