<?php

namespace App\Rule;

use App\Entity\Order;

class DiscountRuleAbstract
{
    private ?string $discountReason = null;
    private ?float $discountAmount = null;
    private ?float $subTotal = null;

    /**
     * @return string
     */
    public function getDiscountReason(): string
    {
        return $this->discountReason;
    }

    /**
     * @param string $discountReason
     */
    public function setDiscountReason(string $discountReason): void
    {
        $this->discountReason = $discountReason;
    }

    /**
     * @return float
     */
    public function getDiscountAmount(): ?float
    {
        return $this->discountAmount;
    }

    /**
     * @param float|null $discountAmount
     */
    public function setDiscountAmount(?float $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * @return float
     */
    public function getSubTotal(): float
    {
        return $this->subTotal;
    }

    /**
     * @param float $subTotal
     */
    public function setSubTotal(float $subTotal): void
    {
        $this->subTotal = $subTotal;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }
}