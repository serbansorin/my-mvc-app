<?php


class Collection extends \SplObjectStorage implements \Main\Interfaces\CollectionInterface
{
    public function add($object, $data = null)
    {
        $this->attach($object, $data);
    }

    public function remove($object)
    {
        $this->detach($object);
    }

    public function get($object)
    {
        return $this->offsetGet($object);
    }

    public function has($object)
    {
        return $this->contains($object);
    }

    public function all()
    {
        $objects = [];
        foreach ($this as $object) {
            $objects[] = $object;
        }
        return $objects;
    }

    public function clear()
    {
        $this->removeAll($this);
    }

    public function find($id)
    {
        foreach ($this as $object) {
            if ($object->id === $id) {
                return $object;
            }
        }
        return null;
    }

    public function first()
    {
        $this->rewind();
        return $this->current();
    }

    public function last()
    {
        $lastObjectPosition = $this->count() - 1;
        $this->rewind();
        for ($i = 0; $i < $lastObjectPosition; $i++) {
            $this->next();
        }
        return $this->current();
    }

    public function each(callable $callback)
    {
        foreach ($this as $object) {
            $callback($object);
        }
    }

    public function filter(callable $callback)
    {
        $filtered = new Collection();
        foreach ($this as $object) {
            if ($callback($object)) {
                $filtered->add($object);
            }
        }
        return $filtered;
    }

    public function findBy($key, $value)
    {
        foreach ($this as $object) {
            if (isset($object->$key) && $object->$key === $value) {
                return $object;
            }
        }
        return null;
    }

    public function isEmpty()
    {
        return $this->count() === 0;
    }

    public function map(callable $callback)
    {
        $mapped = new Collection();
        foreach ($this as $object) {
            $mapped->add($callback($object));
        }
        return $mapped;
    }

    public function pluck($key)
    {
        $values = [];
        foreach ($this as $object) {
            if (isset($object->$key)) {
                $values[] = $object->$key;
            }
        }
        return $values;
    }

    public function pop()
    {
        $lastObject = $this->last();
        if ($lastObject !== null) {
            $this->remove($lastObject);
        }
        return $lastObject;
    }

    public function push($value)
    {
        $this->add($value);
    }

    public function reduce(callable $callback, $initial = null)
    {
        $accumulator = $initial;
        foreach ($this as $object) {
            $accumulator = $callback($accumulator, $object);
        }
        return $accumulator;
    }

    public function shift()
    {
        $firstObject = $this->first();
        if ($firstObject !== null) {
            $this->remove($firstObject);
        }
        return $firstObject;
    }

    public function toArray()
    {
        return $this->all();
    }

    public function toJson()
    {
        return json_encode($this->all());
    }
}
