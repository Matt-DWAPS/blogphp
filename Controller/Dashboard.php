<?php

require_once 'Framework/Controller.php';
require_once 'Model/User.php';
require_once 'Model/Article.php';
require_once 'Services/Validator.php';

class Dashboard extends Controller
{

    const PUBLISH = [
        'PUBLIÉ' => 1,
        'BROUILLON' => 0
    ];

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

        $this->generateView([
            'articles' => $articles,
            'users' => $users,
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
                        if ($post['no_publish'] == 'rough_draft') {
                            $article->setPublish(0);
                        } else {
                            $article->setPublish(1);
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
        $article->setId($_GET['id']);
        $articles = $article->getOneArticle($_GET['id']);


        $article->deleteArticle($_GET['id']);
        header('Location: /dashboard');
        exit;


    }

    public function updateArticle()
    {
        $article = new Article();
        $post = isset($_POST) ? $_POST : false;

        $article->setId($_GET['id']);
        $articles = $article->getOneArticle($_GET['id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['articleForm'] == 'updateArticle') {
                $article->setTitle($post['title']);
                $article->setContent($post['content']);
                $article->setPictureUrl($post['picture_url']);
                $article->setExcerpt($post['excerpt']);
                if ($article->formArticleUpdateValidate()) {
                    $dateNow = new DateTime();
                    $article->setCreatedAt($dateNow->format('Y-m-d H:i:s'));
                    if ($post['no_publish'] == 'rough_draft') {
                        $article->setPublish(0);
                    } else {
                        $article->setPublish(1);
                    }
                    $article->setUserId($_SESSION['auth']['id']); // ici tu dois récupérer l'id de l'admin dans $_SESSION...
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

    public function disconnected()
    {
        unset($_SESSION['auth']);
        $_SESSION['flash']['alert'] = "success";
        $_SESSION['flash']['message'] = "Vous êtes déconnecté.";
        header('Location: /home');
        exit;
    }

}