<?php $this->title = "Mon Blog - "; ?>

<h2 class="post-title" id="contenu">Articles</h2>

<?php foreach ($articles as $article) : ?>
    <a href="<?= "/articles/article" . $article->id ?>">
        <h3 class="title"><?= $article->title; ?></h3>
    </a>
    <p><?= $article->content; ?></p>
<?php endforeach; ?>

