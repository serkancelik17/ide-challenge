<?php

namespace App\Type\Order\Schema;

class DiscountType
{
    private string $discountReason;
    private string $discountAmount;
    private string $subtotal;

    public function getDiscountReason(): string
    {
        return $this->discountReason;
    }

    public function getDiscountAmount(): string
    {
        return $this->discountAmount;
    }

    public function getSubtotal(): string
    {
        return $this->subtotal;
    }

    public function setDiscountReason(string $discountReason): self
    {
        $this->discountReason = $discountReason;
        return $this;
    }

    public function setDiscountAmount(string $discountAmount): self
    {
        $this->discountAmount = $discountAmount;
        return $this;
    }

    public function setSubtotal(string $subtotal): self
    {
        $this->subtotal = $subtotal;
        return $this;
    }
}