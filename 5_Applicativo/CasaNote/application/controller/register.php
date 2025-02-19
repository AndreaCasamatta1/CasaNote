<?php

class register
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
        require_once 'application/views/register/index.php';
    }

    public function register()
    {
        if (isset($_POST['register'])) {

            require_once 'application/libs/validator.php';

            $name = $this->validator->sanitizeInput($_POST['name']);
            $password = $this->validator->sanitizeInput($_POST['pass']);
            $password_confirm = $this->validator->sanitizeInput($_POST['pass2']);

            require_once 'application/models/AuthModel.php';
            $authModel = new AuthModel();
            $result = $authModel->registerUser($name, $password, $password_confirm);
            if ($result) {
                $_SESSION["UserId"] = $result['id'];
                header("Location:" . URL . "home");
                require_once "application/views/_templates";
                exit();
            } else {
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }

    }
}