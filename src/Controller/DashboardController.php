<?php

namespace Kikwik\ApiAdminBundle\Controller;

use Kikwik\ApiAdminBundle\Service\EndpointParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    /**
     * @var \Kikwik\ApiAdminBundle\Service\EndpointParser
     */
    private $endpointParser;

    public function __construct(EndpointParser $endpointParser)
    {
        $this->endpointParser = $endpointParser;
    }


    public function index()
    {
        return $this->render('@KikwikApiAdmin/dashboard.html.twig', [
            'endpointParser' => $this->endpointParser
        ]);
    }
}