<?php

namespace App\Rule;

use App\Entity\Order;

interface DiscountRule
{
    public function handle(Order $order) : self|bool;
}