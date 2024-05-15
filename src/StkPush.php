<?php

namespace PhpStkPushDarajaApi;

class StkPush
{
    private $shortCode;
    private $lipaNaMpesaOnlinePassKey;
    private $callbackUrl;

    public function __construct($shortCode, $lipaNaMpesaOnlinePassKey, $callbackUrl)
    {
        $this->shortCode = $shortCode;
        $this->lipaNaMpesaOnlinePassKey = $lipaNaMpesaOnlinePassKey;
        $this->callbackUrl = $callbackUrl;
    }

    public function push($accessToken, $amount, $phoneNumber, $accountReference, $transactionDesc)
    {
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        
        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortCode . $this->lipaNaMpesaOnlinePassKey . $timestamp);

        $curl_post_data = [
            'BusinessShortCode' => $this->shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phoneNumber,
            'PartyB' => $this->shortCode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }
}
