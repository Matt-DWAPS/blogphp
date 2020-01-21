<?php

require_once 'Framework/Controller.php';
require_once 'Model/Article.php';

class Articles extends Controller
{
    public function index()
    {
        $article = new Article();
        $articles = $article->getAllArticles();
        $this->generateView([
            'articles' => $articles,
        ]);
    }

    public function article()
    {
        $article = new Article();
        $article->setId($_GET['id']);
        $articles = $article->getOneArticle($_GET['id']);
        $this->generateView([
            'articles' => $articles
        ]);

    }

}