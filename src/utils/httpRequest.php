<?php
namespace uqpay\payment\sdk\utils;

use GuzzleHttp\Client;
use uqpay\payment\sdk\exception\UqpayPayFailException;
use uqpay\payment\sdk\result\BaseResult;

class httpRequest
{
    function httpArrayPost($url, $data)
    {
        $client = new Client();
        $response = $client->post($url, ["form_params" => $data]);
        $code = $response->getStatusCode();
        if($code>=200&&$code<300){
            return json_decode((string)$response->getBody(),true);
        }
        $result = json_decode((string)$response->getBody(),true);
        $baseResult = new BaseResult($result);
        throw new UqpayPayFailException($baseResult->code,$baseResult->message);
    }
}