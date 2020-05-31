<?php $this->title = "Mon Blog JF"; ?>

<h2 class="post-title" id="contenu">Page d'accueil</h2>
<div class="d-flex">
    <section class="table-left col-8 border">
        <h2 class="border mt-2 text-center">Les derniers articles</h2>
        <?php foreach ($articles as $article) : ?>
            <?php if (empty($article->picture_url)) : ?>
                <div class="border m-2 p-2 row">
                    <div class="d-flex">
                        <div class="col-12 d-flex align-items-center">
                            <div>
                                <a href="<?= "/articles/read/" . $article->id ?>">
                                    <h3 class="title"><?= $article->title; ?></h3>
                                </a>
                                <p><?= $article->excerpt; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="border m-2 p-2">
                    <div class="d-flex">
                        <div class="col-7 col-md-5">
                            <a href="<?= "/articles/read/" . $article->id ?>">

                                <img alt="picture article" class="img-fluid img-thumbnail"
                                     src="<?= $article->picture_url ?>">
                            </a>
                        </div>
                        <div class="col-5 col-md-6 d-flex align-items-center ">
                            <div>
                                <a href="<?= "/articles/read/" . $article->id ?>">
                                    <h3 class="title"><?= $article->title; ?></h3>
                                </a>
                                <p><?= $article->excerpt; ?></p>
                            </div>

                        </div>
                    </div>

                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
    <section class="border">
        <div class="col-12 p-3">
            <h2 class="border text-center">La vie n'est qu'une histoire... Voici la mienne</h2>
            <img class="text- pr-3 img-home" src="content/img/home.jpg"
                 alt="img-presentation"/>
            <p>Mox dicta finierat, multitudo omnis ad, quae imperator voluit, promptior laudato consilio consensit in
                pacem ea ratione maxime percita, quod norat expeditionibus crebris fortunam eius in malis tantum
                civilibus vigilasse, cum autem bella moverentur externa, accidisse plerumque luctuosa, icto post haec
                foedere gentium ritu perfectaque sollemnitate imperator Mediolanum ad hiberna discessit.

                Sin autem ad adulescentiam perduxissent, dirimi tamen interdum contentione vel uxoriae condicionis vel
                commodi alicuius, quod idem adipisci uterque non posset. Quod si qui longius in amicitia provecti
                essent, tamen saepe labefactari, si in honoris contentionem incidissent; pestem enim nullam maiorem esse
                amicitiis quam in plerisque pecuniae cupiditatem, in optimis quibusque honoris certamen et gloriae; ex
                quo inimicitias maximas saepe inter amicissimos exstitisse.
            </p>
        </div>
    </section>
</div>


