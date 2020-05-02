<?php
require_once 'Framework/Model.php';

class User extends Model
{
    const MAX_LENGTH_USERNAME = 16;
    const LENGTH_TOKEN = 78;

    private $id;
    private $username;
    private $email;
    private $password;
    private $cPassword;
    private $createdAt;
    private $role;
    private $status;
    private $token;
    private $picture;

    private $errors = 0;
    private $errorsMsg = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getCPassword()
    {
        return $this->cPassword;
    }

    /**
     * @param mixed $cPassword
     */
    public function setCPassword($cPassword)
    {
        $this->cPassword = $cPassword;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }


    /**
     * @return array
     */
    public function getErrorsMsg()
    {
        return $this->errorsMsg;
    }

    public function formLoginValidate()
    {
        $this->checkEmail();
        $this->checkPasswordLogin();

        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function hydrate($user)
    {
        $this->setCPassword($user->password);
        $this->setEmail($user->email);
        $this->setPicture($user->picture);
        $this->setRole($user->role);
        $this->setUsername($user->username);
        $this->setStatus($user->status);
        $this->setToken($user->token);
        $this->setCreatedAt($user->created_at);
        $this->setId($user->id);
    }

    public function login()
    {
        if (password_verify($this->getPassword(), $this->getCPassword())) {
            return true;
        } else {
            return false;
        }
    }

    public function passwordHash()
    {
        $this->setPassword(password_hash($this->getPassword(), PASSWORD_BCRYPT));
        $this->setCPassword(null);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws Exception
     */
    public function getUser($userId)
    {
        $sql = 'SELECT id as id, created_at as created_at, role as role, status as status, username as username, picture as picture, email as email FROM user WHERE id=:id';
        $user = $this->executeRequest($sql, array(
            'id' => $userId,
        ));
        if ($user->rowCount() == 1)
            return $user->fetch();
        else {
            throw new Exception("Aucun utilisateur ne correspond à l'identifiant '$userId'");
        }
    }

    private function checkToken()
    {
        if (Validator::isEmpty($this->getToken())) {
            $this->errors++;
            $this->errorsMsg['token'] = "Token non valide";
        }
    }

    public function emailAndTokenValidation()
    {
        $this->checkEmail();
        $this->checkToken();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * @param $userEmail
     * @return mixed
     * @throws Exception
     */
    public function getEmailAndTokenUserInBdd($userEmail)
    {
        $sql = 'SELECT email, token FROM user WHERE email= :email';
        $user = $this->executeRequest($sql, array(
            'email' => $this->getEmail(),
        ));
        if ($user->rowCount() === 1)
            return $user->fetch();
        else {
            throw new Exception("Aucun utilisateur ne correspond à l'adresse email '$userEmail'");
        }
    }

    public function updateUser()
    {
        $sql = 'UPDATE user SET role=:role, status=:status, email=:email WHERE email=:email';
        $updateUser = $this->executeRequest($sql, array(
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'role' => $this->getRole(),
            'status' => $this->getStatus()
        ));
    }

    public function updateUserProfile()
    {
        $sql = 'UPDATE user SET email=:email, username=:username WHERE email=:email';
        $updateUser = $this->executeRequest($sql, array(
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'username' => $this->getUsername(),
        ));
    }

    public function updatePictureUser()
    {
        $sql = 'UPDATE user SET picture=:picture WHERE id=:id';
        $updatePicture = $this->executeRequest($sql, array(
            'id' => $this->getId(),
            'picture' => $this->getPicture()
        ));
    }

    public function updateToken()
    {
        $sql = 'UPDATE user SET token=:token WHERE email=:email';
        $updateUser = $this->executeRequest($sql, array(
            'email' => $this->getEmail(),
            'token' => $this->getToken()
        ));
    }

    public function updatePassword()
    {
        $this->passwordHash();
        $sql = 'UPDATE user SET password=:password, token=:token WHERE email=:email';
        $updateUser = $this->executeRequest($sql, array(
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'token' => $this->getToken()
        ));
    }

    /**
     * @throws Exception
     */
    public function generateToken()
    {
        $this->setToken(bin2hex(random_bytes(self::LENGTH_TOKEN)));
    }

    public function getUserInBdd($status = null)
    {
        $sql = 'SELECT username, email, password, role, status, created_at, id, picture FROM user WHERE email= :email';

        if ($status !== null) {
            $sql .= ' AND status = :status';
            $req = $this->executeRequest($sql, array(
                'email' => $this->getEmail(),
                'status' => $status,
            ));
            return $req->fetch();
        }
        $req = $this->executeRequest($sql, array(
            'email' => $this->getEmail(),
        ));
        return $req->fetch();
    }

    private function checkPasswordLogin()
    {
        if (Validator::isEmpty($this->getPassword())) {
            $this->errors++;
            $this->errorsMsg['password'] = "Password vide";
        }
    }

    public function formNewPasswordValidate()
    {
        $this->checkPassword();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function formRegisterValidate()
    {
        $this->checkUsername();
        $this->checkEmail();
        $this->checkPassword();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function userFormValidate()
    {
        $this->checkUsername();
        $this->checkEmail();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function formForgotPasswordValidate()
    {
        $this->checkEmail();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function registerValidate()
    {
        if ($this->checkEmailInBdd() === 0 && $this->checkUsernameInBdd() === 0) {
            return true;
        }
        return false;
    }

    private function checkUsername()
    {
        if (Validator::isEmpty($this->getUsername())) {
            $this->errors++;
            $this->errorsMsg['username'] = "Nom d'utilisateur vide";
        }

        if (Validator::isToUpper($this->getUsername(), self::MAX_LENGTH_USERNAME)) {
            $this->errors++;
            $this->errorsMsg['username'] = "Nom d'utilisateur trop long";
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

    private function checkPassword()
    {
        if (Validator::isEmpty($this->getPassword())) {
            $this->errors++;
            $this->errorsMsg['password'] = "Password vide";
        } elseif (Validator::isEmpty($this->getCPassword()) || Validator::isNotIdentic($this->getPassword(), $this->getCPassword())) {
            $this->errors++;
            $this->errorsMsg['password'] = "Les deux mots de passe ne sont pas identiques";
        }
    }

    public function checkEmailInBdd()
    {
        $sql = 'SELECT email FROM user WHERE email=:email';
        $req = $this->executeRequest($sql, array('email' => $this->getEmail()));
        return $req->rowCount();
    }

    public function checkUsernameInBdd()
    {
        $sql = 'SELECT username FROM user WHERE username=:username';
        $req = $this->executeRequest($sql, array('username' => $this->getUsername()));
        return $req->rowCount();
    }


    public function checkPasswordInBdd()
    {
        $sql = 'SELECT password FROM user WHERE password=:password';
        $req = $this->executeRequest($sql, array('password' => $this->getPassword()));
        $passwordCorrect = password_verify($_POST['password'], $req['password']);
        return $passwordCorrect->rowCount();
    }

    public function getAllUserDashboard()
    {
        $sql = 'SELECT id, username, email, password, role, status, created_at FROM user';
        $req = $this->executeRequest($sql);
        return $req->fetchAll();
    }

    public function save()
    {
        $this->passwordHash();
        $sql = "INSERT INTO user(username, email, password, role, status, created_at, token) VALUES(:username, :email, :password, :role, :status, :created_at, :token)";
        $req = $this->executeRequest($sql, array(
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'role' => $this->getRole(),
            'status' => $this->getStatus(),
            'created_at' => $this->getCreatedAt(),
            'token' => $this->getToken(),
        ));

    }

}

