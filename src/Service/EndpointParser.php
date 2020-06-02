<?php

namespace Kikwik\ApiAdminBundle\Service;

use Symfony\Component\HttpClient\HttpClient;

class EndpointParser
{
    /**
     * @var string
     */
    private $apiEndpoint;

    private $metadata = null;

    public function __construct(string $apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
    }

    public function getMetadata()
    {
        if(is_null($this->metadata))
        {
            $client = HttpClient::create();
            $this->metadata = json_decode($client->request('GET', $this->apiEndpoint.'/docs.jsonld')->getContent(),true);
        }
        return $this->metadata;
    }

    public function getTitle()
    {
        $metadata = $this->getMetadata();
        return isset($metadata['hydra:title']) ? $metadata['hydra:title'] : '';
    }

    public function getDescription()
    {
        $metadata = $this->getMetadata();
        return isset($metadata['hydra:description']) ? $metadata['hydra:description'] : '';
    }

    public function getEntryPoint()
    {
        $metadata = $this->getMetadata();
        return isset($metadata['hydra:entrypoint']) ? $metadata['hydra:entrypoint'] : '';
    }

    public function getSupportedClass()
    {
        $metadata = $this->getMetadata();
        $result = [];
        foreach($metadata['hydra:supportedClass'] as $supportedClassData)
        {
            $result[$supportedClassData['@id']] = new ResourceDescription($supportedClassData);
        }
        return $result;
    }


    public function getResources()
    {
        $supportedClasses = $this->getSupportedClass();
        unset($supportedClasses['#Entrypoint']);
        unset($supportedClasses['#ConstraintViolation']);
        unset($supportedClasses['#ConstraintViolationList']);
        return $supportedClasses;
    }

    public function getEntryPoints()
    {
        $supportedClasses = $this->getSupportedClass();
        $result = [];
        foreach($supportedClasses['#Entrypoint']->getSupportedProperty() as $supportedPropertyData)
        {
            $result[] = new SupportedProperty($supportedPropertyData, $this->apiEndpoint);
        }
        return $result;
    }
}

class SupportedProperty
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $baseEntryPoint;

    public function __construct(array $data, string $baseEntryPoint)
    {
        $this->data = $data;
        $this->baseEntryPoint = $baseEntryPoint;
    }

    public function getTitle()
    {
        return $this->data['hydra:title'];
    }

    public function getLink()
    {
        return str_replace('#Entrypoint',$this->baseEntryPoint,$this->data['hydra:property']['@id']);
    }
}

class ResourceDescription
{
    /**
     * @var array
     *
       "@id" => "http://schema.org/Book"
        "@type" => "hydra:Class"
        "rdfs:label" => "Book"
        "hydra:title" => "Book"
        "hydra:supportedProperty" => array:6 [▶]
        "hydra:supportedOperation" => array:6 [▶]
        "hydra:description" => "A book."
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getRaw()
    {
        return $this->data;
    }

    public function getTitle()
    {
        return $this->data['hydra:title'];
    }

    public function getDescription()
    {
        return $this->data['hydra:description'];
    }

    public function getSupportedProperty()
    {
        return $this->data['hydra:supportedProperty'];
    }

    public function getSupportedOperation()
    {
        return $this->data['hydra:supportedOperation'];
    }
}