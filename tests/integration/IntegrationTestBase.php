<?php

namespace Platron\Atol\tests\integration;

use Platron\Atol\tests\integration\MerchantSettings;

class IntegrationTestBase {
    /** @var string */
    protected $login;
    /** @var string */
    protected $password;
    /** @var int */
    protected $inn;
    /** @var string */
    protected $groupCode;
    /** @var string */
    protected $paymentAddress;
    
    public function __construct() {
        $this->login = MerchantSettings::LOGIN;
        $this->password = MerchantSettings::PASSWORD;
        $this->inn = MerchantSettings::INN;
        $this->groupCode = MerchantSettings::GROUP_ID;
        $this->paymentAddress = MerchantSettings::PAYMENT_ADDRESS;
    }
}
