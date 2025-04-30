<?php
session_start();
class login
{
    private $authModel;
    private $validator;

    public function __construct()
    {
        require_once "application/libs/validator.php";
        $this->validator = new validator();
        require_once "application/models/authModel.php";
        $this->authModel = new authModel();
    }

    public function index()
    {
        require 'application/views/_templates/header.php';
        require 'application/views/_templates/navbar.php';
        require_once 'application/views/login/index.php';
    }

    public function logIn()
    {

        if (isset($_POST['login'])) {

            require_once 'application/libs/validator.php';

            $email = $this->validator->sanitizeMail($_POST['email']);
            $pass = $this->validator->sanitizeInput($_POST['pass']);
            if (!$this->validator->validateEmail($email)) {
                require_once 'application/views/_templates/error.php';
                $this->index();
                exit();
            }
            $result = $this->authModel->verifyUser($email, $pass);
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

        unset($_SESSION['UserId']);

        session_destroy();
        header("Location:" . URL);
        exit();
    }


}
