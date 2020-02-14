<?php

require_once 'Framework/Controller.php';
require_once 'Model/User.php';
require_once 'Model/Article.php';
require_once 'Model/Comment.php';
require_once 'Services/Validator.php';

class Dashboard extends Controller
{


    public function index()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /home');
            exit();
        }
        $user = new User();
        $users = $user->getAllUserDashboard();
        $article = new Article();
        $articles = $article->getAllArticles();
        $comment = new Comment();
        $comments = $comment->getPendingComments(self::COMMENT_STATUS['EN ATTENTE']);
        $this->generateView([
            'articles' => $articles,
            'users' => $users,
            'comments' => $comments,
        ]);
    }

    /**
     * @throws Exception
     */
    public function updateUser()
    {
        $roles = self::ROLES;
        $user_status = self::STATUS;

        $user = new User();
        $post = isset($_POST) ? $_POST : false;

        $user->setId(filter_input(INPUT_GET, 'id'));
        $users = $user->getUser($user->getId());
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        }
        $this->generateView([
            'users' => $users,
            'roles' => $roles,
            'user_status' => $user_status,
        ]);
    }

    public function createArticle()
    {
        $post = isset($_POST) ? $_POST : false;
        $article = new Article();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['articleForm'] == 'addArticle') {
                $article->setTitle($post['title']);
                $article->setContent($post['content']);
                $article->setPictureUrl($post['picture_url']);
                $article->setExcerpt($post['excerpt']);
                if ($article->formArticleValidate()) {
                    if ($article->articleValidate()) {
                        $dateNow = new DateTime();
                        $article->setCreatedAt($dateNow->format('Y-m-d H:i:s'));
                        if (isset($post['publish'])) {
                            $article->setPublish(self::PUBLISH['PUBLIÉ']);
                        } else {
                            $article->setPublish(self::PUBLISH['BROUILLON']);
                        }
                        $article->setUserId($_SESSION['auth']['id']);
                        $article->save();
                        header('Location: /dashboard');
                        exit;
                    } else {
                        $_SESSION['flash']['alert'] = "danger";
                        $_SESSION['flash']['message'] = "Un problème est survenue, ressayer ulterieurement";
                    }
                } else {
                    $_SESSION['flash']['alert'] = "danger";
                    $_SESSION['flash']['message'] = "Veuillez verifier les champs obligatoires";
                }
            }
        }
        $this->generateView([
            'errorsMsg' => $article->getErrorsMsg(),
            'post' => $post
        ]);
    }

    public function deleteArticle()
    {
        $article = new Article();
        $article->setId(filter_input(INPUT_GET, 'id'));
        $article->deleteArticle($article->getId());
        header('Location: /dashboard');
        exit;
    }

    /**
     * @throws Exception
     */
    public function updateArticle()
    {
        $article = new Article();
        $post = isset($_POST) ? $_POST : false;

        $article->setId(filter_input(INPUT_GET, 'id'));
        $articles = $article->getOneArticle($article->getId());
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['articleForm'] == 'updateArticle') {
                $article->setTitle($post['title']);
                $article->setContent($post['content']);
                $article->setPictureUrl($post['picture_url']);
                $article->setExcerpt($post['excerpt']);
                if ($article->formArticleUpdateValidate()) {
                    $dateNow = new DateTime();
                    $article->setCreatedAt($dateNow->format('Y-m-d H:i:s'));
                    if (isset($post['publish'])) {
                        $article->setPublish(1);
                    } else {
                        $article->setPublish(0);
                    }
                    $article->setUserId($_SESSION['auth']['id']);
                    $article->updateArticle();
                    header('Location: /dashboard');
                    exit;
                } else {
                    $_SESSION['flash']['alert'] = "danger";
                    $_SESSION['flash']['message'] = "Veuillez verifier les champs obligatoires";
                }
            }
        }
        $this->generateView([
            'articles' => $articles,
            'errorsMsg' => $article->getErrorsMsg(),
        ]);
    }

    public function validComment()
    {
        $comment = new Comment();
        $comment->setId(filter_input(INPUT_GET, 'id'));
        $comments = $comment->getPendingComments(self::COMMENT_STATUS['EN ATTENTE']);
        $comment->updateComment(self::COMMENT_STATUS['PUBLIÉ']);
        $_SESSION['flash']['alert'] = "Success";
        $_SESSION['flash']['message'] = "Commentaire publié !";
        header('Location: /dashboard');
        exit;
    }

    public function deleteComment()
    {
        $comment = new Comment();
        $comment->setId(filter_input(INPUT_GET, 'id'));
        $comment->deleteComment($comment->getId());
        $_SESSION['flash']['alert'] = "Success";
        $_SESSION['flash']['message'] = "Commentaire supprimé !";
        header('Location: /dashboard');
        exit;
    }

    public function disconnected()
    {
        unset($_SESSION['auth']);
        $_SESSION['flash']['alert'] = "success";
        $_SESSION['flash']['message'] = "Vous êtes déconnecté.";
        header('Location: /home');
        exit;
    }

}