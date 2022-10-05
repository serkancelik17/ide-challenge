<?php

namespace App\Controller;

use App\Entity\Order;
use App\Rule\DiscountCategoryBuy5Get1Rule;
use App\Rule\DiscountCategoryToCheapest20PercentGte2Rule;
use App\Rule\DiscountPayment10PercentOver1000Rule;
use App\Rule\DiscountRule;
use App\Service\OrderService;
use App\Type\Order\IndexResponseType;
use App\Type\Order\NewRequestType;
use App\Type\Order\OrderDiscountResponseType;
use App\Type\Order\Schema\DiscountType;
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
 * @Route("/api/orders")
 */
class OrderController extends AbstractFOSRestController
{

    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /** Get All Orders
     * @Route("", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Return Order List",
     *     @Model(type=IndexResponseType::class))
     * )
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
     * @Route("", methods={"POST"})
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
    public function store(ConstraintViolationListInterface $validationErrors, NewRequestType $newRequestType) : JsonResponse|View
    {
        //request i validate et
        if (\count($validationErrors) > 0) {
            return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
        }
        // siparişi kaydet
        try {
            $orderId = $this->orderService->store($newRequestType);
        } catch (Exception $e) {
            throw new \Exception('Order not saved! Error message:'.$e->getMessage());
        }

        return $this->json(['status' => true, 'message' => 'Created new order successfully with id ' . $orderId]);
    }

    /** Delete An Order
     * @Route("/{order}", methods={"DELETE"})
     */
    public function delete(Order $order) : JsonResponse
    {
        $this->orderService->delete($order);

        return $this->json(['status' => true, 'message' => 'Deleted order successfully with id ' . $order->getId()]);
    }

    /** Get All Orders
     * @Route("/{order}/discounts", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Return Order Discounts",
     *     @Model(type="string"))
     * )
     * @Rest\View()
     */
    public function discounts(Order $order) : View
    {
        $data = [];

        $rules = [
            DiscountCategoryBuy5Get1Rule::class,
         //   DiscountCategoryToCheapest20PercentGte2Rule::class,
         //   DiscountPayment10PercentOver1000Rule::class
        ];


        $data = new OrderDiscountResponseType();
        $data->setOrderId($order->getId());

        $discounts = [];
        foreach ($rules AS $rule) {
            $instance = (new $rule($order))->handle($order);

            if($instance instanceof DiscountRule)
                $discounts[] = $instance;
        }
        $data->setDiscounts($discounts);

        return View::create($data);

    }
}