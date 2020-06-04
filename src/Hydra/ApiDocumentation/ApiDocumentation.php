<?php


namespace Kikwik\ApiAdminBundle\Hydra\ApiDocumentation;


use Kikwik\ApiAdminBundle\Hydra\Base;

class ApiDocumentation extends Base
{
    private $supportedClass = [];

    public function __construct(array $jsonld)
    {
        parent::__construct($jsonld);

        foreach($this->jsonld['hydra:supportedClass'] as $supportedClassData)
        {
            $this->supportedClass[$supportedClassData['@id']] = new SupportedClass($supportedClassData);
        }
    }

    public function getTitle()
    {
        return isset($this->jsonld['hydra:title']) ? $this->jsonld['hydra:title'] : '';
    }

    public function getDescription()
    {
        return isset($this->jsonld['hydra:description']) ? $this->jsonld['hydra:description'] : '';
    }

    public function getSupportedClass($id): SupportedClass
    {
        if(!isset($this->supportedClass[$id]))
        {
            throw new \UnexpectedValueException('Kikwik\ApiAdminBundle\Hydra\ApiDocumentation non existent supportedClass with id "'.$id.'"');
        }
        return $this->supportedClass[$id];
    }
}