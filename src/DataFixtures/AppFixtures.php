<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    protected Generator $faker;
    private ObjectManager $manager;

    private array $customers,$products = [];

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();

        $this->loadCustomers(); //add random customers
        $this->loadProducts(); //add random products
        $this->loadOrders(); //add random orders

    }

    /**
     * @throws Exception
     */
    public function loadProducts() {
        //Add Products
        for ($i=0; $i<20; $i++) {
            $product = new Product();
            $product->setName($this->faker->name)
                ->setCategoryId(random_int(1,5))
                ->setPrice(random_int(10,100))
                ->setStock(random_int(0,10));
            $this->manager->persist($product);

            $this->products[] = $product;
        }
        $this->manager->flush();

    }

    public function loadCustomers() {

        //Add Customers
        for ($i=0; $i<20; $i++) {
            $customer = new Customer();
            $customer->setName($this->faker->name)
                ->setSince($this->faker->dateTimeBetween('-30 days'))
                ->setRevenue($this->faker->randomFloat(0,1000));

            $this->manager->persist($customer);

            $this->customers[] = $customer;
        }
        $this->manager->flush();
    }

    /**
     * @throws Exception
     */
    public function loadOrders()
    {
        //Add orders;
        for ($i=0; $i<20; $i++) {
            $orderTotal = 0;

            $order = new Order();
            $order->setCustomer($this->customers[random_int(0,count($this->customers)-1)]);
            $order->setTotal(100);
            $this->manager->persist($order);

            for ($y=0; $y<3; $y++) {
                $product = $this->products[random_int(0,count($this->products)-1)];
                $quantity = random_int(1,10);
                $orderItemTotal = $product->getPrice() * $quantity;
                $orderTotal += $orderItemTotal;

                $orderItem = new OrderItem();
                $orderItem->setProduct($product)->setQuantity($quantity)->setTotal($orderItemTotal)
                    ->setUnitPrice($product->getPrice());
                $this->manager->persist($orderItem);
                $this->manager->flush();

                $order->addItem($orderItem);
                $order->setTotal($orderTotal);
            }
        }
        $this->manager->flush();

    }
}
