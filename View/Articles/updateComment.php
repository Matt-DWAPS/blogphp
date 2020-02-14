<?php $this->title = "Signalement d'un commantaire"; ?>

<h2 class="post-title p-5" id="contenu">Signalement</h2>

<?php if (isset($_SESSION['flash'])) : ?>
    <div class="d-flex justify-content-center font-weight-bold border rounded mb-3">
        <p class="text-success alert alert-<?= $_SESSION['flash']['alert']; ?>">
            <?= $_SESSION['flash']['message']; ?></p>
    </div>

<?php endif; ?>
<?php unset($_SESSION['flash']); ?>

<?php foreach ($comments as $comment) : ?>
    <div class="border rounded p-2 mb-2">
        <p class="m-0 pb-3">Posté le : <?= $comment->date ?></p>
        <p class="border p-2"><u><?= $comment->username ?></u> Dit
            : <?= $comment->content ?></p>

        <div class="input d-flex justify-content-end">
            <a class=" btn-link text-info" role="button"
               href="<?= "articles/read/" . $comment->article_id ?>"><i class="fas fa-arrow-left"></i> Retourner a la
                liste des
                articles</a>
        </div>
    </div>
<?php endforeach; ?>


