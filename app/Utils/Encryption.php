<?php

namespace App\Utils;

class Encryption
{
    protected $key;
    protected $cipher = 'AES-256-CBC';
    
    public function __construct($key = null)
    {
        $this->key = $key ?? config('app.encryption_key');
    }
    
    public function encrypt($data)
    {
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        $encrypted = openssl_encrypt($data, $this->cipher, $this->key, 0, $iv);
        
        return base64_encode($encrypted . '::' . $iv);
    }
    
    public function decrypt($data)
    {
        list($encrypted, $iv) = explode('::', base64_decode($data), 2);
        
        return openssl_decrypt($encrypted, $this->cipher, $this->key, 0, $iv);
    }
    
    public function hash($data)
    {
        return hash('sha256', $data);
    }
    
    public function verifyHash($data, $hash)
    {
        return hash_equals($this->hash($data), $hash);
    }
}
