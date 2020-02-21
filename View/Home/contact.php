<?php $this->title = "Contact"; ?>
<h2 class="post-title" id="contenu">Contactez-nous</h2>
<form method="post">
    <div class="form-group">
        <label for="name input-lg">Formulaire de contact</label>
        <input class="form-control input-lg" type="text" id="name" name="name"
               placeholder="Nom :" value=""/>
        <p style="color: red"><?= isset($errorsMsg['name']) ? $errorsMsg['name'] : ''; ?></p>
    </div>
</form>
<p>bienvenue </p>
