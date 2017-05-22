<?php

namespace Platron\Atol\services;

class GetTokenResponse extends BaseServiceResponse {
    
    /** @var int */
    public $code;
    /** @var string */
    public $token;
    
    public function __construct(array $response) {
        if($response['code'] >= 2){
            $this->errorCode = $response['code'];
            $this->errorDescription = $response['text'];
        }
        
        parent::__construct($response);
    }
}
