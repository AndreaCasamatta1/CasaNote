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
            $pass =$_POST['pass'];
            if (!$this->validator->validateEmail($email)) {
                $_SESSION["errors"] []  = "Errore nel login, email o password, errati o non esistenti";
                require_once 'application/views/_templates/error.php';
                $this->index();
                exit();
            }
            $result = $this->authModel->verifyUser($email, $pass);
            $resultInfo = $this->authModel->getUserInfo($email);
            if ($result) {
                $_SESSION["UserId"] = $resultInfo['id'];
                $_SESSION["name"] = $resultInfo['username'];
                $_SESSION["email"] = $resultInfo['email'];
                header("Location:" . URL . "home/main");

            } else {
                $_SESSION["errors"] []  = "Errore nel login, email o password, errati o non esistenti";
                require_once 'application/views/_templates/error.php';
                $this->index();

            }


        }

    }

    public function logout()
    {
        logger::info("entrato in logout");
        session_start();
        logger::info("sessione_start()");
        $_SESSION = [];
        logger::info("eliminate variabili sessioni");
        if(isset($_COOKIE[session_name()])):
            logger::info("Cookie eliminato: Session ID");
            setcookie(session_name(),'',time()-70000000,'/');
        endif;
        session_destroy();
        logger::info("distrutta sessioni");

        header("Location: " . URL . "login");
        exit();
    }
}
