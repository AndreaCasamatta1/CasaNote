<?php

/**
 * Classe utile per la Validazione dei dati.
 */
class Validator
{

    function checkPosition($val)
    {
        return is_numeric($val) && $val > 0 && $val <= 9999;
    }

    function checkTextArea($val)
    {
        return (preg_match('/^[\r\n0-9a-zA-Z!?()%*+^$£àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð\/ ,.:;\'-]+$/', $val)) && strlen($val) >= 2;
    }

    function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    public function sanitizeMail($data)
    {
        // Rimuove spazi iniziali/ finali
        $data = trim($data);

        // Rimuove caratteri speciali
        $data = filter_var($data, FILTER_SANITIZE_EMAIL);

        return $data;
    }

    // Funzione per validare email
    public function validateEmail($data)
    {
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {

            return true;
        } else {
            return false;
        }
    }


}