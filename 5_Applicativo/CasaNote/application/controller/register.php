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
            $password = $this->validator->sanitizeInput($_POST['pass']);
            $password_confirm = $this->validator->sanitizeInput($_POST['pass2']);
            if (!$this->validator->validateEmail($email)) {
                require_once 'application/views/_templates/error.php';
                $this->index();
                exit();
            }
            $result = $this->authModel->registerUser($name, $email, $password, $password_confirm);
            logger::info($result);
            if ($result) {
                $idUser = $this->authModel->getUserInfo($email);
                logger::info('Dati restituiti da getData: ' . print_r($idUser, true));
                if($idUser) {
                    $_SESSION["UserId"] = $idUser['id'];
                    logger::info("ID utente salvato nella sessione: " . $_SESSION["UserId"]);
                    header("Location:" . URL . "home/main");
                    exit();
                } else {
                    logger::error("Errore: utente non trovato, password errata o email gia in uso.");
                    require_once 'application/views/_templates/error.php';
                    $this->index();
                    exit();
                }

            } else {
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }

    }
}