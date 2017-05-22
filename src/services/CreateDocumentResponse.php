<?php

namespace Platron\Atol\services;

class CreateDocumentResponse extends BaseServiceResponse {
    
    /** @var string Уникальный идентификатор */
    public $uuid;
    
    /** @var string */
    public $status;
    
    /**
     * @inheritdoc
     */
    public function isValid(array $response) {
        if(!empty($response['error']['code'])){
            $this->errorCode = $response['error']['code'];
            $this->errorDescription = $response['error']['text'];
            return false;
        }
        
        return true;
    }
}
