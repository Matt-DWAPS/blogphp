<?php $this->title = "Mon Blog - "; ?>

<h2 class="post-title" id="contenu">Articles</h2>
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['alert']; ?>">
        <p><?= $_SESSION['flash']['message']; ?></p>
    </div>
<?php endif; ?>
<?php unset($_SESSION['flash']); ?>
<?php foreach ($articles as $article) : ?>
    <a href="<?= "/articles/read/" . $article->id ?>">
        <h3 class="title"><?= $article->title; ?></h3>
    </a>
    <p><?= $article->content; ?></p>
<?php endforeach; ?>

