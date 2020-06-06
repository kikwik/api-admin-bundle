<?php

namespace Kikwik\ApiAdminBundle\Controller;

use Kikwik\ApiAdminBundle\Service\EndpointPool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class DashboardController extends AbstractController
{
    /**
     * @var \Kikwik\ApiAdminBundle\Service\EndpointPool
     */
    private $endpointPool;

    public function __construct(EndpointPool $endpointPool)
    {
        $this->endpointPool = $endpointPool;
    }


    public function index()
    {
        return $this->render('@KikwikApiAdmin/dashboard.html.twig', [
            'endpointPool' => $this->endpointPool
        ]);
    }

    public function sideMenu()
    {
        return $this->render('@KikwikApiAdmin/_sideMenu.html.twig', [
            'endpointPool' => $this->endpointPool
        ]);
    }
}