<?php

require_once 'Framework/Controller.php';
require_once 'Model/Article.php';
require_once 'Model/User.php';
require_once 'Model/Comment.php';
require_once 'Services/Validator.php';

class Articles extends Controller
{

    public function index()
    {
        $article = new Article();
        $articles = $article->getAllArticles(self::PUBLISH['PUBLIÉ']);
        $this->generateView([
            'articles' => $articles,
        ]);
    }


    /**
     * @throws Exception
     */
    public function read()
    {
        $articleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $post = isset($_POST) ? $_POST : false;
        $article = new Article();
        $comment = new Comment();
        //$comment->setArticleId($articleId);
        //$comment->setId(filter_input(INPUT_GET, 'id'));
        $comments = $comment->getComments($articleId, self::COMMENT_STATUS['PUBLIÉ']);


        //$article->setId(filter_input(INPUT_GET, 'id'));
        $articles = $article->getOneArticle($articleId);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['commentForm'] == 'addComment') {
                $comment->setContent($post['content']);
                $comment->setUserId($post['user_id']);
                if ($comment->checkCommentValidate()) {
                    $dateNow = new DateTime();
                    $comment->setCreatedAt($dateNow->format('Y-m-d H:i:s'));
                    if (isset($post['publish'])) {
                        $comment->setStatus(self::COMMENT_STATUS['PUBLIÉ']);
                    } else {
                        $_SESSION['flash']['alert'] = "danger";
                        $_SESSION['flash']['message'] = "Une erreur est survenue, veuillez ressayer ulterieurement";
                        header('Location: /articles');
                        exit;
                    }
                    $comment->setUserId($_SESSION['auth']['id']);
                    $comment->setArticleId($articleId);
                    $comment->save();
                    $_SESSION['flash']['alert'] = "success";
                    $_SESSION['flash']['message'] = "Merci pour votre commentaire";
                    header('Location: /articles/read/' . $articleId);
                    exit;
                } else {
                    $_SESSION['flash']['alert'] = "danger";
                    $_SESSION['flash']['message'] = "Un problème est survenue, veuillez verifier les champs";
                }
            } else {
                $_SESSION['flash']['alert'] = "danger";
                $_SESSION['flash']['message'] = "Un problème est survenue, ressayer ulterieurement";
            }
        }
        $this->generateView([
            'articles' => $articles,
            'comments' => $comments,
            'post' => $post,
            'errorsMsg' => $comment->getErrorsMsg(),
        ]);
    }

    public function reportComment()
    {
        $commentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $comment = new Comment();
        $comment->getComment($commentId);

        $comment->setStatus(self::COMMENT_STATUS['EN ATTENTE']);
        $comment->updateComment($comment->getStatus());

        $comment->setArticleId($comment->getArticleId());


        $_SESSION['flash']['alert'] = "Success";
        $_SESSION['flash']['message'] = "Nous vous remercions pour le signalement de ce commentaire, il sera examiné prochainement";
        header('Location: /articles/read/' . $comment->getArticleId());
        exit;

    }
}