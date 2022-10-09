<?php
namespace App\Tests\Controller;

use App\Controller\OrderController;
use App\Entity\Order;
use App\Service\OrderService;
use App\Type\Order\NewRequestType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class OrderControllerTest extends TestCase {
    private MockObject|OrderService $mockOrderService;
    private OrderService $orderService;
    private OrderController $orderController;

    protected function setUp(): void
{
    parent::setUp();

    $this->mockOrderService = $this->createMock(OrderService::class);
    $this->orderController = new OrderController($this->mockOrderService);
}

    public function testIndexResponseIsCorrect()
    {
        $this->mockOrderService->expects($this->once())->method('findAll')->willReturn([]);

        $response = $this->orderController->index();
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals([], []);
    }


    /**
     * @throws \Exception
     */
    public function testStoreIsSuccess()
    {
        $mockValidationErrors = $this->createMock(ConstraintViolationListInterface::class);
        $newRequestType = new NewRequestType();
        $newRequestType->customerId = 1;
        $newRequestType->total = 100;

        //public function store(ConstraintViolationListInterface $validationErrors, NewRequestType $newRequestType) : JsonResponse|View

        $response = $this->orderController->store($mockValidationErrors,$newRequestType);

        self::assertEquals(200, $response->getStatusCode());
        self::assertIsString($response->getContent());
    }

    /**
     * @throws \Exception
     */
    public function testStoreIsHaveValidationError()
    {
        $mockValidationErrors = $this->createMock(ConstraintViolationListInterface::class);
        $newRequestType = new NewRequestType();

        $response = $this->orderController->store($mockValidationErrors,$newRequestType);

        self::assertEquals(200, $response->getStatusCode());
        self::assertIsString($response->getContent());
    }

    /**
     * @throws \Exception
     */
    public function testDeleteIsSuccess()
    {
        $mockOrder = $this->createMock(Order::class);

        //    public function delete(Order $order) : JsonResponse
        $response = $this->orderController->delete($mockOrder);

        self::assertEquals(200, $response->getStatusCode());
        self::assertIsString($response->getContent());
    }
}