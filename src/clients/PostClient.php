<?php

namespace Platron\Atol\clients;

use Platron\Atol\clients\iClient;
use Platron\Atol\SdkException;
use Platron\Atol\services\BaseServiceRequest;
use Psr\Log\LoggerInterface;

class PostClient implements iClient {
    
    const LOG_LEVEL = 0;
    
    /** @var string */
    protected $errorDescription;
    /** @var int */
    protected $errorCode;
    
    /**
     * @inheritdoc
     */
    public function sendRequest(BaseServiceRequest $service, LoggerInterface $logger = null) {
        $requestParameters = $service->getParameters();
        $requestUrl = $service->getRequestUrl();
        
        $curl = curl_init($service->getRequestUrl());
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestParameters));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
        
        if($logger){
            $logger->log(self::LOG_LEVEL, 'Requested url '.$requestUrl.' params '. print_r($requestParameters, true));
            $logger->log(self::LOG_LEVEL, 'Response '.$response);
        }
		
		if(curl_errno($curl)){
			throw new SdkException(curl_error($curl), curl_errno($curl));
		}
		
		return json_decode($response);
    }
}
