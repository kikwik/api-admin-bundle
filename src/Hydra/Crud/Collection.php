<?php

namespace Kikwik\ApiAdminBundle\Hydra\Crud;


use Kikwik\ApiAdminBundle\Hydra\Base;

class Collection extends Base
{
    /**
     * @return \Kikwik\ApiAdminBundle\Hydra\Crud\Member[]
     */
    public function getMembers()
    {
        $result = [];
        foreach($this->jsonld['hydra:member'] as $memberData)
        {
            $result[] = new Member($memberData);
        }
        return $result;
    }

    public function getFields()
    {
        if(isset($this->jsonld['hydra:member'][0]))
        {
            $first = new Member($this->jsonld['hydra:member'][0]);
            return $first->getFields();
        }
        return [];
    }

    public function getTotalItems()
    {
        return $this->jsonld['hydra:totalItems'];
    }

    public function hasPagination()
    {
        return isset($this->jsonld['hydra:view']) ? true : false;
    }

    public function getFirstPage()
    {
        return isset($this->jsonld['hydra:view']['hydra:first']) ? $this->jsonld['hydra:view']['hydra:first'] : false;
    }

    public function getPreviousPage()
    {
        return isset($this->jsonld['hydra:view']['hydra:previous']) ? $this->jsonld['hydra:view']['hydra:previous'] : false;
    }

    public function getNextPage()
    {
        return isset($this->jsonld['hydra:view']['hydra:next']) ? $this->jsonld['hydra:view']['hydra:next'] : false;
    }

    public function getLastPage()
    {
        return isset($this->jsonld['hydra:view']['hydra:last']) ? $this->jsonld['hydra:view']['hydra:last'] : false;
    }
}