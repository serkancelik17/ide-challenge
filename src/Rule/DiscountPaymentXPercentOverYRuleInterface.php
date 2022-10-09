<?php

namespace App\Rule;

use App\Entity\Order;

/**
 *  Toplam X TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %Y indirim kazanır.
 */
class DiscountPaymentXPercentOverYRuleInterface extends DiscountRuleAbstract implements DiscountRuleInterface
{
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
        //if amount > X
        if($order->getTotal() >= $this->totalAmount) {
            $this->setDiscountAmount($order->getTotal() * $this->discount);
            return $this;
        }
        return false;
    }
}