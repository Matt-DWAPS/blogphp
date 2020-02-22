<?php $this->title = "Contact"; ?>
<h2 class="post-title mt-3 mb-3" id="contenu">Contact</h2>
<div class="d-flex justify-content-around">
    <div class="col-4 d-flex border rounded">
        <h3 class="text-center d-flex align-items-center">
            Vous pouvez me laisser un message, un commentaire ou un avis en remplissant ce formulaire.
            Je répondrai au plus vite !
        </h3>
    </div>
    <div class="border rounded p-3 col-6">
        <h3>Formulaire de contact</h3>
        <form method="post">
            <div class="form-group">
                <input class="form-control" type="text" id="name" name="name"
                       placeholder="Nom : *" value=""/>
                <p style="color: red"><?= isset($errorsMsg['name']) ? $errorsMsg['name'] : ''; ?></p>
                <input class="form-control " type="text" id="email" name="email"
                       placeholder="Entrer votre adresse e-mail : *"
                       value="<?= isset($post['email']) ? $post['email'] : ''; ?>"/>
                <p class="text-danger"><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>
                <br/>
                <input class="form-control" type="text" id="subject" name="subject"
                       placeholder="Sujet : *" value=""/>
                <p style="color: red"><?= isset($errorsMsg['subject']) ? $errorsMsg['subject'] : ''; ?></p>
                <textarea class="form-control input-lg" rows="4" id="content" placeholder="Message : *"
                          aria-label="content"><?= isset($post['content']) ? $post['content'] : ''; ?></textarea>
                <br/>
                <input type="hidden" name="sendForm" value="send">
                <input class="btn btn-primary" type="submit" id="submit" value="Envoyer">
            </div>
        </form>
    </div>

</div>

