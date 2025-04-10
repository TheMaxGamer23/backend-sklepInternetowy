<?php
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }

    $generatedKey = generateRandomString(50);

    $date = (array) new DateTime('now');
    $data = $date['date'];
    $method = "AES-256-CBC";
    $key = "encryptionKey123";
    $options = 0;
    $iv = '1234567891011121';

    $token = openssl_encrypt($data, $method, $key, $options, $iv);
?>