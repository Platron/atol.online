<?php

namespace Platron\Atol\services;

use stdClass;

class GetStatusResponse extends BaseServiceResponse {
    
    const STATUS_DONE = 'done';
    
    /** @var string */
    public $status;
    /** @var float */
    public $total;
    /** @var int */
    public $fn_number;
    /** @var int */
    public $shift_number;
    /** @var string */
    public $receipt_datetime;
    /** @var int */
    public $fiscal_receipt_number;
    /** @var int */
    public $fiscal_document_number;
    /** @var string */
    public $ecr_registration_number;
    /** @var int */
    public $fiscal_document_attribute;
    
    /**
     * @inheritdoc
     */
    public function __construct(stdClass $response) {
        if(!empty($response->error->code)){
            $this->errorCode = $response->error->code;
            $this->errorDescription = $response->error->text;            
        }
        
        if($response->status == self::STATUS_DONE ){
            $this->status = self::STATUS_DONE;
            parent::__construct($response->payload);
        }
        else {
            $this->status = $response->status;
        }
    }
    
    /**
     * Создан ли уже чек
     * @return boolean
     */
    public function isReceiptReady(){
        return $this->status == self::STATUS_DONE;
    }
}
