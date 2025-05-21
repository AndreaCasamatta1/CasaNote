<?php

class authModel
{
    private $conn;
    private $statement;
    private $security;

    public function __construct()
    {
        require_once "application/libs/security.php";
        $this->conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
        $this->security = new \libs\security();
    }

    // 1. getUserInfo Method
    public function getUserInfo($email)
    {
        \logger::info("Metodo: getUserInfo");
        // Prepara la query per recuperare info utente tramite email
        $selectUserInfo = "SELECT id, email, username,password FROM users WHERE email = ?";
        $this->statement = $this->conn->prepare($selectUserInfo);
        $this->statement->bind_param("s", $email);
        $this->statement->execute();

        $result = $this->statement->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            \logger::info("Utente info recuperata: " . print_r($user, true));
            return $user;
        } else {
            \logger::info("Nessun utente trovato con email: $email");
            return null;
        }
    }

    public function verifyUser($email, $password)
    {
        \logger::info("Metodo: verifyUser");
        // Prepara la query per recuperare id, password e username dall'email
        $selectAccess = "SELECT id, password, username FROM users WHERE email = ?";


        $this->statement = $this->conn->prepare($selectAccess);
        $this->statement->bind_param("s", $email);
        $this->statement->execute();

        $result = $this->statement->get_result();
        $user = $result->fetch_assoc();

        logger::info("Verifica utente: " . print_r($user, true));

        // Verifica che l'utente esista e la password corrisponda
        if ($user && password_verify($password, $user['password'])) {
            logger::info("Password verificata per l'utente: " . $user['username']);
            return $user;
        } else {
            \logger::info("Autenticazione fallita");
            return null;
        }
    }


    public function registerUser($name, $email, $password, $password_confirm)
    {
        \logger::info("Metodo: registerUser");
        // Verifica che le password coincidano
        if ($password !== $password_confirm) {
            echo "Le password non corrispondono.";
            logger::info("password non confermata, 2 password diverse");
            return null;
        }
        // Hash della password per sicurezza
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Query per inserire utente solo se l'email non esiste già
        $sql = "INSERT INTO users (username, email, password)
        SELECT ?, ?, ?
        WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = ?)";


        $this->statement = $this->conn->prepare($sql);
        $this->statement->bind_param("ssss", $name, $email, $hashed_password, $email);

        if ($this->statement->execute()) {
            // Controlla se l'inserimento è avvenuto (email non presente)
            if ($this->statement->affected_rows > 0) {
                //email in database non presente, registrazione fatta
                logger::info("Registrato con successo");
                return true;
            } else {
                //email nel database presente, registrazione non fatta
                logger::info("Email gia usata");
                return null;
            }
        } else {
            return null;
        }
    }


    public function updateName($userId, $newName)
    {
        \logger::info("Metodo: updateName");
        // Prepara la query per aggiornare il nome utente
        $sql = "UPDATE users SET username = ? WHERE id = ?";
        $this->statement = $this->conn->prepare($sql);
        // Associa i parametri: nuovo nome e id utente
        $this->statement->bind_param("si", $newName, $userId);
        logger::info("Nome cambiato con successo");
        // Esegue la query e salva il risultato
        return $this->statement->execute();
    }

    public function updatePassword($userId, $newPassword, $oldPassword)
    {
        \logger::info("Metodo: updatePassword");
        // Recupera la password attuale dell'utente dal database
        $selectPassword = "SELECT password FROM users WHERE id = ?";
        $this->statement = $this->conn->prepare($selectPassword);
        $this->statement->bind_param("i", $userId);
        $this->statement->execute();
        $result = $this->statement->get_result();
        $user = $result->fetch_assoc();
        $currentPassword = $user['password'];
        // Verifica che la vecchia password fornita corrisponda a quella attuale
        if (password_verify($oldPassword, $currentPassword)) {
            logger::info("password confermata e corretta, password uguali");
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            // Aggiorna la password nel DB
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $this->statement = $this->conn->prepare($sql);
            $this->statement->bind_param("si", $hashedPassword, $userId);
            logger::info("password cambiata con successo");
            return $this->statement->execute();
        }
        logger::info("password non confermata, 2 password diverse");
        return false;
    }


    public function deleteUser($userId)
    { \logger::info("Metodo: deleteUser");
        // Prepara la query per eliminare l'utente con l'id specificato
        $sql = "DELETE FROM users WHERE id = ?";
        $this->statement = $this->conn->prepare($sql);
        // Associa il parametro userId alla query
        $this->statement->bind_param("i", $userId);
        logger::info("Utente eliminato con successo");
        return $this->statement->execute();
    }
}
