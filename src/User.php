<?php

/**
 * Class User
 */
class User
{
    private $db;
    private $id;
    private $email;
    private $passwordHash;

    /**
     * User constructor.
     * @param Database $db
     */
    public function __construct(
        Database $db
    )
    {
        $this->db = $db;
    }

    /**
     * @param $id
     */
    public function getUserById($id)
    {
    }

    /**
     * @param $post
     * @return bool
     */
    public function registerUser($post)
    {
        if (empty($post['email'])) {
            return false;
        }
        if (empty($post['password'])) {
            return false;
        }
        if (empty($post['password_confirm'])) {
            return false;
        }

        App::log('start registration');

        $email = (string)filter_input(INPUT_POST, 'email');
        $password = $post['password'];
        $passwordConfirm = $post['password_confirm'];

        if ($password === $passwordConfirm) {
            App::log('passwords match');
            // Allow to register
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            return $this->db->registerUser($email, $passwordHash);
        } else {
            return false;
        }

    }

    /**
     *
     */
    public function deleteUser()
    {
    }

    /**
     * @param $userData
     */
    public function loadUser($userData)
    {
        $this->setId($userData['id']);
        $this->setEmail($userData['email']);
        $this->setPasswordHash($userData['password']);
    }

    /**
     * @param $email
     * @return $this
     */
    public function getUserByEmail($email)
    {
        $userData = $this->db->getUserByEmail($email);
        // validate user data
        // if problems, return false

        App::log($userData);

        $this->loadUser($userData);

        return $this;
    }

    /**
     * @param $post
     * @return bool
     */
    public function loginUser($post)
    {
        if (empty($post['email'])) {
            return false;
        }
        if (empty($post['password'])) {
            return false;
        }

        $email = $post['email'];
        $password = $post['password'];
        $this->getUserByEmail($email);
        App::log('this id ' . $this->id);
        if (!$this->id) {
            return false;
        }

        App::log(password_hash($password, PASSWORD_DEFAULT));
        App::log($this->passwordHash);
        if (password_verify($password, $this->passwordHash)) {
            $_SESSION['id'] = $this->id;
            $_SESSION['email'] = $this->email;
            return true;
        } else {
            return false;
        }
    }

    public static function logoutUser()
    {
        session_destroy();
    }

    /**
     * @param $id
     */
    private function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $email
     */
    private function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param $passwordHash
     */
    private function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }
}