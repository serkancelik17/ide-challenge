<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Type\Order\NewRequestType;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

class OrderService extends OrderRepository
{
    private CustomerRepository $customerRepository;
    private ProductRepository $productRepository;

    public function __construct(ManagerRegistry $registry, CustomerRepository $customerRepository, ProductRepository $productRepository)
    {
        parent::__construct($registry);
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
    }


    /**
     * @throws Exception
     */
    public function store(NewRequestType $newRequestType): bool|int|string
    {
                $order = new Order();
                $customer = $this->customerRepository->find($newRequestType->customerId);
                $order->setTotal($newRequestType->total)->setCustomer($customer);

                //Add items
                foreach ($newRequestType->items AS $item) {
                    $product = $this->productRepository->find($item->productId);
                    $orderItem = (new OrderItem())->setProduct($product)->setQuantity($item->quantity)
                        ->setUnitPrice($item->unitPrice)->setTotal($item->total);
                    $order->addItem($orderItem);
                }

                //save order
                $this->save($order,true);

                return $this->_em->getConnection()->lastInsertId();
    }

    public function delete(Order $order) : bool
    {
        $this->_em->remove($order);

        return true;
    }

}