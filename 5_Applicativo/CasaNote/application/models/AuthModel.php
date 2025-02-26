<?php

class AuthModel
{
    private $conn;
    private $statement;
    public function __construct()
    {
        $this->conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
    }
    public function getData($email, $password)
    {
        $selectAccess = "SELECT id, password FROM users WHERE email='$email'";
        $this->statement = $this->conn->query($selectAccess);
        var_dump($this->statement);
        $user = $this->statement->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
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
        $sql = "INSERT INTO users (username, email, password) VALUES ('$name', '$email', '$hashed_password')";
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return null;
        }
    }
}
