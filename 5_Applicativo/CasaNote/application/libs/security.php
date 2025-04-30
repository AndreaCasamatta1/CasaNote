<?php

namespace libs;

class security
{
    private $encryption_key = 'tkXxN68c5ml4TTHMzrgV8Xt7pXjJHcAo3WN1UVZgF3Iv5iZ+iRRHOTDPXCykibFg';
    private $cipher = 'AES-256-CBC';
    private $iv_size;
    public function __construct()
    {
        $this->iv_size = openssl_cipher_iv_length($this->cipher);
    }
    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes($this->iv_size);
        $encrypted = openssl_encrypt($data, $this->cipher, $this->encryption_key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($data)
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, $this->iv_size);
        $encrypted = substr($data, $this->iv_size);
        return openssl_decrypt($encrypted, $this->cipher, $this->encryption_key, 0, $iv);
    }
}