<?php


class NameParser
{
    use StringManipulationTrait;

    private string $fullName;
    private string $shortName;
    private string $namespace;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function setName(string $name): self
    {
        $this->fullName = $name;
        $this->parse();
        return $this;
    }

    private function parse(): void
    {
        $lastBackslashPos = strrpos($this->fullName, "\\");
        if ($lastBackslashPos !== false) {
            $this->shortName = substr($this->fullName, $lastBackslashPos + 1);
            $this->namespace = substr($this->fullName, 0, $lastBackslashPos);
        } else {
            $this->shortName = $this->fullName;
            $this->namespace = '';
        }
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getSingularName(): string
    {
        return $this->singularize($this->shortName);
    }

    public function getPluralName(): string
    {
        return $this->pluralize($this->shortName);
    }

    public function __toString(): string
    {
        return $this->getShortName();
    }
}
