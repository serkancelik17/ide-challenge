<?php

namespace App\Controller;

use App\Entity\Order;
use App\Rule\DiscountCategoryBuy5Get1RuleInterface;
use App\Rule\DiscountCategoryToCheapest20PercentGte2RuleInterface;
use App\Rule\DiscountPayment10PercentOver1000RuleInterface;
use App\Rule\DiscountRuleAbstract;
use App\Rule\DiscountRuleInterface;
use App\Service\OrderService;
use App\Type\Order\IndexResponseType;
use App\Type\Order\NewRequestType;
use Doctrine\DBAL\Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function index() : JsonResponse
    {
        $data = [];

        $orders = $this->orderService->findAll();
        foreach ($orders as $order) {
            $orderItems = [];
            foreach($order->getItems() AS $item)
                $orderItems[] = ["productId"=>$item->getProduct()->getId(),"quantity"=>$item->getQuantity(),"unitPrice"=>$item->getUnitPrice(),"total"=>$item->getTotal()];
            $data[] = [
                'id' => $order->getId(),
                'customerId' => $order->getCustomer()->getId(),
                'items' => $orderItems,
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
    public function discounts(Order $order) : JsonResponse
    {
        $data = [];
        $totalDiscount = 0;

        $rules = [
            DiscountCategoryBuy5Get1RuleInterface::class,
            DiscountCategoryToCheapest20PercentGte2RuleInterface::class,
            DiscountPayment10PercentOver1000RuleInterface::class
        ];

        $data['orderId'] = $order->getId();
        $subTotal = $order->getTotal();
        //Kuralları uygulaa
        foreach ($rules AS $rule) {
            $instance = (new $rule($order))->handle($order);
            if($instance instanceof DiscountRuleInterface) {
                $subTotal -= $instance->getDiscountAmount();
                $totalDiscount += $instance->getDiscountAmount();
                /** @var DiscountRuleAbstract $instance */
                $instance->setSubTotal($subTotal);
                $data['discounts'][] = ['discountReason' =>$instance->getDiscountReason(),'discountAmount'=>$instance->getDiscountAmount(),'subTotal'=>$instance->getSubTotal()];
            }
            $data['totalDiscount'] = $totalDiscount;
            $data['discountedTotal'] = $order->getTotal() - $totalDiscount;
        }

        return new JsonResponse($data);

    }
}