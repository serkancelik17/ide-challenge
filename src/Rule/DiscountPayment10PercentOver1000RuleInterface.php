<?php

namespace App\Rule;

use App\Entity\Order;

/**
 *  Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
 */
class DiscountPayment10PercentOver1000RuleInterface extends DiscountRuleAbstract implements DiscountRuleInterface
{
    CONST TOTAL_AMOUNT = 1000;
    CONST DISCOUNT = 0.1;

    public function __construct()
    {
        $this->setDiscountReason((self::DISCOUNT * 100)."_PERCENT_OVER_".self::TOTAL_AMOUNT);
    }


    public function handle(Order $order) : self|bool
    {
        //Eger sipariş 1000 TL ustu ise %10 indirim uygula
        if($order->getTotal() >= self::TOTAL_AMOUNT) {
            $this->setDiscountAmount($order->getTotal() * self::DISCOUNT);
            return $this;
        }
        return false;
    }
}