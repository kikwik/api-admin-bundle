<?php

namespace Kikwik\ApiAdminBundle\Hydra;

use Kikwik\ApiAdminBundle\Hydra\ApiDocumentation\ApiDocumentation;
use Kikwik\ApiAdminBundle\Hydra\Crud\Collection;

class HydraFactory
{
    public static function createFromJsonLd($jsonld)
    {
        if(!isset($jsonld['@type']))
        {
            return new Base($jsonld);
        }

        switch($jsonld['@type'])
        {
            case 'Entrypoint':
                return new Entrypoint($jsonld);
                break;
            case 'hydra:ApiDocumentation':
                return new ApiDocumentation($jsonld);
                break;
            case 'hydra:Collection':
                return new Collection($jsonld);
                break;
            default:
                throw new \UnexpectedValueException('Kikwik\ApiAdminBundle\Hydra\Factory: type '.$jsonld['@type'].' not supported');
                break;
        }
    }
}