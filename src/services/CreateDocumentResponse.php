<?php

namespace Platron\Atol\services;

class CreateDocumentResponse extends BaseServiceResponse {
    
    /** @var string Уникальный идентификатор */
    public $uuid;
    
    /** @var string */
    public $status;
        
    public function __construct(array $response) {
        if(!empty($response->error->code)){
            $this->errorCode = $response->error->code;
            $this->errorDescription = $response->error->text;
        }
        
        parent::__construct($response);
    }
}
