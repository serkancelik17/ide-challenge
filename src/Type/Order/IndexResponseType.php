<?php
namespace  App\Type\Order;

use App\Type\Order\Schema\ItemType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class IndexResponseType
{
    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     * @var int
     */
    public int $id;
    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     * @ORM\Column(name="customerId")
     * @var int
     */
    public int $customerId;

    /**
     * @Assert\Type("array")
     * @var ItemType[]
     */
    public array $items;
    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @var float
     */
    public float $total;
}