<?php

function testLogin($params) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://earsivportal.efatura.gov.tr/earsiv-services/assos-login');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $json = json_decode($response, true);
    $msg = isset($json['messages'][0]['text']) ? $json['messages'][0]['text'] : substr($response, 0, 100);
    echo "Params: " . implode(',', array_keys($params)) . " -> " . $msg . "\n";
    curl_close($ch);
}

echo "Testing combinations...\n";

// 1. Standard
testLogin(['assoscmd' => 'login', 'username' => '1111111111', 'password' => 'test']);

// 2. With anonym
testLogin(['assoscmd' => 'login', 'username' => '1111111111', 'password' => 'test', 'anonym' => 'anonym']);

// 3. Userid instead of username
testLogin(['assoscmd' => 'login', 'userid' => '1111111111', 'password' => 'test']);

// 4. Sifre instead of password
testLogin(['assoscmd' => 'login', 'username' => '1111111111', 'sifre' => 'test']);

// 5. Parola instead of password
testLogin(['assoscmd' => 'login', 'username' => '1111111111', 'parola' => 'test']);
