<?php
session_start();
class register
{
    private $validator;
    private $authModel;

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
        require_once 'application/views/register/index.php';
    }

    public function register()
    {
        if (isset($_POST['register'])) {
            require_once 'application/libs/validator.php';
            $name = $this->validator->sanitizeInput($_POST['name']);
            $email = $this->validator->sanitizeMail($_POST['email']);
            $password = $_POST['pass'];
            $password_confirm = $_POST['pass2'];
            if (!$this->validator->validateEmail($email)) {
                $_SESSION["errors"] []  = "La email non rispetta i criteri.";
                require_once 'application/views/_templates/error.php';
                $this->index();
                exit();
            }
            $passwordValidation = $this->validator->validatePassword($password);
            if (!$passwordValidation) {
                $_SESSION["errors"] []  = "La password non rispetta i criteri";
                require_once 'application/views/_templates/error.php';
                $this->index();
                exit();
            }
            if ($password !== $password_confirm) {
                $_SESSION["errors"] []  = "Le password non corrispondono";
                require_once 'application/views/_templates/error.php';
                $this->index();
                exit();
            }
            $resultRegister = $this->authModel->registerUser($name, $email, $password, $password_confirm);
            logger::info($resultRegister);
            if ($resultRegister) {
                $result = $this->authModel->getUserInfo($email);
                logger::info('Dati restituiti da getData: ' . print_r($result, true));
                if($result) {
                    $_SESSION["UserId"] = $result['id'];
                    $_SESSION["name"] = $result['username'];
                    $_SESSION["email"] = $result['email'];
                    logger::info("ID utente salvato nella sessione: " . $_SESSION["UserId"]);
                    header("Location:" . URL . "home/main");
                    exit();
                } else {
                    logger::error("Errore: password errata o email gia in uso.");
                    $_SESSION["errors"] []  = "Password errata o email gia in uso.";
                    require_once 'application/views/_templates/error.php';
                    $this->index();
                    exit();
                }

            } else {
                $_SESSION["errors"] []  = "Email già presente.";
                require_once 'application/views/_templates/error.php';
                $this->index();
                exit();
            }
        }

    }
}