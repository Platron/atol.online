<?php

namespace Platron\Atol\services;

use stdClass;

class CreateDocumentResponse extends BaseServiceResponse {
    
    /** @var string Уникальный идентификатор */
    public $uuid;
    
    /** @var string */
    public $status;
        
    public function __construct(stdClass $response) {
        if(!empty($response->error->code)){
            $this->errorCode = $response->error->code;
            $this->errorDescription = $response->error->text;
        }
        
        parent::__construct($response);
    }
}
