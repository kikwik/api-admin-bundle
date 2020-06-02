<?php

namespace Kikwik\ApiAdminBundle\Tests;


use Kikwik\ApiAdminBundle\Controller\DashboardController;
use Kikwik\ApiAdminBundle\KikwikApiAdminBundle;
use Kikwik\ApiAdminBundle\Service\EndpointParser;
use Nyholm\BundleTest\BaseBundleTestCase;

class BundleInitializationTest extends BaseBundleTestCase
{
    protected function getBundleClass()
    {
        return KikwikApiAdminBundle::class;
    }

    public function testInitBundle()
    {
        // Create a new Kernel
        $kernel = $this->createKernel();

        // Add some configuration
        $kernel->addConfigFile(__DIR__.'/config.yml');

        // Boot the kernel.
        $this->bootKernel();

        // Get the container
        $container = $this->getContainer();

        // Test if you services exists
        $services = [
            'kikwik_api_admin.controller.dashboard_controller'  => DashboardController::class,
            'kikwik_api_admin.service.endpoint_parser'          => EndpointParser::class,
        ];
        foreach($services as $serviceId => $serviceClass)
        {
            $this->assertTrue($container->has($serviceId),'Container should have the service with id: '.$serviceId);
            $service = $container->get($serviceId);
            $this->assertInstanceOf($serviceClass, $service, 'Service '.$serviceId.' should be an instance of '.$serviceClass);
        }
    }
}