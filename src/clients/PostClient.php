<?php

namespace Platron\Atol\clients;

use Platron\Atol\clients\iClient;
use Platron\Atol\SdkException;
use Platron\Atol\services\BaseServiceRequest;

class PostClient implements iClient {
    
    /** @var string */
    protected $errorDescription;
    /** @var int */
    protected $errorCode;
    
    /**
     * @inheritdoc
     */
    public function sendRequest(BaseServiceRequest $service) {
        $requestParameters = $service->getParameters();
        
        $curl = curl_init($service->getRequestUrl());
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestParameters));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		
		if(curl_errno($curl)){
			throw new SdkException(curl_error($curl), curl_errno($curl));
		}
		
		return json_decode($response);
    }
}
