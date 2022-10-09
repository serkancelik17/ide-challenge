<?php

namespace App\Rule;

use App\Entity\Order;
use App\Entity\OrderItem;

/**
 * X ID'li kategoriden Y veya daha fazla ürün satın alındığında, en ucuz ürüne %Z indirim yapılır.
 */
class DiscountCategoryToCheapestXPercentGteYRule extends DiscountRuleAbstract implements DiscountRuleInterface
{
//    @TODO will be remove
//    CONST CATEGORY_ID = 1;
//    CONST QTY = 2; //GTE compare value
//    CONST DISCOUNT = 0.20;

    private int $categoryId;
    private int $quantity;
    private float $discount;

    public function __construct(int $categoryId, int $quantity, float $discount)
    {
        $this->categoryId = $categoryId;
        $this->quantity = $quantity;
        $this->discount = $discount;

        $this->setDiscountReason("BUY_".$this->quantity."_PRODUCT_IN_CAT_".$this->categoryId."_".($this->discount * 100)."_PERCENT");
    }

    public function handle(Order $order): self|bool
    {
        /** @var OrderItem[] $categoryItems */
        $categoryItems = [];

        // butun itemlar icin don ve ilgili kategoridekileri bu
        foreach ($order->getItems() AS $item) {
            if ($item->getProduct()->getCategory() == $this->categoryId) {
                $categoryItems[] = $item;
            }
        }

        //Belirtilen kategoriden 2 veya daha fazla varsa
        if ( count($categoryItems) >= $this->quantity) {
            // Fiyata gore sirala
            usort($categoryItems,function ($a,$b){return $a->getUnitPrice() > $b->getUnitPrice();});
            //en ucuz urunu bul
            $this->setDiscountAmount($categoryItems[0]->getTotal() * $this->discount);

            return $this;
        }
        return false;
    }

}