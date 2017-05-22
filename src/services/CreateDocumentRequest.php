<?php

namespace Platron\Atol\services;

class CreateDocumentRequest extends BaseServiceRequest{
    
    /** @var string идентификатор группы ККТ */
    protected $group_code;
    /** @var string тип операции */
    protected $operationType;
    /** @var string */
    protected $token;
    /** @var string */
    protected $paymentAddress;
    /** @var string */
    protected $customerEmail;
    /** @var int */
    protected $customerPhone;
    /** @var int */
    protected $inn;
    /** @var int */
    protected $paymentType;
    /** @var array Позиции в чеке */
    protected $products;
    
    const 
        OPERATION_TYPE_SELL = 'sell', // Приход
        OPERATION_TYPE_SELL_REFUND = 'sell_refund', // Возврат прихода
        OPERATION_TYPE_SELL_CORRECTION = 'sell_correction', // Коррекция прихода
        OPERATION_TYPE_BUY = 'buy', // Расход
        OPERATION_TYPE_BUY_REFUND = 'buy_refund', // Возврат расхода
        OPERATION_TYPE_BUY_CORRECTION = 'buy_correction'; // Коррекция расхода
    
    const 
        PAYMENT_TYPE_CASH = 0,
        PAYMENT_TYPE_ELECTRON = 1,
        PAYMNET_TYPE_PRE_PAID = 2,
        PAYMNET_TYPE_CREDIT = 3,
        PAYMNET_TYPE_OTHER = 4;
    
    /**
     * @inheritdoc
     */
    public function getRequestUrl() {
        return self::REQUEST_URL.$this->group_code.'/'.$this->operationType.'?tokenid='.$this->token;
    }
    
    /**
     * Добавить адрес магазина для оплаты (сайт)
     * @param string $address
     */
    public function addMerchantAddress($address){
        $this->paymentAddress = $address;
    }
    
    /**
     * Установить email покупателя
     */
    public function addCustomerEmail($email){
        $this->customerEmail = $email;
    }
    
    /**
     * Установить телефон покупателя
     * @param int $phone
     */
    public function addCustomerPhone($phone){
        $this->customerPhone = $phone;
    }
    
    /**
     * Установить inn
     * @param type $inn
     */
    public function addInn($inn){
        $this->inn = $inn;
    }
    
    /**
     * Установить тип платежа
     * @param int $paymentType
     */
    public function addPaymentType($paymentType){
        $this->paymentType = $paymentType;
    }
    
    public function addProduct(){
        
    }

    /**
     * @param string $operationType тип операции
     * @param string $token токен из запроса получения токена
     * @param string $groupCode идентификатор группы ККТ
     * @throws \Platron\Atol\SdkException
     */
    public function __construct($operationType, $token, $groupCode) {
        if(!in_array($operationType, $this->getOperationTypes())){
            throw new \Platron\Atol\SdkException('Wrong operation type');
        }
        
        $this->group_code = $groupCode;
        $this->token = $token;
    }
    
    protected function getOperationTypes(){
        return [
            self::OPERATION_TYPE_BUY,
            self::OPERATION_TYPE_BUY_CORRECTION,
            self::OPERATION_TYPE_BUY_REFUND,
            self::OPERATION_TYPE_SELL,
            self::OPERATION_TYPE_SELL_CORRECTION,
            self::OPERATION_TYPE_SELL_REFUND,
        ];
    }
    
    protected function getPaymentTypes(){
        return [
            self::PAYMENT_TYPE_CASH,
            self::PAYMENT_TYPE_ELECTRON,
            self::PAYMNET_TYPE_CREDIT,
            self::PAYMNET_TYPE_OTHER,
            self::PAYMNET_TYPE_PRE_PAID,
        ];
    }
}
