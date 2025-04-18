<?php


class login
{
    private $validator;

    public function __construct()
    {
        require_once "application/libs/validator.php";
        $this->validator = new Validator();
    }

    public function index()
    {
        require 'application/views/_templates/header.php';
        require 'application/views/_templates/navbar.php';
        require_once 'application/views/login/index.php';
    }

    public function logIn()
    {
        session_start();
        if (isset($_POST['login'])) {

            require_once 'application/libs/validator.php';

            $email = $this->validator->sanitizeMail($_POST['email']);
            $pass = $this->validator->sanitizeInput($_POST['pass']);
            require_once 'application/models/AuthModel.php';
            if (!$this->validator->validateEmail($email)) {
                require_once 'application/views/_templates/error.php';
                $this->index();
                exit();
            }
            $authModel = new AuthModel();
            $result = $authModel->getData($email, $pass);
            if ($result) {
                $_SESSION["UserId"] = $result['id'];

                header("Location:" . URL . "home/main");
                exit();
            } else {
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }

    }

    public function logout()
    {
        session_start();
        unset($_SESSION['UserId']);

        session_destroy();
        header("Location:" . URL);
        exit();
    }


}
