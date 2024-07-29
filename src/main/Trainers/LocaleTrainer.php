<?php

use NameParser;

class LocaleTrainer
{
    private NameParser $nameParser;

    public function __construct(string $name = '')
    {
        $this->nameParser = new NameParser($name);
    }

    public function setName(string $name): self
    {
        $this->nameParser->setName($name);
        return $this;
    }

    public function getCity(): string
    {
        return $this->nameParser->getShortName();
    }

    public function getCountry(): string
    {
        return $this->nameParser->getNamespace();
    }

    public function getFullCityName(): string
    {
        return $this->getCity() . ', ' . $this->getCountry();
    }
}
