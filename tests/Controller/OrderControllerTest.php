<?php
namespace App\Tests\Controller;

use App\Controller\OrderController;
use App\Service\OrderService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderControllerTest extends TestCase {

    public function testIndexResponseIsCorrect()
    {

        $mockOrderService = $this->createMock(OrderService::class);
        $orderController = new OrderController($mockOrderService);

        $mockOrderService->expects($this->once())->method('findAll')->willReturn([]);

        $response = $orderController->index();
        self::assertEquals([],[]);

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
}