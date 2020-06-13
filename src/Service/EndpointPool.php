<?php

namespace Kikwik\ApiAdminBundle\Service;


use Kikwik\ApiAdminBundle\Hydra\ApiDocumentation\ApiDocumentation;
use Kikwik\ApiAdminBundle\Hydra\Context;
use Kikwik\ApiAdminBundle\Hydra\Crud\Collection;
use Kikwik\ApiAdminBundle\Hydra\Entrypoint;
use Symfony\Component\HttpClient\HttpClient;

class EndpointPool
{
    private $apiEndpoint;

    private $apiDomain;

    private $jsonLds = [];

    public function __construct(string $apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
        $urlPath = parse_url($this->apiEndpoint,PHP_URL_PATH);
        if($urlPath)
        {
            $this->apiDomain = substr($this->apiEndpoint,0,strrpos($this->apiEndpoint,$urlPath));
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
        return $entrypoint->getContext()->getApiDocumentation();
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
            $jsonld = json_decode($response->getContent(),true);
            if(!isset($jsonld['@type']))
            {
                $this->jsonLds[$url] = new Context($jsonld);
                $this->jsonLds[$url]->setApiDocumentation($this->loadJsonLd($jsonld['@context']['@vocab']));
            }
            else
            {
                switch($jsonld['@type'])
                {
                    case 'hydra:ApiDocumentation':
                        $this->jsonLds[$url] = new ApiDocumentation($jsonld);
                        break;
                    case 'Entrypoint':
                        $this->jsonLds[$url] = new Entrypoint($jsonld);
                        $this->jsonLds[$url]->setContext($this->loadJsonLd($jsonld['@context']));
                        break;
                    case 'hydra:Collection':
                        $this->jsonLds[$url] = new Collection($jsonld);
                        $this->jsonLds[$url]->setContext($this->loadJsonLd($jsonld['@context']));
                        break;
                    default:
                        throw new \UnexpectedValueException('Kikwik\ApiAdminBundle\Service\EndpointPool::loadJsonLd type '.$jsonld['@type'].' not supported');
                        break;
                }
            }
        }
        return $this->jsonLds[$url];
    }




    public function debug()
    {
        dump($this->jsonLds);
    }

}
