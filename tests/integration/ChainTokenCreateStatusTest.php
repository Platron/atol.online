<?php

namespace Platron\Atol\tests\integration;

use Platron\Atol\clients\PostClient;
use Platron\Atol\services\GetTokenRequest;
use Platron\Atol\services\GetTokenResponse;

class ChainTokenCreateStatusTest extends IntegrationTestBase {
    public function testChainTokenCreateStatus(){
        $client = new PostClient();
        
        $tokenService = new GetTokenRequest($this->login, $this->password);
        $response = new GetTokenResponse($client->sendRequest($tokenService));
        
        $this->assertTrue($response->isValid());
        
        
    }
}
