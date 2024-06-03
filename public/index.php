<?php

require '../vendor/autoload.php';

use PhpStkPushDarajaApi\AccessToken;
use PhpStkPushDarajaApi\StkPush;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..'); 
$dotenv->load();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];



    $consumerKey = $_ENV['CONSUMER_KEY'];
    $consumerSecret = $_ENV['CONSUMER_SECRET'];
    $shortCode = $_ENV['SHORT_CODE'];
    $lipaNaMpesaOnlinePassKey = $_ENV['LIPA_NA_MPESA_PASSKEY'];
    $callbackUrl = $_ENV['CALLBACK_URL'];

    $accountReference = 'Account Reference';
    $transactionDesc = 'Payment for Order XYZ';

    $accessTokenGenerator = new AccessToken($consumerKey, $consumerSecret);
    $accessToken = $accessTokenGenerator->generate();

  
    $stkPush = new StkPush($shortCode, $lipaNaMpesaOnlinePassKey, $callbackUrl);
    $response = $stkPush->push($accessToken, $amount, $phone, $accountReference, $transactionDesc);

    if (isset($response->ResponseCode) && $response->ResponseCode == '0') {
        echo json_encode(['success' => true, 'response' => $response]);
    } else {
        echo json_encode(['success' => false, 'response' => $response]);
    }
    exit();
} else {
    header('Location: form.php');
    exit();
}
