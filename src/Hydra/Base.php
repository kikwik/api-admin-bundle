<?php

namespace Kikwik\ApiAdminBundle\Hydra;



class Base
{
    /**
     * @var string
     */
    protected $jsonld = [];

    protected $context;

    public function __construct(array $jsonld)
    {
        $this->jsonld = $jsonld;
    }

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}