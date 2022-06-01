<?php

//if (!function_exists('customEncrypt')) {
//    function customEncrypt($data, $method = "e"): bool|string
//    {
//        $iv = '1234567891011121';
//        $hashKey = env("HASHKEY");
//        $ciphering = "AES-256-CBC";
//        $options = 0;
//
//
//        if ($method == "e") {
//            $result = openssl_encrypt($data, $ciphering, $hashKey, $options, $iv);
//        } else {
//            $result = openssl_decrypt($data, $ciphering, $hashKey, $options, $iv);
//        }
//        return $result;
//    }
//}
