<?php $this->title = "Mon Blog JF"; ?>

<h2 class="post-title" id="contenu">Page d'accueil</h2>

<?php foreach ($articles as $article) : ?>
    <a href="">
        <h3 class="title"><?= $article->title; ?></h3>
    </a>
    <p><?= $article->excerpt; ?></p>
<?php endforeach; ?>
