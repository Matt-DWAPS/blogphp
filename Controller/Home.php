<?php

require_once 'Framework/Controller.php';
require_once 'Model/User.php';

class Home extends Controller
{
    const ROLES = [
        'MEMBER' => '20',
        'ADMIN' => '99',
    ];

    public function index()
    {
        $this->generateView([]);
    }

    public function registration()
    {
        $user = new User();
        $post = isset($_POST) ? $_POST : false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['registerForm'] == 'register') {
                $user->setUsername($post['username']);
                $user->setEmail($post['email']);
                $user->setpassword($post['password']);
                $user->setCPassword($post['cPassword']);
                if ($user->formRegisterValidate()) {
                    if ($user->registerValidate()) {
                        $dateNow = new DateTime();
                        $user->setCreatedAt($dateNow->format('Y-m-d H:i:s'));
                        $user->setRole(self::ROLES['MEMBER']);
                        $user->setStatus('0');
                        $user->setToken(null);
                        $user->save();
                        header('Location: /home');
                        exit();
                    } else {
                        echo 'identifiant déjà utilisé';
                        die();
                    }
                }
            }
        }
        $this->generateView([
            'errorsMsg' => $user->getErrorsMsg(),
            'post' => $post
        ]);
    }

    public function login()
    {
        $user = new User();
        $post = isset($_POST) ? $_POST : false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['loginForm'] == 'login') {
                $user->setEmail($post['email']);
                $user->setPassword($post['password']);
                if ($user->formLoginValidate()) {
                    $userBdd = $user->getUserInBdd();
                    if ($userBdd) {
                        $user->hydrate($userBdd);
                        if ($user->login()) {
                            $_SESSION['auth']['username'] = $user->getUsername();
                            $_SESSION['auth']['email'] = $user->getEmail();
                            $_SESSION['auth']['role'] = $user->getRole();
                            $_SESSION['auth']['status'] = $user->getStatus();
                            $_SESSION['auth']['created_at'] = $user->getCreatedAt();
                            /*$_SESSION['auth']['token']= $user->getToken();*/
                            $_SESSION['flash']['alert'] = "success";
                            $_SESSION['flash']['message'] = "Bienvenue";
                            header('Location: /home');
                            exit();
                        } else {
                            $_SESSION['flash']['alert'] = "danger";
                            $_SESSION['flash']['message'] = "Mauvais identifiant";
                        }
                    } else {
                        $_SESSION['flash']['alert'] = "danger";
                        $_SESSION['flash']['message'] = "Mauvais identifiant";
                    }
                }
            }
        }
        $this->generateView([
            'errorsMsg' => $user->getErrorsMsg(),
            'post' => $post
        ]);
    }
}