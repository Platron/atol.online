<?php

namespace Platron\Atol\services;

class GetTokenResponse extends BaseServiceResponse {
    
    /** @var int */
    public $code;
    /** @var string */
    public $token;
 
    public function __construct(array $response) {
        foreach (get_object_vars($this) as $name => $value) {
			if (!empty($response[$name])) {
				$this->$name = $response[$name];
			}
		}
    }
    
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
