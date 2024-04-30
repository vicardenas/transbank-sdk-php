<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class MallTransactionCaptureResponse
{
    public $authorizationCode;
    public $authorizationDate;
    public $capturedAmount;
    public $responseCode;

    public function __construct($json)
    {
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->capturedAmount = Utils::returnValueIfExists($json, 'captured_amount');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
    }

    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    public function getAuthorizationDate()
    {
        return $this->authorizationDate;
    }

    public function getCapturedAmount()
    {
        return $this->capturedAmount;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }

}
