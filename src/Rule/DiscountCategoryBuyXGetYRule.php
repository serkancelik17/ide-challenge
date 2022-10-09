<?php

namespace App\Rule;

use App\Entity\Order;
use App\Entity\OrderItem;

/**
 * X ID'li kategoriye ait bir üründen Y adet satın alındığında, Z tanesi ücretsiz olarak verilir.
 */
class DiscountCategoryBuyXGetYRule extends DiscountRuleAbstract implements DiscountRuleInterface
{
    /** @TOOO will be remove */
//    const CATEGORY_ID = 2;
//    const QTY = 6;
//    const DISCOUNT_QTY = 1;

    private int $categoryId;
    private int $quantity;
    private int $discountQuantity;


    public function __construct(int $categoryId,int $quantity,int $discountQuantity)
    {
        $this->categoryId = $categoryId;
        $this->quantity = $quantity;
        $this->discountQuantity = $discountQuantity;

        $this->setDiscountReason("BUY_" . ($this->quantity - $this->discountQuantity) . "_GET_" . $this->discountQuantity);
    }

    public function handle(Order $order): self|bool
    {
        /** @var OrderItem $orderItem */
        foreach ($order->getItems() as $orderItem) {

            //Kategori ve Qty uyusuyorsa indirimi dondur.
            if ($orderItem->getProduct()->getCategoryId() == $this->categoryId && $this->quantity == self::QTY) {
                $this->setDiscountAmount($orderItem->getUnitPrice());
                return $this;
            }
        }
        return false;
    }
}