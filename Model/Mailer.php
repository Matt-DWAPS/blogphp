<?php
require_once 'Framework/Model.php';

class Mailer extends Model
{
    const MAX_LENGTH_USERNAME = 20;
    private $username;
    private $email;
    private $name;
    private $content;
    private $subject;

    private $errors = 0;
    private $errorsMsg = [];

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return int
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param int $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrorsMsg()
    {
        return $this->errorsMsg;
    }

    /**
     * @param array $errorsMsg
     */
    public function setErrorsMsg($errorsMsg)
    {
        $this->errorsMsg = $errorsMsg;
    }


    private function checkName()
    {
        if (Validator::isEmpty($this->getName())) {
            $this->errors++;
            $this->errorsMsg['name'] = "Nom d'utilisateur vide";
        }

        if (Validator::isToUpper($this->getName(), self::MAX_LENGTH_USERNAME)) {
            $this->errors++;
            $this->errorsMsg['username'] = "Nom d'utilisateur trop long";
        }
    }

    private function checkSubject()
    {
        if (Validator::isEmpty($this->getSubject())) {
            $this->errors++;
            $this->errorsMsg['subject'] = "Sujet du message vide";
        }
    }

    private function checkEmail()
    {
        if (Validator::isEmpty($this->getEmail())) {
            $this->errors++;
            $this->errorsMsg['email'] = "Email vide";
        }

        if (Validator::isNotAnEmail($this->getEmail())) {
            $this->errors++;
            $this->errorsMsg['email'] = "Email non valide";
        }
    }

    private function checkContent()
    {
        if (Validator::isEmpty($this->getContent())) {
            $this->errors++;
            $this->errorsMsg['content'] = "Contenu du message vide";
        }
    }

    public function checkFormValidate()
    {
        $this->checkName();
        $this->checkSubject();
        $this->checkEmail();
        $this->checkContent();

        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }

    }


}