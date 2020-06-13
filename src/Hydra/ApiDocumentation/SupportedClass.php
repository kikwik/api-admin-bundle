<?php


namespace Kikwik\ApiAdminBundle\Hydra\ApiDocumentation;


use Kikwik\ApiAdminBundle\Hydra\Base;

class SupportedClass extends Base
{
    private $supportedProperties = [];

    private $supportedOperations = [];

    public function __construct(array $jsonld)
    {
        parent::__construct($jsonld);
        if(isset($this->jsonld['hydra:supportedProperty']))
        {
            foreach($this->jsonld['hydra:supportedProperty'] as $supportedPropertyData)
            {
                $this->supportedProperties[$supportedPropertyData['hydra:property']['@id']] = $supportedPropertyData;
            }
        }
        if(isset($this->jsonld['hydra:supportedOperation']))
        {
            foreach($this->jsonld['hydra:supportedOperation'] as $supportedOperationData)
            {
                if(isset($supportedOperationData['hydra:method']))
                {
                    $this->supportedOperations[$supportedOperationData['hydra:method']] = $supportedOperationData;
                }
            }
        }
    }

    public function getTitle()
    {
        return $this->jsonld['hydra:title'];
    }
}