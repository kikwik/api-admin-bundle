<?php


namespace Kikwik\ApiAdminBundle\Hydra\Crud;


use Kikwik\ApiAdminBundle\Hydra\Base;

class Member extends Base
{
    private $values = [];

    public function __construct(array $jsonld)
    {
        parent::__construct($jsonld);
        foreach($this->jsonld as $field => $value)
        {
            if(substr($field,0,1)!='@')
            {
                $this->values[$field] = $value;
            }
        }
    }

    public function getId()
    {
        return $this->jsonld['@id'];
    }

    public function getType()
    {
        return $this->jsonld['@type'];
    }

    public function getFields()
    {
        return array_keys($this->values);
    }

    public function getValues()
    {
        return $this->values;
    }
}