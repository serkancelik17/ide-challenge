<?php
namespace  App\Type\Order;

use JMS\Serializer\Annotation\Groups;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Validator\Constraints as Assert;

class NewRequestType
{
//    public int $id;
    /**
     * @Assert\NotBlank()
     * @Groups({"read"})
     * @var int
     */
    public int $customerId;

    /**
     * @Assert\Type("array")
     */
    public array $items;
    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @var float
     */
    public float $total;
}