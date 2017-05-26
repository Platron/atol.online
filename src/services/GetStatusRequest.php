<?php

namespace Platron\Atol\services;

class GetStatusRequest extends BaseServiceRequest{
    
    /** @var string */
    protected $groupCode;
    /** @var string */
    protected $uuId;
    /** @var string */
    protected $token;
    
    /**
     * @inheritdoc
     */
    public function getRequestUrl() {
        return self::REQUEST_URL.$this->groupCode.'/report/'.$this->uuId.'?tokenid='.$this->token;
    }
    
    /**
     * @param string $groupCode
     * @param string $uuId
     * @param string $token
     */
    public function __construct($groupCode, $uuId, $token) {
        $this->groupCode = $groupCode;
        $this->uuId = $uuId;
        $this->token = $token;
    }
    
    public function getParameters() {
        return [];
    }
}
