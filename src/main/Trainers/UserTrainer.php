<?php

use NameParser;


class UserTrainer
{
    private NameParser $nameParser;
    private string $firstName;
    private string $lastName;

    public function __construct(string $name = '')
    {
        $this->nameParser = new NameParser($name);
        $this->parseUserName();
    }

    public function setName(string $name): self
    {
        $this->nameParser->setName($name);
        $this->parseUserName();
        return $this;
    }

    private function parseUserName(): void
    {
        $parts = explode(' ', $this->nameParser->getShortName(), 2);
        $this->firstName = $parts[0] ?? '';
        $this->lastName = $parts[1] ?? '';
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
