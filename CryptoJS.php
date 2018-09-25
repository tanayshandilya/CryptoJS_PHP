<?php

/**
* @author Tanay Shandilya
*/

class CryptoJS
{
    /**
    * @return mixed
    */

    public static function encrypt($pass, $data) {
        
        // Set a random salt
        
        $salt = substr(md5(mt_rand(), true), 8);
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $block - (strlen($data) % $block);
        $data = $data . str_repeat(chr($pad), $pad);
        
        // Setup encryption parameters
        
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, "", MCRYPT_MODE_CBC, "");
        $key_len =  mcrypt_enc_get_key_size($td);
        $iv_len =  mcrypt_enc_get_iv_size($td);
        $total_len = $key_len + $iv_len;
        $salted = '';
        $dx = '';
        
        // Salt the key and iv
        
        while (strlen($salted) < $total_len) {
            $dx = md5($dx.$pass.$salt, true);
            $salted .= $dx;
        }
        $key = substr($salted,0,$key_len);
        $iv = substr($salted,$key_len,$iv_len);
        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data = mcrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        
        //return chunk_split(base64_encode('Salted__' . $salt . $encrypted_data),32,"\r\n");
        
        return base64_encode('Salted__' . $salt . $encrypted_data);
    
    }

    /**
    * @return mixed
    */

    public static function decrypt($password, $edata) {
        
        $data = base64_decode($edata);
        $salt = substr($data, 8, 8);
        $ct = substr($data, 16);
        $rounds = 3;
        $data00 = $password.$salt;
        $md5_hash = array();
        $md5_hash[0] = md5($data00, true);
        $result = $md5_hash[0];

        // print "MD5-Hash[0] (Base64): " . base64_encode($result) . "\n";
        
        for ($i = 1; $i < $rounds; $i++) {
            $md5_hash[$i] = md5($md5_hash[$i - 1].$data00, true);
            $result .= $md5_hash[$i];
        }
        $key = substr($result, 0, 32);
        
        // print "Key (Base64): " . base64_encode($key) . "\n";
        
        $iv = substr($result, 32, 16);
        
        // print "IV (Base64): " . base64_encode($iv) . "\n";
        
        return openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    
    }
}
