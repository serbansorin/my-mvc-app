<?php

namespace Main\Accessors;


class ClassName
{
    use \StringManipulationTrait;

    private $name;
    private $props;

    // public function __construct($name)
    // {
    //     $this->name = $this->getCompleteClassName($name);
    //     $this->props = [];
    // }

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        // Constructor logic

    }

}