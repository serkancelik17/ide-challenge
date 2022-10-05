<?php

namespace App\Rule;

use App\Entity\Order;

/**
 * 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.
 */
class DiscountCategoryToCheapest20PercentGte2Rule extends DiscountRuleAbstract implements DiscountRule
{
    CONST CATEGORY_ID = 1;
    CONST QTY = 2; //GTE compare value
    CONST DISCOUNT_PERCENT = 20;

    public function __construct()
    {
        $this->setDiscountReason("CHEAPEST_".self::DISCOUNT_PERCENT."_PERCENT_GTE_".self::QTY);
    }

    public function handle(Order $order): self|bool
    {

        foreach ($order->getItems() AS $item)
        if ($item->getProduct()->getCategory() == self::CATEGORY_ID && $item->getQuantity() >= self::QTY) {
            //@TODO En ucuz urune %20 indirim yap
            //en ucuzz urunu bul
            return $this;
        }
        return false;
    }

}