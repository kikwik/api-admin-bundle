<?php

namespace Kikwik\ApiAdminBundle\Service;


use Kikwik\ApiAdminBundle\Hydra\ApiDocumentation\ApiDocumentation;
use Kikwik\ApiAdminBundle\Hydra\Crud\Collection;
use Kikwik\ApiAdminBundle\Hydra\Entrypoint;
use Kikwik\ApiAdminBundle\Hydra\HydraFactory;
use Symfony\Component\HttpClient\HttpClient;

class EndpointPool
{
    private $apiEndpoint;

    private $apiDomain;

    private $jsonLds = [];

    public function __construct(string $apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
        if(parse_url($this->apiEndpoint,PHP_URL_PATH))
        {
            $this->apiDomain = substr($this->apiEndpoint,0,strrpos($this->apiEndpoint,parse_url($this->apiEndpoint,PHP_URL_PATH)));
        }
        else
        {
            $this->apiDomain = $this->apiEndpoint;
        }
    }

    public function getEntryPoint(): Entrypoint
    {
        return $this->loadJsonLd($this->apiEndpoint);
    }

    public function getApiDocumentation(): ApiDocumentation
    {
        $entrypoint = $this->getEntryPoint();
        $context = $this->loadJsonLd($entrypoint->getContext());
        return $this->loadJsonLd($context->getVocab());
    }

    public function getCollection($url): Collection
    {
        return $this->loadJsonLd($url);
    }

    private function loadJsonLd($url)
    {
        if(!parse_url($url,PHP_URL_SCHEME))
        {
            $url = $this->apiDomain.$url;
        }

        if(!isset($this->jsonLds[$url]))
        {
            $client = HttpClient::create();
            $response = $client->request('GET',$url,[
                'headers' => [
                    'accept' => 'application/ld+json',
                ]
            ]);
            if($response->getStatusCode()==200)
            {
                $this->jsonLds[$url] = HydraFactory::createFromJsonLd(json_decode($response->getContent(),true));
            }
            else
            {
                throw new \Exception('Error '.$response->getStatusCode().': '.$response->getContent(false));
            }
        }
        return $this->jsonLds[$url];
    }




    public function debug()
    {
        foreach($this->jsonLds as $jsonLd)
        {
            dump($jsonLd);
        }
    }




//    private $metadata = null;
//
//
//    public function getMetadata()
//    {
//        if(is_null($this->metadata))
//        {
//            $client = HttpClient::create();
//            $this->metadata = json_decode($client->request('GET', $this->apiEndpoint.'/docs.jsonld')->getContent(),true);
//        }
//        return $this->metadata;
//    }
//
//    public function getTitle()
//    {
//        $metadata = $this->getMetadata();
//        return isset($metadata['hydra:title']) ? $metadata['hydra:title'] : '';
//    }
//
//    public function getDescription()
//    {
//        $metadata = $this->getMetadata();
//        return isset($metadata['hydra:description']) ? $metadata['hydra:description'] : '';
//    }
//
//    public function getEntryPoint()
//    {
//        $metadata = $this->getMetadata();
//        return isset($metadata['hydra:entrypoint']) ? $metadata['hydra:entrypoint'] : '';
//    }
//
//    public function getSupportedClass()
//    {
//        $metadata = $this->getMetadata();
//        $result = [];
//        foreach($metadata['hydra:supportedClass'] as $supportedClassData)
//        {
//            $result[$supportedClassData['@id']] = new ResourceDescription($supportedClassData);
//        }
//        return $result;
//    }
//
//
//    public function getResources()
//    {
//        $supportedClasses = $this->getSupportedClass();
//        unset($supportedClasses['#Entrypoint']);
//        unset($supportedClasses['#ConstraintViolation']);
//        unset($supportedClasses['#ConstraintViolationList']);
//        return $supportedClasses;
//    }

//    public function getEntryPoints()
//    {
//        $supportedClasses = $this->getSupportedClass();
//        $result = [];
//        foreach($supportedClasses['#Entrypoint']->getSupportedProperty() as $supportedPropertyData)
//        {
//            $result[] = new SupportedProperty($supportedPropertyData, $this->apiEndpoint);
//        }
//        return $result;
//    }
}

//class SupportedProperty
//{
//    /**
//     * @var array
//     */
//    private $data;
//    /**
//     * @var string
//     */
//    private $baseEntryPoint;
//
//    public function __construct(array $data, string $baseEntryPoint)
//    {
//        $this->data = $data;
//        $this->baseEntryPoint = $baseEntryPoint;
//    }
//
//    public function getTitle()
//    {
//        return $this->data['hydra:title'];
//    }
//
//    public function getLink()
//    {
//        return str_replace('#Entrypoint',$this->baseEntryPoint,$this->data['hydra:property']['@id']);
//    }
//}
//
//class ResourceDescription
//{
//    /**
//     * @var array
//     *
//       "@id" => "http://schema.org/Book"
//        "@type" => "hydra:Class"
//        "rdfs:label" => "Book"
//        "hydra:title" => "Book"
//        "hydra:supportedProperty" => array:6 [▶]
//        "hydra:supportedOperation" => array:6 [▶]
//        "hydra:description" => "A book."
//     */
//    private $data;
//
//    public function __construct(array $data)
//    {
//        $this->data = $data;
//    }
//
//    public function getRaw()
//    {
//        return $this->data;
//    }
//
//    public function getTitle()
//    {
//        return $this->data['hydra:title'];
//    }
//
//    public function getDescription()
//    {
//        return $this->data['hydra:description'];
//    }
//
//    public function getSupportedProperty()
//    {
//        return $this->data['hydra:supportedProperty'];
//    }
//
//    public function getSupportedOperation()
//    {
//        return $this->data['hydra:supportedOperation'];
//    }
//}