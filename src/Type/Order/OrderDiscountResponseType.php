<?php
namespace  App\Type\Order;

use App\Type\Order\Schema\DiscountType;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class OrderDiscountResponseType extends AbstractFOSRestController
{
    private int $orderId;
    /** @var DiscountType[] */
    private array $discounts;
    private string $totalDiscount;
    private string $discountedTotal;


    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @return DiscountType[]
     */
    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    public function getTotalDiscount(): string
    {
        return $this->totalDiscount;
    }

    public function getDiscountedTotal(): string
    {
        return $this->discountedTotal;
    }

    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @param DiscountType[] $discounts
     */
    public function setDiscounts(array $discounts): self
    {
        $this->discounts = $discounts;
        return $this;
    }

    public function setTotalDiscount(string $totalDiscount): self
    {
        $this->totalDiscount = $totalDiscount;
        return $this;
    }

    public function setDiscountedTotal(string $discountedTotal): self
    {
        $this->discountedTotal = $discountedTotal;
        return $this;
    }
}