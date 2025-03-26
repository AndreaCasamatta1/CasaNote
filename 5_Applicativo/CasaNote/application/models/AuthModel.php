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
        // Usa il prepared statement con bind_param
        $selectAccess = "SELECT id, password FROM users WHERE email = ?";
        $this->statement = $this->conn->prepare($selectAccess);  // Preparazione della query
        $this->statement->bind_param("s", $email);  // Bind del parametro (stringa 's' per l'email)
        $this->statement->execute();  // Esecuzione della query

        $result = $this->statement->get_result();
        $user = $result->fetch_assoc();

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

        // Usa il prepared statement per prevenire SQL injection
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $this->statement = $this->conn->prepare($sql);  // Preparazione della query
        $this->statement->bind_param("sss", $name, $email, $hashed_password);  // Bind dei parametri (tre stringhe 'sss')

        if ($this->statement->execute()) {
            return true;
        } else {
            return null;
        }
    }
}
