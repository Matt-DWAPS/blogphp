<?php
require_once 'Configuration.php';
require_once 'Request.php';
require_once 'View.php';
require_once './vendor/autoload.php';

abstract class Controller
{
    const FROMEMAIL = 'jeanforteroche@webagency-matt.com';
    const AUTHOREMAIL = 'Jean ForteRoche';

    const ROLES = [
        'BANNI' => '0',
        'VISITEUR' => '10',
        'MEMBRE' => '20',
        'ADMIN' => '75',
        'SUPERADMIN' => '99',
    ];

    const PUBLISH = [
        'PUBLIÉ' => 1,
        'BROUILLON' => 0
    ];

    const STATUS = [
        'NON ACTIF' => '0',
        'ACTIF' => '1'
    ];

    const COMMENT_STATUS = [
        'PUBLIÉ' => 1,
        'EN ATTENTE' => 0
    ];

    // Action à réaliser
    private $action;

    // Requête entrante
    protected $request;

    // Définit la requête entrante
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $action
     * @throws Exception
     */
    // Exécute l'action à réaliser
    public function executeAction($action)
    {
        if (method_exists($this, $action)) {
            $this->action = $action;
            $this->{$this->action}();
        } else {
            $classController = get_class($this);
            throw new Exception("Action '$action' non définie dans la classe $classController");
        }
    }

    // Méthode abstraite correspondant à l'action par défaut
    // Oblige les classes dérivées à implémenter cette action par défaut
    public abstract function index();


    /**
     * @param array $dataView
     * @throws Exception
     */
    // Génère la vue associée au contrôleur courant
    protected function generateView($dataView = array())
    {
        // Détermination du nom du fichier vue à partir du nom du contrôleur actuel
        $classController = get_class($this);
        $controller = str_replace("Controller", "", $classController);
        // Instanciation et génération de la vue
        $vue = new View($this->action, $controller);
        $vue->generate($dataView);
    }


    public function sendEmail($action, $subject, $toEmail = self::FROMEMAIL, $data = [], $fromEmail = self::FROMEMAIL, $mailAuthor = self::AUTHOREMAIL)
    {
        $vue = new View($action, 'Mails');
        $body = $vue->generateMail($data);

        
        $transport = (new Swift_SmtpTransport(
            Configuration::get('mailtransport', 'mailport')))
            ->setUsername(Configuration::get('mailusername'))
            ->setPassword(Configuration::get('mailpassword'));

        $mailer = new Swift_Mailer($transport);


        $message = (new Swift_Message($subject))
            ->setFrom([$fromEmail => $mailAuthor])
            ->setTo([$toEmail])
            ->setBody($body)
            ->setContentType('text/html');
        $mailer->send($message);

    }
}