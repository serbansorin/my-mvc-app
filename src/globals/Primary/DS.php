<?php

declare(strict_types=1);

use ArrayObject;
use SplDoublyLinkedList;
use SplFixedArray;
use SplObjectStorage;
use SplStack;
use SplQueue;
use SplPriorityQueue;
use Swoole\StringObject;

class DS {
    public static function List() {
        return new SplDoublyLinkedList();
    }

    public static function ListArray(array $list = []) {
        $arr = new SplDoublyLinkedList();
        array_walk($list, fn($value) => $arr->push($value));
        return $list;
    }

    public static function String(string $data = '') {
        return new StringObject($data);
    }

    public static function Array(array $data = []) {
        return new ArrayObject($data);
    }

    public static function ArrayFixed(mixed $data = null) {
        if (is_array($data)) {
            return (new SplFixedArray())->fromArray($data);
        }
        elseif (is_int($data)) {
            return new SplFixedArray($data);
        }

        // TODO: sure to throw exception
        return (new SplFixedArray());
    }

    public static function Deque(array $data = []) {
        return new ArrayObject($data);
    }

    public static function Set(array $data = []) {
        return new SplObjectStorage();
    }

    public static function Map(array $data = []) {
        return new SplObjectStorage();
    }

    public static function Stack() {
        return new SplStack();
    }

    public static function Queue() {
        return new SplQueue();
    }

    public static function PriorityQueue() {
        return new SplPriorityQueue();
    }
}

if (!function_exists('ds')) {
    /**
     */
    function ds($type,$data = null)
    {
        return match ($type) {
            'list' => DS::List(),
            'list_array' => DS::ListArray($data),
            'string' => DS::String($data),
            'array' => DS::Array($data),
            'array_fixed' => DS::ArrayFixed($data),
            'deque' => DS::Deque($data),
            'set' => DS::Set($data),
            'map' => DS::Map($data),
            'stack' => DS::Stack(),
            'queue' => DS::Queue(),
            'priority_queue' => DS::PriorityQueue(),
            default => null,
        };
    }
}
