<?php $this->title = "Uploader une image"; ?>

<h2 class="post-title" id="contenu">Upload image de l'article</h2>

<form method="post" enctype="multipart/form-data">
    <div class="col border rounded p-3 col-12">
        <label for="picture">Ajouter/modifier une image</label><br/>
        <div class="row p-3"><input type="file" class="btn btn-primary mr-3" id="picture" name="picture"
                                    value="<?= $article->picture, isset($post['picture']) ? $post['picture'] : ''; ?>">
            <input type="hidden" name="pictureUpload" value="upload"/>
            <input
                    class="btn btn-primary" type="submit"
                    name="submit"
                    value="Télécharger"></div>
        <p class="text-danger"><?= isset($errorsMsg['picture']) ? $errorsMsg['picture'] : ''; ?></p>
        <p><strong>Note:</strong> Seuls les formats .jpg, .jpeg, .jpeg, .gif, .png sont autorisés jusqu'à une taille
            maximale de 5 Mo.</p>

    </div>
</form>