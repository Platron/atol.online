<?php

namespace Platron\Atol\services;

use Platron\Atol\data_objects\ReceiptPosition;
use Platron\Atol\SdkException;

/**
 * Все парараметры обязательны для заполнения, кроме external_id. Он нужен только для корректировки чека. В наборе email|phone требуется хотя бы одно значение
 */
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
        PAYMENT_TYPE_CASH = 0, // наличными
        PAYMENT_TYPE_ELECTRON = 1, // электронными
        PAYMNET_TYPE_PRE_PAID = 2, // предварительная оплата (аванс)
        PAYMNET_TYPE_CREDIT = 3, // последующая оплата (кредит)
        PAYMNET_TYPE_OTHER = 4,// иная форма оплаты (встречное предоставление
        PAYMNET_TYPE_ADDITIONAL = 5; // расширенный типы оплаты. для каждого фискального типа оплаты можно указать расширенный тип оплаты
    
    const 
        SNO_OSN = 'osn', // общая СН
        SNO_USN_INCOME = 'usn_income', // упрощенная СН (доходы)
        SNO_USN_INCOME_OUTCOME = 'usn_income_outcome', // упрощенная СН (доходы минус расходы)
        SNO_ENDV = 'envd', // единый налог на вмененный доход
        SNO_ESN = 'esn', // единый сельскохозяйственный налог
        SNO_PATENT = 'patent'; // патентная СН
    
    /**
     * @inheritdoc
     */
    public function getRequestUrl() {
        return self::REQUEST_URL.$this->groupCode.'/'.$this->operationType.'?tokenid='.$this->token;
    }
    
    /**
     * Добавить адрес магазина для оплаты (сайт)
     * @param string $address
     * @return CreateDocumentRequest
     */
    public function addMerchantAddress($address){
        $this->paymentAddress = $address;
        return $this;
    }
    
    /**
     * Установить email покупателя
     * @param string $email
     * @return CreateDocumentRequest
     */
    public function addCustomerEmail($email){
        $this->customerEmail = $email;
        return $this;
    }
    
    /**
     * Установить телефон покупателя
     * @param int $phone
     * @return CreateDocumentRequest
     */
    public function addCustomerPhone($phone){
        $this->customerPhone = $phone;
        return $this;
    }
    
    /**
     * Установить inn
     * @param type $inn
     * @return CreateDocumentRequest
     */
    public function addInn($inn){
        $this->inn = $inn;
        return $this;
    }
    
    /**
     * Установить тип платежа. Из констант
     * @param int $paymentType
     * throws SdkException
     * @return CreateDocumentRequest
     */
    public function addPaymentType($paymentType){
        if(!in_array($paymentType, $this->getPaymentTypes())){
            throw new SdkException('Wrong payment type');
        }
        
        $this->paymentType = $paymentType;
        return $this;
    }
    
    /**
     * Добавить позицию в чек
     * @param ReceiptPosition $position
     * @return CreateDocumentRequest
     */
    public function addReceiptPosition(ReceiptPosition $position){
        $this->receiptPositions[] = $position;
        return $this;
    }
    
    /**
     * Установить номер чека, если это коррекция
     * @param string $externalId
     * @return CreateDocumentRequest
     */
    public function addExternalId($externalId){
        $this->externalId = $externalId;
        return $this;
    }
    
    /**
     * Добавить SNO. Если у организации один тип - оно не обязательное. Из констант
     * @param string $sno
     * @throws SdkException
     * @return CreateDocumentRequest
     */
    public function addSno($sno){
        if(!in_array($sno, $this->getSnoTypes())){
            throw new SdkException('Wrong sno type');
        }
        
        $this->sno = $sno;
        return $this;
    }

    /**
     * @param string $token Токен из запроса получения токена
     * @return CreateDocumentRequest
     */
    public function __construct($token) {
        $this->token = $token;
        return $this;
    }
    
    /**
     * Добавить тип операции
     * @param string $operationType Тип операции. Из констант
     * @throws SdkException
     * @return CreateDocumentRequest
     */
    public function addOperationType($operationType){
        if(!in_array($operationType, $this->getOperationTypes())){
            throw new SdkException('Wrong operation type');
        }
        
        $this->operationType = $operationType;
        return $this;
    }
    
    /**
     * Добавить код группы
     * @param string $groupCode Идентификатор группы ККТ
     * @return CreateDocumentRequest
     */
    public function addGroupCode($groupCode){
        $this->groupCode = $groupCode;
        return $this;
    }
    
    public function getParameters() {        
        $totalAmount = 0;
        $items = [];
        foreach($this->receiptPositions as $receiptPosition){
            $totalAmount += $receiptPosition->getPositionSum();
            $items[] = $receiptPosition->getParameters();
        }

        $params = [
            'timestamp' => date('d.m.Y H:i:s'),
            'external_id' => $this->externalId,
            'service' => [
                'inn' => $this->inn,
                'callback_url' => '',
                'payment_address' => $this->paymentAddress,
            ],
            'receipt' => [
                'items' => $items,
                'total' => $totalAmount,
                'payments' => [
                    'sum' => $totalAmount,
                    'type' => $this->paymentType,
                ],
                'attributes' => [
                    'email' => $this->customerEmail ? : '',
                    'phone' => $this->customerPhone ? : '',
                ],
            ],
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
