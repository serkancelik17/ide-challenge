<?php

namespace App\Rule;

use App\Entity\Order;

class DiscountCategoryToCheapest20PercentGte2Rule extends DiscountRuleAbstract implements DiscountRule
{
    CONST CATEGORY_ID = 1;
    CONST QTY = 2; //GTE compare
    CONST DISCOUNT_PERCENT = 20;

    public function __construct()
    {
        $this->setDiscountReason("CHEAPEST_".self::DISCOUNT_PERCENT."_PERCENT_GTE_".self::QTY);
    }

    public function handle(Order $order): self|bool
    {
        foreach ($order->getItems() AS $item)
        if ($item->getProduct()->getCategory()->getId() == self::CATEGORY_ID && $item->getQuantity >= self::QTY) {
            //En ucuz urune %20 indirim yap
        }
        return false;
    }

}