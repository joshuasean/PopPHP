<?php

require_once '../../bootstrap.php';

use Pop\Filter\String;

try {
    echo 'Slug: ' . String::slug('Testing, 1, 2, 3 | About Us | Hello World!', ' | ') . '<br /><br />' . PHP_EOL;

    echo 'Random String: ' . String::random(6, String::ALPHANUM, String::UPPER) . '<br /><br />' . PHP_EOL;

    $key = md5('Pop PHP Framework');

    $encrypted = String::encrypt('Hello World!', $key);
    echo 'Encrypted: ' . $encrypted . '<br /><br />' . PHP_EOL;

    $decrypted = String::decrypt($encrypted, $key);
    echo 'Decrypted: ' . $decrypted;
} catch (\Exception $e) {
    echo $e->getMessage();
}

