<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\OrderItem;
use App\Type\Order\NewRequestType;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Type\Order\IndexResponseType;

/**
 * @Route("/api", name="app.api")
 */

class OrderController extends AbstractController
{
    /** Get All Orders
     * @Route("/orders", name="app.orders", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Return Order List",
     *     @Model(type=IndexResponseType::class))
     * )
     * @Rest\View(serializerGroups={"read"}, serializerEnableMaxDepthChecks=true)
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $data = [];
        $orders = $doctrine->getRepository(Order::class)->findAll();

        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->getId(),
                'customerId' => $order->getCustomer()->getId(),
                'items' => $order->getItems(),
                'total' => $order->getTotal(),
            ];
        }


        return $this->json($data);
    }


    /**
     *  Add New Order
     * @Route("/orders", name="app.orders.store", methods={"POST"})
     * @OA\RequestBody(
     *     @OA\JsonContent(ref=@Model(type=NewRequestType::class))
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns Pool List",
     * )
     */
    public function store(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $order = new Order();
        $order->setCustomer(new Customer());
        $order->addItem(new OrderItem());
        $order->setTotal($request->request->get('total'));

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json(['status' => true,'message' => 'Created new order successfully with id ' . $order->getId()]);
    }

    /** Delete An Order
     * @Route("/orders/{id}", name="app.order.delete", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, Order $order): Response
    {
        $entityManager = $doctrine->getManager();

//        if (!$order) {
//            return $this->json('No project found for id' . $order->getId(), 404);
//        }

        $entityManager->remove($order);
        $entityManager->flush();

        return $this->json( ['success'=> true,'message'=>'Deleted order successfully with id ' . $order->getId()]);
    }
}