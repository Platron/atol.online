<?php

namespace Platron\Atol\services;

use Platron\Atol\data_objects\ReceiptPosition;
use Platron\Atol\SdkException;

class CreateDocumentRequest extends BaseServiceRequest{
    
    /** @var string идентификатор группы ККТ */
    protected $groupCode;
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
    /** @var ReceiptPosition[] Позиции в чеке */
    protected $receiptPositions;
    /** @var string */
    protected $externalId;
    /** @var string */
    protected $sno;
    
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
    
    const 
        SNO_OSN = 'osn',
        SNO_USN_INCOME = 'usn_income',
        SNO_USN_INCOME_OUTCOME = 'usn_income_outcome',
        SNO_ENDV = 'envd',
        SNO_ESN = 'esn',
        SNO_PATENT = 'patent';
    
    /**
     * @inheritdoc
     */
    public function getRequestUrl() {
        return self::REQUEST_URL.$this->groupCode.'/'.$this->operationType.'?tokenid='.$this->token;
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
     * Установить тип платежа. Из констант
     * @param int $paymentType
     */
    public function addPaymentType($paymentType){
        if(!in_array($paymentType, $this->getPaymentTypes())){
            throw new SdkException('Wrong payment type');
        }
        
        $this->paymentType = $paymentType;
    }
    
    /**
     * Добавить позицию в чек
     * @param ReceiptPosition $position
     */
    public function addReceiptPosition(ReceiptPosition $position){
        $this->receiptPositions[] = $position;
    }
    
    /**
     * Установить номер чека, если это коррекция
     * @param string $externalId
     */
    public function addExternalId($externalId){
        $this->externalId = $externalId;
    }
    
    /**
     * Добавить SNO. Если у организации один тип - оно не обязательное. Из констант
     * @param string $sno
     */
    public function assSno($sno){
        if(!in_array($sno, $this->getSnoTypes())){
            throw new SdkException('Wrong sno type');
        }
        
        $this->sno = $sno;
    }

    /**
     * @param string $operationType Тип операции. Из констант
     * @param string $token Токен из запроса получения токена
     * @param string $groupCode Идентификатор группы ККТ
     * @throws SdkException
     */
    public function __construct($operationType, $token, $groupCode) {
        if(!in_array($operationType, $this->getOperationTypes())){
            throw new SdkException('Wrong operation type');
        }
        
        $this->groupCode = $groupCode;
        $this->token = $token;
    }
    
    public function getParameters() {
        $params = [
            'timestamp' => date('d.m.Y H:i:s'),
            'service' => [
                'inn' => $this->inn,
                'callback_url' => '',
                'payment_address' => $this->paymentAddress,
            ],
            'attributes' => [
                'email' => $this->customerEmail,
                'phone' => $this->customerPhone,
            ],
            'external_id' => $this->externalId,
        ];
        
        $totalAmount = 0;
        foreach($this->receiptPositions as $receiptPosition){
            $totalAmount += $receiptPosition->getPositionSum();
            $params['items'][] = $receiptPosition->getParameters();
        }
        
        $params['total'] = $totalAmount;
        $params['payments'] = [
            'sum' => $totalAmount,
            'type' => $this->paymentType,
        ];
        
        return $params;
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
    
    protected function getSnoTypes(){
        return [
            self::SNO_ENDV,
            self::SNO_ESN,
            self::SNO_OSN,
            self::SNO_PATENT,
            self::SNO_USN_INCOME,
            self::SNO_USN_INCOME_OUTCOME,
        ];
    }
}
