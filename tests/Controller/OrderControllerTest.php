<?php
namespace App\Tests\Controller;

use App\Controller\OrderController;
use App\Service\OrderService;
use App\Type\Order\NewRequestType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class OrderControllerTest extends TestCase {
    private $mockOrderService;
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
    public function storeIsWorkingProperly()
    {
        $mockValidationErrors = $this->createMock(ConstraintViolationListInterface::class);
        $mockNewRequestType = $this->createMock(NewRequestType::class);
        //public function store(ConstraintViolationListInterface $validationErrors, NewRequestType $newRequestType) : JsonResponse|View

        $response = $this->orderController->store($mockValidationErrors,$mockNewRequestType);


    }
//        $request = $this->getMock("Symfony\Component\HttpFoundation\Request");
//        $container = $this->getMock("Symfony\Component\DependencyInjection\ContainerInterface");
//        $service = $this->getMockBuilder("Some\Stuff")->disableOriginalConstructor()->getMock();
//        $container->expects($this->once())
//            ->method("getParameter")
//            ->with($this->equalTo('do_stuff'))
//            ->will($this->returnValue(true));
//
//        $container->expects($this->once())
//            ->method("get")
//            ->with($this->equalTo('stuff.service'))
//            ->will($this->returnValue($service));
//
//        $controller = new SameStuffController();
//        $controller->setContainer($container);
//
//        $controller->goAction($request);
}