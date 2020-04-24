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
        } elseif (isset($_SESSION['auth']) && $_SESSION['auth']['role'] == '20') {
            $roles = self::ROLES;
            $user_status = self::STATUS;
            $user = new User();
            $userId = $_SESSION['auth']['id'];
            $users = $user->getUser($userId);
            $user->hydrate($users);

            $comment = new Comment();
            $commentsConfirmed = $comment->getCommentsUser($userId, self::COMMENT_STATUS['PUBLIÉ']);
            $comment->hydrate($commentsConfirmed);
            $commentsReported = $comment->getCommentsUser($userId, self::COMMENT_STATUS['EN ATTENTE']);
            $comment->hydrate($commentsReported);

            $this->generateView([
                'user' => $user,
                'roles' => $roles,
                'confirmed' => $commentsConfirmed,
                'reported' => $commentsReported,
            ]);
            exit();
        }
        $user = new User();
        $users = $user->getAllUserDashboard();
        $article = new Article();
        $articles = $article->getAllArticles();
        $comment = new Comment();
        $comments = $comment->getPendingComments(self::COMMENT_STATUS['EN ATTENTE']);
        $roles = self::ROLES;


        $this->generateView([
            'articles' => $articles,
            'users' => $users,
            'comments' => $comments,
        ]);
    }

    /**
     * @throws Exception
     */
    public function adminUpdateUser()
    {
        $roles = self::ROLES;
        $user_status = self::STATUS;
        $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $user = new User();
        $post = isset($_POST) ? $_POST : false;

        $users = $user->getUser($userId);
        $user->hydrate($users);
        /*
                echo '<pre>';
                print_r($users);
                print_r($user);
                die();*/
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['userForm'] == 'updateUser') {
                $user->setEmail($post['email']);
                $user->setRole($post['role']);
                $user->setStatus($post['status']);
                $user->updateUser();
                $_SESSION['auth']['role'] = $user->getRole();
                $_SESSION['auth']['username'] = $user->getUsername();
                $_SESSION['auth']['email'] = $user->getEmail();
                $_SESSION['auth']['status'] = $user->getStatus();
                $_SESSION['flash']['alert'] = "Success";
                $_SESSION['flash']['infos'] = "Modification effectué avec succès !";
            }
        }
        $this->generateView([

            'user' => $user,
            'roles' => $roles,
            'user_status' => $user_status,
        ]);
    }

    /**
     * @throws Exception
     */
    public function pictureUpload()
    {
        $user = new User();
        $post = isset($_POST) ? $_POST : false;

        $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $users = $user->getUser($userId);
        $dossier = 'content/uploads/';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['pictureUpload'] == 'upload') {
                $fichier = basename($_FILES['picture']['name']);
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                {
                    $usersBdd = $user->hydrate($users);
                    $user->setPicture($dossier . $fichier);
                    $user->updatePictureUser();
                    $_SESSION['flash']['alert'] = "Success";
                    $_SESSION['flash']['infos'] = "Upload effectué avec succès !";
                    header('Location: /dashboard/adminUpdateUser/' . $users->id);
                    exit;
                } else //Sinon (la fonction renvoie FALSE).
                {
                    echo 'Echec de l\'upload !';
                }
            }
        }
        $this->generateView([

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
                        header('Location: /dashboard/');
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

    /**
     * @throws Exception
     */
    public function pictureArticleUpload()
    {
        $article = new Article();
        $post = isset($_POST) ? $_POST : false;

        $articleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $articles = $article->getOneArticle($articleId);
        $dossier = 'content/uploads/article/';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['pictureUpload'] == 'upload') {
                $fichier = basename($_FILES['picture']['name']);
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                {
                    $articleBdd = $article->hydrate($articles);
                    $article->setPictureUrl($dossier . $fichier);
                    $article->updatePicturArticle();
                    $_SESSION['flash']['alert'] = "Success";
                    $_SESSION['flash']['infos'] = "Upload effectué avec succès !";
                    header('Location: /dashboard/');
                    exit;
                } else //Sinon (la fonction renvoie FALSE).
                {
                    echo 'Echec de l\'upload !';
                }
            }
        }
        $this->generateView([

        ]);
    }

    public function deleteArticle()
    {
        $articleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $article = new Article();
        $article->deleteArticle($articleId);
        header('Location: /dashboard');
        exit;
    }

    /**
     * @throws Exception
     */
    public function updateArticle()
    {
        $articleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $post = isset($_POST) ? $_POST : false;
        $article = new Article();
        $articleBdd = $article->getOneArticle($articleId);
        $article->hydrate($articleBdd);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['articleForm'] == 'updateArticle') {
                $article->setTitle($post['title']);
                $article->setContent($post['content']);
                $article->setExcerpt($post['excerpt']);
                if ($article->formArticleUpdateValidate()) {
                    $dateNow = new DateTime();
                    $article->setCreatedAt($dateNow->format('Y-m-d H:i:s'));
                    if (isset($post['publish'])) {
                        $article->setPublish(self::PUBLISH['PUBLIÉ']);
                    } else {
                        $article->setPublish(self::PUBLISH['BROUILLON']);
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
            'articles' => $article,
            'errorsMsg' => $article->getErrorsMsg(),
        ]);
    }

    public function validComment()
    {
        $commentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $comment = new Comment();
        $comment->updateComment($commentId, self::COMMENT_STATUS['PUBLIÉ']);

        $_SESSION['flash']['alert'] = "Success";
        $_SESSION['flash']['infos'] = "Commentaire approuvé !";
        header('Location: /dashboard');
        exit;
    }


    public function deleteComment()
    {
        $commentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $comment = new Comment();
        $comment->deleteComment($commentId);
        $_SESSION['flash']['alert'] = "Success";
        $_SESSION['flash']['infos'] = "Commentaire supprimé !";
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