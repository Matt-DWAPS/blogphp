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
                       placeholder="Nom : *" value="<?= isset($post['name']) ? $post['name'] : ''; ?>"/>
                <p class="text-danger"><?= isset($errorsMsg['name']) ? $errorsMsg['name'] : ''; ?></p>
            </div>
            <div class="form-group">
                <input class="form-control " type="text" id="email" name="email"
                       placeholder="Entrer votre adresse e-mail : *"
                       value="<?= isset($post['email']) ? $post['email'] : ''; ?>"/>
                <p class="text-danger"><?= isset($errorsMsg['email']) ? $errorsMsg['email'] : ''; ?></p>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" id="subject" name="subject"
                       placeholder="Sujet : *" value="<?= isset($post['subject']) ? $post['subject'] : ''; ?>"/>
                <p class="text-danger"><?= isset($errorsMsg['subject']) ? $errorsMsg['subject'] : ''; ?></p>
            </div>
            <div class="form-group">
                <textarea class="form-control input-lg" rows="4" id="content" name="content" placeholder="Message : *"
                          aria-label="content"><?= isset($post['content']) ? $post['content'] : ''; ?></textarea>
                <p class="text-danger"><?= isset($errorsMsg['content']) ? $errorsMsg['content'] : ''; ?></p>
            </div>
            <br/>
            <input type="hidden" name="sendForm" value="send">
            <input class="btn btn-primary" type="submit" id="submit" value="Envoyer">
            <p class="text-danger"><?php echo $errorMsg ?></p>
            <?php if (isset($_SESSION['flash'])) : ?>
                <p class="text-center font-weight-bold text-success alert alert-<?= $_SESSION['flash']['alert']; ?>">
                    <?= $_SESSION['flash']['message']; ?></p>
            <?php endif; ?>
            <?php unset($_SESSION['flash']); ?>
    </div>
    </form>
</div>

</div>

