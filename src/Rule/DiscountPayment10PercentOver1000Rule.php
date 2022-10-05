<?php

namespace App\Rule;

use App\Entity\Order;

/**
 *  Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
 */
class DiscountPayment10PercentOver1000Rule extends DiscountRuleAbstract implements DiscountRule
{
    CONST TOTAL_AMOUNT = 1000;
    CONST DISCOUNT_PERCENT = 10;

    public function __construct()
    {
        $this->setDiscountReason(self::DISCOUNT_PERCENT."_PERCENT_OVER_".self::TOTAL_AMOUNT);
    }


    public function handle(Order $order) : self|bool
    {
        //Eger sipariş 1000 TL ustu ise %10 indirim uygula
        if($order->getTotal() >= self::TOTAL_AMOUNT) {
            $this->setDiscountAmount($order->getTotal() * (self::DISCOUNT_PERCENT/100));
            return $this;
        }
        return false;
    }
}