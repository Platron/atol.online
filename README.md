Platron Atol SDK
===============
## Установка

Проект предполагает через установку с использованием composer
<pre><code>composer require platron/atol-online</pre></code>

## Тесты
Для работы тестов необходим PHPUnit, для установки необходимо выполнить команду
```
composer install
```
Для того, чтобы запустить интеграционные тесты нужно скопировать файл tests/integration/MerchantSettingsSample.php удалив 
из названия Sample и вставив настройки магазина. После выполнить команду из корня проекта
```
vendor/bin/phpunit tests/integration
```

## Примеры использования

### 1. Запрос токена

<pre><code>
    $client = new Platron\Atol\clients\PostClient();
        
    $tokenService = new Platron\Atol\services\GetStatusRequest('login', 'password');
    $tokenResponse = new Platron\Atol\services\GetStatusResponse($client->sendRequest($tokenService));
</pre></code>

### 2. Создание чека

<pre><code>
    $client = new Platron\Atol\clients\PostClient();
    $receiptPosition = new Platron\Atol\data_objects\ReceiptPosition('Test product', 10.00, 2, ReceiptPosition::TAX_VAT10);
 
    $createDocumentService = (new Platron\Atol\services\CreateDocumentRequest('token'))
        ->addCustomerEmail('test@test.ru')
        ->addCustomerPhone('79268750000')
        ->addGroupCode('groupCode')
        ->addInn('inn')
        ->addMerchantAddress('paymentAddress')
        ->addOperationType(Platron\Atol\services\CreateDocumentRequest::OPERATION_TYPE_BUY)
        ->addPaymentType(Platron\Atol\services\CreateDocumentRequest::PAYMENT_TYPE_ELECTRON)
        ->addSno(Platron\Atol\services\CreateDocumentRequest::SNO_ESN)
        ->addReceiptPosition($receiptPosition);
    $createDocumentResponse = new Platron\Atol\services\CreateDocumentResponse($client->sendRequest($createDocumentService));
</pre></code>

### 3. Запрос статуса 

<pre><code>
    $client = new Platron\Atol\clients\PostClient();
    $getStatusServise = new Platron\Atol\services\GetTokenRequest('groupCode', 'uuid', 'token');
    $getStatusResponse = new Platron\Atol\services\GetTokenResponse($client->sendRequest($getStatusServise));
</pre></code>