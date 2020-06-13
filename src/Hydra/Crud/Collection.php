<?php

namespace Kikwik\ApiAdminBundle\Hydra\Crud;


use Kikwik\ApiAdminBundle\Hydra\Base;

class Collection extends Base
{
    private $members;

    public function __construct(array $jsonld)
    {
        parent::__construct($jsonld);

        $this->members = [];
        foreach($this->jsonld['hydra:member'] as $memberData)
        {
            $this->members[] = new Member($memberData);
        }
    }

    /**
     * @return \Kikwik\ApiAdminBundle\Hydra\Crud\Member[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    public function getFields()
    {
        if(isset($this->members[0]))
        {
            return $this->members[0]->getFields();
        }
        return [];
    }

    public function getTitle()
    {
        return '...';
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