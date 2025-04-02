<?php

class AuthModel
{
    private $conn;
    private $statement;

    public function __construct()
    {
        $this->conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
    }
// 1. getUserInfo Method
    public function getUserInfo($email)
    {

        $selectUserInfo = "SELECT id, email, username,password FROM users WHERE email = ?";

        $this->statement = $this->conn->prepare($selectUserInfo);
        $this->statement->bind_param("s", $email);
        $this->statement->execute();

        $result = $this->statement->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            Logger::info("User info found: " . print_r($user, true));
            return $user;
        } else {
            return null;
        }
    }

    public function verifyUser($email, $password)
    {

        $selectAccess = "SELECT id, password, username FROM users WHERE email = ?";


        $this->statement = $this->conn->prepare($selectAccess);
        $this->statement->bind_param("s", $email);
        $this->statement->execute();

        $result = $this->statement->get_result();
        $user = $result->fetch_assoc();

        Logger::info("Verifica utente: " . print_r($user, true));

        // Verifica la password
        if ($user && password_verify($password, $user['password'])) {
            Logger::info("Password verificata per l'utente: " . $user['username']);
            return $user;
        } else {
            return null;
        }
    }


    public function registerUser($name, $email, $password, $password_confirm)
    {
        if ($password !== $password_confirm) {
            echo "Le password non corrispondono.";
            return null;
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password)
        SELECT ?, ?, ?
        WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = ?)";


        $this->statement = $this->conn->prepare($sql);
        $this->statement->bind_param("ssss", $name, $email, $hashed_password, $email);

        if ($this->statement->execute()) {

            if ($this->statement->affected_rows > 0) {
                return true;
            } else {
                Logger::info("Email gia usata");
                return null;
            }
        } else {
            return null;
        }
    }



    public function updateName($userId, $newName)
    {
        // Aggiorna il nome dell'utente
        $sql = "UPDATE users SET username = ? WHERE id = ?";
        $this->statement = $this->conn->prepare($sql);
        $this->statement->bind_param("si", $newName, $userId);

        return $this->statement->execute();
    }

    public function updatePassword($userId, $newPassword)
    {
        // Aggiorna la password dell'utente
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $this->statement = $this->conn->prepare($sql);
        $this->statement->bind_param("si", $hashedPassword, $userId);

        return $this->statement->execute();
    }

    public function deleteUser($userId)
    {
        // Elimina l'account utente
        $sql = "DELETE FROM users WHERE id = ?";
        $this->statement = $this->conn->prepare($sql);
        $this->statement->bind_param("i", $userId);

        return $this->statement->execute();
    }
}
