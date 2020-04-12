<?php $this->title = "Mon Blog - "; ?>

<h2 class="post-title" id="contenu">Articles</h2>
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['alert']; ?>">
        <p><?= $_SESSION['flash']['message']; ?></p>
    </div>
<?php endif; ?>
<?php unset($_SESSION['flash']); ?>
<?php foreach ($articles as $article) : ?>

    <?php if (empty($article->picture_url)) : ?>
        <div class="border m-2 p-2 row">
            <div class="d-flex">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div>
                        <a href="<?= "/articles/read/" . $article->id ?>">
                            <h3 class="title"><?= $article->title; ?></h3>
                        </a>
                        <p><?= $article->content; ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="border m-2 p-2 row">

            <div class="d-flex">
                <div class="col-6">
                    <a href="<?= "/articles/read/" . $article->id ?>">

                        <img class="img-fluid img-thumbnail" src="<?= $article->picture_url ?>">
                    </a>
                </div>
                <div class="col-9 d-flex align-items-center justify-content-center">
                    <div>
                        <a href="<?= "/articles/read/" . $article->id ?>">
                            <h3 class="title"><?= $article->title; ?></h3>
                        </a>
                        <p><?= $article->content; ?></p>
                    </div>

                </div>
            </div>

        </div>
    <?php endif; ?>
<?php endforeach; ?>

