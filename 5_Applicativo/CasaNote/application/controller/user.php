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

    }
    public function updateName()
    {
        if (isset($_POST['newName'])) {
            $newName = $this->validator->sanitizeInput($_POST['newName']);
            $userId = $_SESSION['UserId'];
            var_dump($userId);
            var_dump($this->authModel->updateName($userId, $newName));
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

            // Verifica la password attuale
            $userData = $this->authModel->getData($_SESSION['email'], $oldPassword);
            if ($userData && $this->authModel->updatePassword($userId, $newPassword)) {
                header("location: " . URL . "home/user");
                exit();
            } else {
                require_once 'application/views/_templates/error.php';
            }
        }
    }
    public function deleteAccount()
    {
        if (isset($_SESSION['UserId'])) {
            $userId = $_SESSION['UserId'];

            if ($this->authModel->deleteUser($userId)) {
                session_destroy();
                header("location: " . URL . "home/main");
                exit();
            } else {
                require_once 'application/views/_templates/error.php';
                $this->index();
            }
        }
    }
}
