<?php

namespace App\Rule;

use App\Entity\Order;
use App\Entity\OrderItem;

/**
 * 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.
 */
class DiscountCategoryToCheapest20PercentGte2Rule extends DiscountRuleAbstract implements DiscountRule
{
    CONST CATEGORY_ID = 1;
    CONST QTY = 2; //GTE compare value
    CONST DISCOUNT = 0.20;

    public function __construct()
    {
        $this->setDiscountReason("CHEAPEST_".self::DISCOUNT."_PERCENT_GTE_".self::QTY);
    }

    public function handle(Order $order): self|bool
    {
        /** @var OrderItem[] $categoryItems */
        $categoryItems = [];

        // butun itemlar icin don ve ilgili kategoridekileri bu
        foreach ($order->getItems() AS $item) {
            if ($item->getProduct()->getCategory() == self::CATEGORY_ID) {
                $categoryItems[] = $item;
            }
        }

        //Belirtilen kategoriden 2 veya daha fazla varsa
        if ( count($categoryItems) >= self::QTY) {
            // Fiyata gore sirala
            usort($categoryItems,function ($a,$b){return $a->getUnitPrice() > $b->getUnitPrice();});
            //en ucuz urunu bul
            $this->setDiscountAmount($categoryItems[0]->getTotal() * self::DISCOUNT);

            return $this;
        }
        return false;
    }

}