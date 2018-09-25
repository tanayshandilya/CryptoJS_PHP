<?php

require 'CryptoJS.php';

$cjs = new CryptoJS;

$plainText = 'This is a plain text';
$key = 'YOUR_SECRET_KEY';
$cypherText = $cjs->encrypt( $key, $plainText );
$decryptedText = $cjs->decrypt( $key, $cypherText );

echo '<pre>';
echo 'Original Text  : ' . $cypherText . "\n";
echo 'Secret Key     : ' . $cypherText . "\n";
echo 'Encrypted Text : ' . $cypherText . "\n";
echo 'Decrypted Text : ' . $decryptedText . "\n";