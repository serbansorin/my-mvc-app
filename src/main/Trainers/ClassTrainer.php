<?php

use NameParser;

class ClassTrainer
{
    private NameParser $nameParser;
    private array $objArr = [];

    public function __construct(string $name = '')
    {
        $this->nameParser = new NameParser($name);
    }

    /** Set the class name */
    public function setName(string $name): self
    {
        $this->nameParser->setName($name);
        return $this;
    }

    /**
     * Get the class name without namespace
     * @return string
     */
    public function getClassName(): string
    {
        return $this->nameParser->getShortName();
    }

    /**
     * Get the namespace of the class
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->nameParser->getNamespace();
    }

    /**
     * Get the full class name with namespace
     * @return string
     */
    public function getFullClassName(): string
    {
        return $this->nameParser->getFullName();
    }

    /**
     * Get the singular class name without namespace
     * @return string
     */
    public function getClassBaseName(): string
    {
        return $this->nameParser->getShortName();
    }

    public function newClassInstance(...$args): ?Object
    {
        $className = $this->getFullClassName();

        if (class_exists($className)) {
            return new $className(...$args);
        }

        return null;   
    }

    public function __toString(): string
    {
        return $this->getClassName();
    }

    // public function __call(string $name, array $arguments): ?object
    // {
    //     $className = $this->getFullClassName();
    //     if (method_exists($className, $name)) {
    //         return $className->$name(...$arguments);
    //     }
    //     return null;
    // }

    // static public function __callStatic(string $name, array $arguments): ?object
    // {
        
    // }
}