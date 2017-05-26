<?php

namespace Platron\Atol\services;

class GetTokenRequest extends BaseServiceRequest {
    /** @var string */
    protected $login;
    /** @var string */
    protected $pass;
    
    /**
     * @inheritdoc
     */
    public function getRequestUrl() {
        return self::REQUEST_URL.'getToken';
    }
    
    /**
     * Получить токен для сессии
     * @param string $login
     * @param string $password
     */
    public function __construct($login, $password){
        $this->login = $login;
        $this->pass = $password;
    }

}
