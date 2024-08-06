<?php

/**
 * Struct is a collection of memory-efficient and performant data structures.
 */
class Struct
{
    static \SplFixedArray $array;
    static \SplHeap $heap;
    static \SplDoublyLinkedList $linkedList;
    // static \Ds\Vector $vector;
    // static \Ds\Deque $deque;
    // static \Ds\Set $set;
    // static \Ds\Map $map;
    // static \Ds\Stack $stack;

    public function maxHeap($data = [])
    {
        foreach ($data as $key => $value) {
            self::$heap->insert($value);
        }

        return self::$heap;
    }

    public function minHeap($data = [])
    {
        foreach ($data as $key => $value) {
            self::$heap->insert($value);
        }

        return self::$heap;
    }

    public function heap()
    {
        return new class extends \SplHeap {
            
        };
    }
}