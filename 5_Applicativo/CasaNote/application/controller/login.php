<?php

class Login
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
            $username = $this->validator->sanitizeInput($_POST['username']);
            $pass = $this->validator->sanitizeInput($_POST['pass']);
            require_once 'application/models/AuthModel.php';
            $authModel = new AuthModel();
            $result = $authModel->getData($username, $pass);

            if ($result) {
                $_SESSION["UserId"] = $result['id'];
                $_SESSION["nome"] = $result['nome'];
                $_SESSION["cognome"] = $result['cognome'];
                $_SESSION["tipo"] = $result['tipo'];

                header("Location:" . URL . "admin");
                exit();
            } else {
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
