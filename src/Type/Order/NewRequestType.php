<?php
namespace  App\Type\Order;

use Symfony\Component\Validator\Constraints as Assert;

class NewRequestType
{
//    public int $id;
    /**
     * @Assert\NotBlank()
     * @var int
     */
    public int $customerId;

//    /**
//     * @var ItemType[]
//     *
//     * @Assert\Type("array")
//     */
//    public ItemType $items;
    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @var float
     */
    public float $total;
}