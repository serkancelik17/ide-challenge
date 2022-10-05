<?php

namespace App\Rule;

use App\Entity\Order;

interface DiscountRule
{
    const CATEGORY_ID = null;
    const QTY = null;
    const DISCOUNT_QTY = null;

    public function handle(Order $order) : self|bool;
}