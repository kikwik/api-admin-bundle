<?php


namespace Kikwik\ApiAdminBundle\Hydra;


use Kikwik\ApiAdminBundle\Hydra\ApiDocumentation\ApiDocumentation;

class Context
{
    /**
     * @var string
     */
    protected $jsonld = [];

    protected $apiDocumentation;

    public function __construct(array $jsonld)
    {
        $this->jsonld = $jsonld;
    }

    public function setApiDocumentation(ApiDocumentation $apiDocumentation)
    {
        $this->apiDocumentation = $apiDocumentation;
    }

    public function getApiDocumentation(): ApiDocumentation
    {
        return $this->apiDocumentation;
    }
}