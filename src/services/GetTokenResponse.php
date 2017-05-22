<?php

namespace Platron\Atol\services;

class GetTokenResponse extends BaseServiceResponse {
    
    /** @var int */
    public $code;
    /** @var string */
    public $token;
    
    /**
     * @inheritdoc
     */
    public function isValid(array $response) {
        if($response['code'] >= 2){
            $this->errorCode = $response['code'];
            $this->errorDescription = $response['text'];
            return false;
        }
        return true;
    }
}
