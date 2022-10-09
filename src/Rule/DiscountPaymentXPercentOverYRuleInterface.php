<?php

namespace App\Rule;

use App\Entity\Order;

/**
 *  Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
 */
class DiscountPaymentXPercentOverYRuleInterface extends DiscountRuleAbstract implements DiscountRuleInterface
{
    /** @TODO will be remove */
//    CONST TOTAL_AMOUNT = 1000;
//    CONST DISCOUNT = 0.1;
    private float $totalAmount;
    private float $discount;

    public function __construct(float $totalAmount, float $discount)
    {
        $this->totalAmount = $totalAmount;
        $this->discount = $discount;

        $this->setDiscountReason(($this->discount * 100)."_PERCENT_OVER_".$this->totalAmount);
    }


    public function handle(Order $order) : self|bool
    {
        //Eger sipariş 1000 TL ustu ise %10 indirim uygula
        if($order->getTotal() >= $this->totalAmount) {
            $this->setDiscountAmount($order->getTotal() * $this->discount);
            return $this;
        }
        return false;
    }
}