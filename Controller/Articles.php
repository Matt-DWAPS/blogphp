<?php

require_once 'Framework/Controller.php';
require_once 'Model/Article.php';
require_once 'Model/User.php';
require_once 'Model/Comment.php';
require_once 'Services/Validator.php';

class Articles extends Controller
{
    /**
     * @throws Exception
     */
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
        $articles = $article->getOneArticle($articleId);
        $article->hydrate($article->getOneArticle($articleId));
        $userId = $_SESSION['auth']['id'];

        $comment = new Comment();
        $comments = $comment->getComments($article->getId(), self::COMMENT_STATUS['PUBLIÉ']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['commentForm'] == 'addComment') {
                $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
                $comment->setContent($content);
                $comment->setUserId($userId);
                if ($comment->checkCommentValidate()) {
                    $dateNow = new DateTime();
                    $comment->setCreatedAt($dateNow->format('Y-m-d H:i:s'));

                    $comment->setStatus(self::COMMENT_STATUS['PUBLIÉ']);
                    $comment->setUserId($userId);
                    $comment->setArticleId($article->getId());
                    $comment->save();
                    $_SESSION['flash']['alert'] = "success";
                    $_SESSION['flash']['message'] = "Merci pour votre commentaire";
                    header('Location: /articles/read/' . $article->getId());
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
        $article = new Article();
        $comment = new Comment();
        $commentBdd = $comment->getComment($commentId);
        if ($commentBdd) {
            $comment->hydrate($commentBdd);
        }
        $comment->setStatus(self::COMMENT_STATUS['EN ATTENTE']);
        $comment->updateComment($commentId, $comment->getStatus());
        $comment->setArticleId($comment->getArticleId());
        $articleBdd = $article->getOneArticle($comment->getArticleId());
        $article->getTitle();
        $data = [
            'title' => $articleBdd->title,
            'content' => $comment->getContent()
        ];

        $this->sendEmail('reportComment', 'Commentaire signaler', self::FROMEMAIL, $data);
        $_SESSION['flash']['alert'] = "Success";
        $_SESSION['flash']['message'] = "Nous vous remercions pour le signalement de ce commentaire, il sera examiné prochainement";
        header('Location: /articles/read/' . $comment->getArticleId());
        exit;

    }
}