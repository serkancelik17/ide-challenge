<?php

namespace App\Rule;

use App\Entity\Order;
use App\Entity\OrderItem;

/**
 * X ID'li kategoriden Y veya daha fazla ürün satın alındığında, en ucuz ürüne %Z indirim yapılır.
 */
class DiscountCategoryToCheapestXPercentGteYRule extends DiscountRuleAbstract implements DiscountRuleInterface
{
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

        // if matched category
        foreach ($order->getItems() AS $item) {
            if ($item->getProduct()->getCategoryId() == $this->categoryId) {
                $categoryItems[] = $item;
            }
        }

        // get 2 for the category
        if ( count($categoryItems) >= $this->quantity) {
            // order by price
            usort($categoryItems,function ($a,$b){return $a->getUnitPrice() > $b->getUnitPrice();});
            // find cheapest product
            $this->setDiscountAmount($categoryItems[0]->getTotal() * $this->discount);

            return $this;
        }
        return false;
    }

}