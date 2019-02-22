<?php

namespace Platron\Atol\clients;

use Platron\Atol\clients\iClient;
use Platron\Atol\SdkException;
use Platron\Atol\services\BaseServiceRequest;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class PostClient implements iClient {

    /** @var string */
    protected $errorDescription;
    /** @var int */
    protected $errorCode;
    /** @var LoggerInterface */
    protected $logger;
    /** @var int */
    protected $connectionTimeout;
    
    /**
     * @param LoggerInterface $logger
     * @param int $connectionTimeout
     */
    public function __construct(LoggerInterface $logger = null, $connectionTimeout = 30) {
        $this->logger = $logger;
        $this->connectionTimeout = $connectionTimeout;
    }
    
    /**
     * @inheritdoc
     */
    public function sendRequest(BaseServiceRequest $service) {
        $requestParameters = $service->getParameters();
        $requestUrl = $service->getRequestUrl();
        
        $curl = curl_init($service->getRequestUrl());
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->connectionTimeout);
        
        if(!empty($requestParameters)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestParameters));
        }
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
        
        if($this->logger){
            $this->logger->log(LogLevel::INFO, 'Requested url '.$requestUrl.' params '. json_encode($requestParameters));
            $this->logger->log(LogLevel::INFO, 'Response '.$response);
        }
		
		if(curl_errno($curl)){
			throw new SdkException(curl_error($curl), curl_errno($curl));
		}
        
        $decodedResponse = json_decode($response);
        if(empty($decodedResponse)){
            throw new SdkException('Atol error. Empty response or not json response');
        }
		
		return $decodedResponse;
    }
}
