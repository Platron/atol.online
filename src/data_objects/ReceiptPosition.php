<?php

namespace Platron\Atol\data_objects;

class ReceiptPosition extends BaseDataObject{
    
    const 
        TAX_NONE = 'none',
        TAX_VAT0 = 'vat0',
        TAX_VAT10 = 'vat10',
        TAX_VAT18 = 'vat18',
        TAX_VAT110 = 'vat110',
        TAX_VAT118 = 'vat118';
    
    /** @var float */
    protected $sum;
    /** @var string */
    protected $tax;
    /** @var float */
    protected $tax_sum;
    /** @var string */
    protected $name;
    /** @var float */
    protected $price;
    /** @var int */
    protected $quantity;
    
    /**
     * @param type $name Описание товара
     * @param type $price Цена единицы товара
     * @param type $quantity Количество товара
     * @param type $vat Налоговая ставка из констант
     * @param type $sum Сумма количества товаров. Передается если количество * цену товара не равно sum
     * @throws \Platron\Atol\SdkException
     */
    public function __construct($name, $price, $quantity, $vat, $sum = null) {
        if(!in_array($vat, $this->getVates())){
            throw new \Platron\Atol\SdkException('Wrong vat');
        }
        
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->tax = $vat;
        if(!$sum){
            $this->sum = $this->quantity * $this->price;
        }
        else {
            $this->sum = $sum;
        }
        $this->tax_sum = $this->getVatAmount($this->sum, $vat);
    }
    
    /**
     * Получить все возможные налоговые ставки
     */
    protected function getVates(){
        return [
            self::TAX_NONE,
            self::TAX_VAT0,
            self::TAX_VAT10,
            self::TAX_VAT110,
            self::TAX_VAT118,
            self::TAX_VAT18,
        ];
    }
    
    /**
     * Получить сумму налога
     * @param float $amount
     */
    protected function getVatAmount($amount, $vat){
        switch($vat){
            case self::TAX_NONE:
            case self::TAX_VAT0:
                return round(0, 2);
            case self::TAX_VAT10:
                return round($amount * 0.1, 2);
            case self::TAX_VAT18:
                return round($amount * 0.18, 2);
            case self::TAX_VAT110:
                return round($amount * 10 / 110, 2);
            case self::TAX_VAT118:
                return round($amount * 18 / 118, 2);
            default :
                throw new \Platron\Atol\SdkException('Unknown vat');
        }
    }
}
