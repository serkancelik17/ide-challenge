<?php

namespace App\Rule;

use App\Entity\Order;
use App\Entity\OrderItem;

/**
 * 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.
 */
class DiscountCategoryBuy5Get1Rule extends DiscountRuleAbstract implements DiscountRule
{
    const CATEGORY_ID = 2;
    const QTY = 6;
    const DISCOUNT_QTY = 1;


    public function __construct()
    {
        $this->setDiscountReason("BUY_" . (self::QTY - self::DISCOUNT_QTY) . "_GET_" . self::DISCOUNT_QTY);
    }

    public function handle(Order $order): self|bool
    {
        /** @var OrderItem $orderItem */
        foreach ($order->getItems() as $orderItem) {

            //Kategori ve Qty uyusuyorsa indirimi dondur.
            if ($orderItem->getProduct()->getCategory() == self::CATEGORY_ID && $orderItem->getQuantity() == self::QTY) {
                $this->setDiscountAmount($orderItem->getUnitPrice());
                return $this;
            }
        }
        return false;
    }
}