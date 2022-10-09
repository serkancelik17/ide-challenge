<?php
namespace  App\Type\Order;

use App\Type\Order\Schema\ItemType;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class NewRequestType
{
//    public int $id;
    /**
     * @Assert\NotBlank()
     * @var int
     */
    public int $customerId;

    /**
     * @Assert\Type("array")
     * @Assert\Count(min=1)
     * @Assert\NotBlank()
     * @Type("array<App\Type\Order\Schema\ItemType>")
     */
    public array $items;
    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @var float
     */
    public float $total;
}