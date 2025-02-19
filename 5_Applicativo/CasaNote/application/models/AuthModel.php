<?php

class AuthModel
{
    private $conn;
    private $statement;
    public function __construct()
    {
        $this->conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
    }
    public function getData($name, $password)
    {
        $selectAccesso = "SELECT id, password FROM users WHERE email='$name'";
        $this->statement = $this->conn->query($selectAccesso);
        $user = $this->statement->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return null;
        }
    }
    public function registerUser($name, $password, $password_confirm)
    {
        if ($password !== $password_confirm) {
            echo "Le password non corrispondono.";
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password) VALUES ('$name', '$hashed_password')";
        if ($this->conn->query($sql) === TRUE) {
            echo "Registrazione completata con successo!";
        } else {
            echo "Errore durante la registrazione: " . $this->conn->error;
        }
    }
}
