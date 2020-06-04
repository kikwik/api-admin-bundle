<?php


namespace Kikwik\ApiAdminBundle\Controller;


use Kikwik\ApiAdminBundle\Service\EndpointPool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CrudController extends AbstractController
{
    /**
     * @var \Kikwik\ApiAdminBundle\Service\EndpointPool
     */
    private $endpointPool;

    public function __construct(EndpointPool $endpointPool)
    {
        $this->endpointPool = $endpointPool;
    }

    public function list(string $resource)
    {
        $resourceUri = urldecode($resource);
        $collection = $this->endpointPool->getCollection($resourceUri);

        return $this->render('@KikwikApiAdmin/crud/list.html.twig', [
            'collection' => $collection
        ]);
    }
}