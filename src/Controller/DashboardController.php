<?php

namespace Kikwik\ApiAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class DashboardController
{
    /**
     * @var string
     */
    private $apiEndpoint;

    public function __construct(string $apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
    }


    public function index()
    {
        return new Response($this->apiEndpoint);
    }
}