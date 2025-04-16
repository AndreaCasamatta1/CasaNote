<?php
session_start();
class user
{
    private $authModel;
    private $validator;

    function __construct()
    {
        require_once "application/models/authModel.php";
        $this->authModel = new authModel();
        require_once "application/libs/validator.php";
        $this->validator = new validator();
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
