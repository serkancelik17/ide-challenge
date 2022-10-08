<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testDoStuffIsTrue()
    {
        $request = $this->getMock("Symfony\Component\HttpFoundation\Request");
        $container = $this->getMock("Symfony\Component\DependencyInjection\ContainerInterface");
        $service = $this->getMockBuilder("Some\Stuff")->disableOriginalConstructor()->getMock();
        $container->expects($this->once())
            ->method("getParameter")
            ->with($this->equalTo('do_stuff'))
            ->will($this->returnValue(true));

        $container->expects($this->once())
            ->method("get")
            ->with($this->equalTo('stuff.service'))
            ->will($this->returnValue($service));

        $controller = new SameStuffController();
        $controller->setContainer($container);

        $controller->goAction($request);

    }
}