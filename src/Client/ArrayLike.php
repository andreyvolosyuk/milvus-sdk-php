<?php


namespace Volosyuk\MilvusPhp\Client;


use ArrayAccess;
use Countable;
use Iterator;

abstract class ArrayLike implements ArrayAccess, Iterator, Countable
{
    private $source = [];

    private $counter = 0;

    protected function append($item)
    {
        $this->source[] = $item;
    }

    protected function processItemBeforeOutput($item) {
        return $item;
    }

    public function current()
    {
        return $this->processItemBeforeOutput($this->source[$this->counter]);
    }

    public function next()
    {
        $this->counter++;
    }

    public function key(): int
    {
        return $this->counter;
    }

    public function valid(): bool
    {
        return isset($this->source[$this->counter]);
    }

    public function rewind()
    {
        $this->counter = 0;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->source);
    }

    public function offsetGet($offset)
    {
        return $this->processItemBeforeOutput($this->source[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        $this->source[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->source[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->source);
    }
}