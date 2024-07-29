<?php

use NameParser;

class NameTrainer
{
    use StringManipulationTrait;
    
    private static ?self $instance = null;
    private NameParser $nameParser;

    private function __construct(string $name = '')
    {
        $this->nameParser = new NameParser($name);
    }

    public static function getInstance(string $name = ''): self
    {
        if (self::$instance === null) {
            self::$instance = new self($name);
        } elseif ($name !== '') {
            self::$instance->setName($name);
        }
        return self::$instance;
    }

    public function setName(string $name): self
    {
        $this->nameParser->setName($name);
        return $this;
    }

    public function getName(): string
    {
        return $this->nameParser->getShortName();
    }

    public function getFullName(): string
    {
        return $this->nameParser->getFullName();
    }

    public function getNamespace(): string
    {
        return $this->nameParser->getNamespace();
    }

    public function getSingularName(): string
    {
        return $this->nameParser->getSingularName();
    }

    public function getPluralName(): string
    {
        return $this->nameParser->getPluralName();
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}