<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Type\Order\NewRequestType;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

class OrderService extends OrderRepository
{
    private CustomerRepository $customerRepository;

    public function __construct(ManagerRegistry $registry, CustomerRepository $customerRepository)
    {
        parent::__construct($registry);
        $this->customerRepository = $customerRepository;
    }


    /**
     * @throws Exception
     */
    public function store(NewRequestType $newRequestType): bool|int|string
    {
                $order = new Order();
                $customer = $this->customerRepository->find($newRequestType->customerId);
                $order->setTotal($newRequestType->total)->setCustomer($customer);

                $this->save($order,true);

                return $this->_em->getConnection()->lastInsertId();
    }

    public function delete(Order $order) : bool
    {
        $this->delete($order);

        return true;
    }

}