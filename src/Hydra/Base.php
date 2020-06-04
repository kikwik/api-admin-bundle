<?php

namespace Kikwik\ApiAdminBundle\Hydra;


use Kikwik\ApiAdminBundle\Service\EndpointPool;

class Base
{
    /**
     * @var string
     */
    protected $jsonld = [];

    public function __construct(array $jsonld)
    {
        $this->jsonld = $jsonld;
    }

    public function getContext()
    {
        return $this->jsonld['@context'];
    }

    public function getVocab()
    {
        if(is_array($this->jsonld['@context']) && isset($this->jsonld['@context']['@vocab']))
        {
            return $this->jsonld['@context']['@vocab'];
        }
    }
}