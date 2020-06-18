<?php

require_once 'Framework/Controller.php';
require_once 'Model/User.php';
require_once 'Model/Article.php';
require_once 'Model/Comment.php';
require_once 'Services/Validator.php';
require_once 'Services/Upload.php';

class Dashboard extends Controller
{

    /**
     * @throws Exception
     */

    public function index()
    {

        if (!isset($_SESSION['auth'])) {
            header('Location: /home');
            exit();
        } elseif (isset($_SESSION['auth']) && $_SESSION['auth']['role'] >= '75') {
            $user = new User();
            $usersBdd = $user->getAllUserDashboard();

            $users = array();

            foreach ($usersBdd as $userBdd) {
                $user = new User;
                $user->hydrate($userBdd);
                $users[] = $user;
            }


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
        } else {
            $post = isset($_POST) ? $_POST : false;
            $roles = self::ROLES;
            $user_status = self::STATUS;
            $user = new User();
            $userId = $_SESSION['auth']['id'];
            $users = $user->getUser($userId);
            $user->hydrate($users);

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($post['userForm'] == 'updateUser') {
                    $user->setUsername($post['username']);
                    $user->setEmail($post['email']);
                    if ($user->userFormValidate()) {
                        $emailInBdd = $user->getEmailInBdd();
                        $usernameInBdd = $user->getUsernameInBdd();
                        if (!array_key_exists('id', $emailInBdd) && !array_key_exists('id', $usernameInBdd)) {

                            $data = [
                                'username' => $user->getUsername(),
                                'email' => $user->getEmail()
                            ];
                            $user->updateUserProfile();
                            $this->sendEmail('updateAccountUser', 'Modification de votre compte sur le blog Jean ForteRoche', $user->getEmail(), $data);
                            $_SESSION['auth']['username'] = $user->getUsername();
                            $_SESSION['auth']['email'] = $user->getEmail();
                            $_SESSION['flash']['alert'] = "Success";
                            $_SESSION['flash']['message'] = "Modification effectué avec succès !";
                        } else {
                            $_SESSION['flash']['alert'] = "danger";
                            $_SESSION['flash']['message'] = "Le nom d'utilisateur ou l'adresse email existe déjà";
                        }
                    }
                }
            }

            $comment = new Comment();
            $commentsConfirmedByBdd = $comment->getCommentsUser($userId, self::COMMENT_STATUS['PUBLIÉ']);
            $commentsReportedByBdd = $comment->getCommentsUser($userId, self::COMMENT_STATUS['EN ATTENTE']);

            $commentsConfirmed = array();

            foreach ($commentsConfirmedByBdd as $commentConfirmed) {
                $comment = new Comment;
                $comment->hydrate($commentConfirmed);
                $commentsConfirmed[] = $comment;
            }

            $commentsReported = array();
            foreach ($commentsReportedByBdd as $commentReported) {
                $comment = new Comment;
                $comment->hydrate($commentReported);
                $commentsReported[] = $comment;
            }

            $this->generateView([
                'user' => $user,
                'roles' => $roles,
                'confirmed' => $commentsConfirmed,
                'reported' => $commentsReported,
                'errorsMsg' => $user->getErrorsMsg()
            ]);
        }
    }

    public function updatePasswordUser()
    {
        $post = isset($_POST) ? $_POST : false;
        $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $user = new User();
        $users = $user->getUser($userId);
        $user->hydrate($users);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['userForm'] == 'updateUserPassword') {
                $user->setpassword($post['password']);
                $user->setCPassword($post['cPassword']);
                if ($user->formNewPasswordValidate()) {
                    $data = [
                        'username' => $user->getUsername(),
                        'email' => $user->getEmail()
                    ];
                    $user->updatePassword();
                    $this->sendEmail('newPassword', 'Modification de votre compte sur le blog Jean ForteRoche', $user->getEmail(), $data);

                    $_SESSION['auth']['username'] = $user->getUsername();
                    $_SESSION['auth']['email'] = $user->getEmail();
                    $_SESSION['flash']['alert'] = "Success";
                    $_SESSION['flash']['infos'] = "Modification effectué avec succès !";
                    header('Location: /dashboard');
                    exit;
                }
            }
        }

        $this->generateView([
            'user' => $user,
            'errorsMsg' => $user->getErrorsMsg()

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


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['userForm'] == 'updateUser') {
                $user->setEmail($post['email']);
                $user->setUsername($post['username']);
                $user->setRole($post['role']);
                $user->setStatus($post['status']);
                if ($user->userFormValidate()) {
                    $emailInBdd = $user->getEmailInBdd();
                    $usernameInBdd = $user->getUsernameInBdd();
                    if (!array_key_exists('id', $emailInBdd) && !array_key_exists('id', $usernameInBdd)) {
                        $user->updateUser();
                        $_SESSION['flash']['alert'] = "Success";
                        $_SESSION['flash']['messages'] = "Modification effectué avec succès !";
                    } else {
                        $_SESSION['flash']['alert'] = "danger";
                        $_SESSION['flash']['messages'] = "Le nom d'utilisateur ou l'adresse email existe déjà";
                    }
                }
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
        $user->hydrate($users);
        $path = Controller::PATH_UPLOAD['user'];


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($post['pictureUpload'] == 'upload') {
                Upload::uploadPicture($user, $path);
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
                $article->setPicture($post['picture_url']);
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
        $article->hydrate($articles);
        $path = Controller::PATH_UPLOAD['article'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['pictureUpload'] == 'upload') {
                Upload::uploadPicture($article, $path);
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