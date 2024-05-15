<?php

require '../vendor/autoload.php';

use PhpStkPushDarajaApi\AccessToken;
use PhpStkPushDarajaApi\StkPush;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];

    // Credentials
    $consumerKey = '2hmSFgqVKZmDnBfNq3KWb8UeO4vms2oWrG97RQ1aZ5G7eUN0';
    $consumerSecret = 'CTd2bLaExMBixoAXMUiLNDKvwRfZuIK9NMs6pFxNMF4Ae60gkxQr7rkuFWKJYl51';
    $shortCode = '174379';
    $lipaNaMpesaOnlinePassKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    $callbackUrl = 'https://yourdomain.com/callback'; // Your callback URL

    // Transaction details
    $accountReference = 'Account Reference';
    $transactionDesc = 'Payment for Order XYZ';

    // Generates access token
    $accessTokenGenerator = new AccessToken($consumerKey, $consumerSecret);
    $accessToken = $accessTokenGenerator->generate();

    //makes an stk push request
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
