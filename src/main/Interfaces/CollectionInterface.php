<?php

namespace Main\Interfaces;

interface CollectionInterface
{
    public function find($id);
    public function first();
    public function last();
    public function each(callable $callback);
    public function map(callable $callback);
    public function filter(callable $callback);
    public function reduce(callable $callback, $initial = null);
    public function toArray();
    public function toJson();
    public function count();
    public function isEmpty();
    public function findBy($key, $value);
    public function pluck($key);
    public function push($value);
    public function pop();
    public function shift();
}