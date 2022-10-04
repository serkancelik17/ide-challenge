<?php
namespace  App\Type\Order\Schema;

use Symfony\Component\Validator\Constraints as Assert;

class ItemType
{
    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     * @var int
     */
    public int $productId;
    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     * @var int
     */
    public int $quantity;
    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @var float
     */
    public float $unitPrice;
    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @var float
     */
    public float $total;
}