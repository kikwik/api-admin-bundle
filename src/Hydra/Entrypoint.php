<?php


namespace Kikwik\ApiAdminBundle\Hydra;


class Entrypoint extends Base
{
    public function getLinks()
    {
        $result = [];
        foreach ($this->jsonld as $resourceName => $link)
        {
            if(substr($resourceName,0,1)!='@')
            {
                $result[] = [
                    'resourceName'=>$resourceName,
                    'link'=>$link,
                ];
            }
        }
        return $result;
    }
}