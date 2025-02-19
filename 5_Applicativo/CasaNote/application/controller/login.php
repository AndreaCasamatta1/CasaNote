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
        require_once 'application/views/login/index.php';
    }

    public function logIn()
    {
        session_start();
        if (isset($_POST['login'])) {

            require_once 'application/libs/validator.php';

            $name = $this->validator->sanitizeInput($_POST['name']);
            $pass = $this->validator->sanitizeInput($_POST['pass']);

            require_once 'application/models/AuthModel.php';
            $authModel = new AuthModel();
            $result = $authModel->getData($name, $pass);

            if ($result) {
                $_SESSION["UserId"] = $result['id'];

                header("Location:" . URL . "admin");
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
