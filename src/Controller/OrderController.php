<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use App\Type\Order\IndexResponseType;
use App\Type\Order\NewRequestType;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/api", name="app.api")
 */
class OrderController extends AbstractFOSRestController
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
    public function index(ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
    {
        $data = [];

        /** @var Order[] $orders */
        $orders = $doctrine->getRepository(Order::class)->findAll();

        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->getId(),
                'customerId' => $order->getCustomer()->getId(),
                'items' => $order->getItems()->toArray(),
                'total' => $order->getTotal(),
            ];
        }
        return new JsonResponse($data);
    }


    /**
     *  Add New Order
     * @Route("/orders", name="app.orders.store", methods={"POST"})
     * @OA\RequestBody(
     *     @OA\JsonContent(ref=@Model(type=NewRequestType::class))
     * )
     * @ParamConverter("newRequestType", class="App\Type\Order\NewRequestType", converter="fos_rest.request_body")
     * @OA\Response(
     *     response=200,
     *     description="Add New Order",
     * )
     * @throws \Exception
     */
    public function store(OrderService $orderService, ConstraintViolationListInterface $validationErrors, NewRequestType $newRequestType)
    {
        //request i validate et
        if (\count($validationErrors) > 0) {
            return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
        }
        // siparişi kaydet
        try {
            $orderId = $orderService->store($newRequestType);
        } catch (Exception $e) {
            throw new \Exception('Sipariş Kaydedilemedi');
        }

        return $this->json(['status' => true, 'message' => 'Created new order successfully with id ' . $orderId]);
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

        return $this->json(['success' => true, 'message' => 'Deleted order successfully with id ' . $order->getId()]);
    }
}