<?php

namespace Platron\Atol\tests\integration;

use Platron\Atol\clients\PostClient;
use Platron\Atol\data_objects\ReceiptPosition;
use Platron\Atol\services\CreateDocumentRequest;
use Platron\Atol\services\CreateDocumentResponse;
use Platron\Atol\services\GetStatusRequest;
use Platron\Atol\services\GetStatusResponse;
use Platron\Atol\services\GetTokenRequest;
use Platron\Atol\services\GetTokenResponse;

class ChainTokenCreateStatusTest extends IntegrationTestBase {
    public function testChainTokenCreateStatus(){
        $client = new PostClient();
        
        $tokenService = new GetTokenRequest($this->login, $this->password);
        $tokenResponse = new GetTokenResponse($client->sendRequest($tokenService));
        
        $this->assertTrue($tokenResponse->isValid());
        
        $receiptPosition = new ReceiptPosition('Test product', 10.00, 2, ReceiptPosition::TAX_VAT10);
        
        $createDocumentService = (new CreateDocumentRequest(CreateDocumentRequest::OPERATION_TYPE_BUY, $tokenResponse->token, $this->groupCode))
            ->addCustomerEmail('test@test.ru')
            ->addCustomerPhone('79268750000')
            ->addGroupCode($this->groupCode)
            ->addInn($this->inn)
            ->addMerchantAddress($this->paymentAddress)
            ->addOperationType(CreateDocumentRequest::OPERATION_TYPE_BUY)
            ->addPaymentType(CreateDocumentRequest::PAYMENT_TYPE_ELECTRON)
            ->addSno(CreateDocumentRequest::SNO_ESN)
            ->addReceiptPosition($receiptPosition);
        $createDocumentResponse = new CreateDocumentResponse($client->sendRequest($createDocumentService));
        
        $this->assertTrue($createDocumentResponse->isValid());
        
        $getStatusServise = new GetStatusRequest($this->groupCode, $createDocumentResponse->uuid, $tokenResponse->token);
        $getStatusResponse = new GetStatusResponse($client->sendRequest($getStatusServise));
        
        $this->assertTrue($getStatusResponse->isValid());
    }
}
