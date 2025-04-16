<?php

class user
{
    private $authModel;
    private $validator;

    function __construct()
    {
        require_once "application/models/AuthModel.php";
        $this->authModel = new AuthModel();
        require_once "application/libs/Validator.php";
        $this->validator = new Validator();
    }
    function index()
    {
        require 'application/views/_templates/navbar2.php';
        require 'application/views/_templates/header.php';
        require 'application/views/profile/index.php';
        require 'application/views/_templates/footer.php';
    }
    public function updateName()
    {
        session_start();
        if (isset($_POST['newName'])) {
            $newName = $_POST['newName'];
            $userId = $_SESSION['UserId'];
            logger::info($userId);
            if ($this->authModel->updateName($userId, $newName)) {
                header("location: " . URL . "home/main");
                exit();
            } else {
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }
    }
    public function updatePassword()
    {
        session_start();
        if (isset($_POST['oldPassword'], $_POST['newPassword']) && isset($_SESSION['UserId'])) {
            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['newPassword'];
            $userId = $_SESSION['UserId'];

            if ($this->authModel->updatePassword($userId, $newPassword,$oldPassword)) {
                header("location: " . URL . "home/main");
                exit();
            } else {
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }
    }
    public function deleteAccount()
    {
        session_start();
        if (isset($_SESSION['UserId'])) {
            $userId = $_SESSION['UserId'];

            if ($this->authModel->deleteUser($userId)) {
                session_destroy();
                header("location: " . URL . "logout");
                exit();
            } else {
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }
    }
}
