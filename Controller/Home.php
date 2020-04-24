<?php

require_once 'Framework/Controller.php';
require_once 'Model/Article.php';
require_once 'Model/User.php';
require_once 'Model/Mailer.php';
require_once 'Services/Validator.php';
require_once './vendor/autoload.php';

class Home extends Controller
{
    const STATUS = [
        'NON ACTIF' => '0',
        'ACTIF' => '1'
    ];

    const LENGTH_TOKEN = 78;

    const ROLES = [
        'BANNI' => '0',
        'VISITEUR' => '10',
        'MEMBER' => '20',
        'ADMIN' => '75',
        'SUPERADMIN' => '99',
    ];

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
    public function contact()
    {
        $mailer = new Mailer();
        $post = isset($_POST) ? $_POST : false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['sendForm'] == 'send') {
                $mailer->setName($post['name']);
                $mailer->setEmail($post['email']);
                $mailer->setSubject($post['subject']);
                $mailer->setContent($post['content']);
                if ($mailer->checkFormValidate()) {
                    $data = [
                        'name' => $mailer->getName(),
                        'email' => $mailer->getEmail(),
                        'subject' => $mailer->getSubject(),
                        'content' => $mailer->getContent()
                    ];
                    $this->sendEmail('contact', $mailer->getSubject(), self::FROMEMAIL, $data, $mailer->getEmail(), self::AUTHOREMAIL);

                    $_SESSION['flash']['alert'] = "success";
                    $_SESSION['flash']['message'] = "Votre message à bien était envoyé";
                    header('Location: /home/contact');
                    exit;
                }
            }
        }
        $this->generateView([
            //'contact' => $contact,
            'post' => $post,
            'errorsMsg' => $mailer->getErrorsMsg(),
        ]);
    }

    /**
     * @throws Exception
     */
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
                        $user->setRole(self::ROLES ['VISITEUR']);
                        $user->setStatus(self::STATUS ['NON ACTIF']);
                        $user->generateToken();
                        $data = [
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'token' => $user->getToken()
                        ];
                        $user->save();
                        $this->sendEmail('registration', 'Inscription sur le blog Jean ForteRoche', $user->getEmail(), $data);

                        $_SESSION['flash']['alert'] = "success";
                        $_SESSION['flash']['message'] = "Veuillez consulté votre messagerie afin de valider la création de votre compte";
                        header('Location: /home/registration');
                        exit();
                    } else {
                        $_SESSION['flash']['alert'] = "danger";
                        $_SESSION['flash']['message'] = "Identifiant indisponible";
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

    /**
     * @throws Exception
     */
    public function userValidationRegistered()
    {
        $user = new User();
        $get = isset($_GET) ? $_GET : false;

        $user->setEmail($get['email']);
        $user->setToken($get['token']);

        $userEmail = $user->getEmail();
        if ($user->emailAndTokenValidation()) {
            $userBdd = $user->getEmailAndTokenUserInBdd($userEmail);
            if ($userBdd) {
                $user->hydrate($userBdd);
                $user->setStatus(self::STATUS ['ACTIF']);
                $user->setRole(self::ROLES ['MEMBER']);
                $user->updateUser();
                $_SESSION['flash']['alert'] = "success";
                $_SESSION['flash']['message'] = "Votre compte est désormais activé, vous pouvez dès à présent vous connecter à l'aide de vos identifiants";
                header('Location: /home/login');
                exit();
            }
        }
        $this->generateView([
            'userBdd' => $userBdd
        ]);
    }

    /**
     * @throws Exception
     */
    public function forgottenPassword()
    {
        $user = new User();
        $get = isset($_GET) ? $_GET : false;
        $post = isset($_POST) ? $_POST : false;

        $user->setEmail($get['email']);
        $user->setToken($get['token']);

        $userEmail = $user->getEmail();
        $userBdd = $user->getEmailAndTokenUserInBdd($userEmail);
        if ($userBdd) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($post['passwordForm'] == 'newPassword') {
                    $user->setpassword($post['password']);
                    $user->setCPassword($post['cPassword']);
                    if ($user->formNewPasswordValidate()) {
                        $user->generateToken();
                        $data = [
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'token' => $user->getToken()
                        ];
                        $user->updatePassword();
                        $this->sendEmail('newPassword', 'Modification de votre compte sur le blog Jean ForteRoche', $user->getEmail(), $data);

                        $_SESSION['flash']['alert'] = "success";
                        $_SESSION['flash']['message'] = "Vous pouvez dès à présent vous connecter avec votre nouveau mot de passe";
                        header('Location: /home/login');
                        exit();
                    }
                }
            }
        }
        $this->generateView([
            'errorsMsg' => $user->getErrorsMsg(),
            'post' => $post
        ]);
    }


    /**
     * @throws Exception
     */
    public function login()
    {
        $user = new User();
        $post = isset($_POST) ? $_POST : false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['loginForm'] == 'login') {
                $user->setEmail($post['email']);
                $user->setPassword($post['password']);
                if ($user->formLoginValidate()) {
                    $userBdd = $user->getUserInBdd(self::STATUS['ACTIF']);
                    if ($userBdd) {
                        $user->hydrate($userBdd);
                        if ($user->login()) {
                            $_SESSION['auth']['username'] = $user->getUsername();
                            $_SESSION['auth']['picture'] = $user->getPicture();
                            $_SESSION['auth']['email'] = $user->getEmail();
                            $_SESSION['auth']['role'] = $user->getRole();
                            $_SESSION['auth']['status'] = $user->getStatus();
                            $_SESSION['auth']['created_at'] = $user->getCreatedAt();
                            $_SESSION['auth']['id'] = $user->getId();
                            $_SESSION['auth']['token'] = $user->getToken();

                            $_SESSION['flash']['alert'] = "success";
                            $_SESSION['flash']['message'] = "Bienvenue";
                            header('Location: /dashboard');
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

    /**
     * @throws Exception
     */
    public function forgotYourPassword()
    {
        $user = new User();
        $post = isset($_POST) ? $_POST : false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($post['forgotYourPasswordForm'] == 'sendPassword') {
                $user->setEmail($post['email']);
                if ($user->formForgotPasswordValidate()) {
                    $userBdd = $user->getUserInBdd(self::STATUS['ACTIF']);
                    if ($userBdd) {
                        $user->hydrate($userBdd);
                        $user->generateToken();
                        $data = [
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'token' => $user->getToken()
                        ];
                        $user->updateToken();
                        $this->sendEmail('forgotYourPassword', 'Reinitialisation du mot de passe', $user->getEmail(), $data);
                        $_SESSION['flash']['alert'] = "success";
                        $_SESSION['flash']['message'] = "Veuillez consulté votre messagerie afin de reinitialiser de votre mot de passe";
                        header('Location: /home/login');
                        exit();
                    }
                }
            }
        }
        $this->generateView([
            'errorsMsg' => $user->getErrorsMsg(),
            'post' => $post,
        ]);
    }
}