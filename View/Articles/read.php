<?php $this->title = "Article"; ?>

<h2 class="post-title p-5" id="contenu">Article</h2>
<div>
    <div class="border p-2 mb-3">
        <?php if (!empty($articles->picture_url)) : ?>
            <div class="m-2 justify-content-center d-flex">
                <img src="<?= $articles->picture_url ?>">
            </div>
        <?php endif; ?>
        <h1 class="title pb-3 p-2"><i><?= $articles->title ?></i></h1>
        <p class="text-justify pb-2 p-4"><?= $articles->content ?></p>
        <div class="col">
            <p class="font-weight-bold">Publié le <?= $articles->created_at ?></p>
        </div>
    </div>
    <?php if (isset($_SESSION['auth'])) : ?>
        <div>
            <form method="post" class="border p-2">


                <?php if (empty($_SESSION['auth']['picture'])) : ?>
                    <div class="col d-flex">
                        <div class="col-1 mt-2 mb-2">
                            <i class="fas fa-user fa-3x img-thumbnail"></i>
                        </div>
                        <div class="col-11 d-flex align-items-center">
                            <label for="username"> <?php $_SESSION['auth']['username']; ?>
                            </label>
                            <p><?php ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col d-flex">
                        <div class="col-1 mt-2 mb-2">
                            <img class="img-fluid img-thumbnail rounded-circle"
                                 src="<?= $_SESSION['auth']['picture'] ?>">
                        </div>
                        <div class="col-11 d-flex align-items-center">
                            <label for="username"> <a
                                        href="<?= "dashboard/adminUpdateUser/" . $_SESSION['auth']['id'] ?>"><?php echo($_SESSION['auth']['username']); ?></a>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="content">Contenu du commentaire :</label>
                    <textarea class="form-control input-lg" rows="4" id="content" name="content"
                              aria-label="content"><?= isset($post['content']) ? $post['content'] : ''; ?></textarea>
                    <p class="text-danger"><?= isset($errorsMsg['content']) ? $errorsMsg['content'] : ''; ?></p>
                </div>
                <input type="hidden" name="commentForm" value="addComment">
                <input class="btn btn-primary" name="publish" type="submit" value="Envoyer">
            </form>
        </div>
    <?php else : ?>
        <div class="border p-2 text-center">
            <h3 class="text-center">Identifier vous pour pouvoir posté un commentaire, merci.</h3>
            <a href="/home/login">Connexion</a> - <a href="/home/registration">Inscription</a>
        </div>
    <?php endif; ?>
    <div>
        <h2 class="text-center pb-3"><i><u>Commentaires</u></i></h2>
        <?php if (isset($_SESSION['flash'])) : ?>
            <div class="d-flex justify-content-center font-weight-bold">
                <p class="text-success alert alert-<?= $_SESSION['flash']['alert']; ?>">
                    <?= $_SESSION['flash']['message']; ?></p>
            </div>

        <?php endif; ?>
        <?php unset($_SESSION['flash']); ?>

        <?php foreach ($comments as $comment) : ?>
            <div class="border rounded p-2 mb-2">
                <?php $date = new DateTime($comment->date); ?>
                <p class="m-0 pb-3">Posté le : <?= $date->format('d-m-Y'); ?> à <?= $date->format('h:i:s'); ?></p>
                <div class="border">
                    <?php if (empty($comment->picture)) : ?>
                        <div class="col d-flex">
                            <div class="col-1 mt-2 mb-2">
                                <i class="fas fa-user fa-3x img-thumbnail"></i>
                            </div>
                            <div class="col-11 d-flex align-items-center">
                                <span><u><?= $comment->username ?></u> Dit
                                : <?= $comment->content ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col d-flex">
                            <div class="col-1 mt-2 mb-2">
                                <img class="img-fluid img-thumbnail rounded-circle" src="<?= $comment->picture ?>">
                            </div>
                            <div class="col-11 d-flex align-items-center">
                                <p class=""><u><?= $comment->username ?></u> Dit
                                    : <?= $comment->content ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <?php if (isset($_SESSION['auth'])) : ?>
                    <div class="input d-flex justify-content-end">
                        <a class=" btn-link text-danger" role="button"
                           href="<?= "articles/reportComment/" . $comment->id ?>">Signaler
                            le commentaire</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

