<?php declare(strict_types=1);

namespace App\PostcodeLookup;

use SeekableIterator;

class AddressIterator implements SeekableIterator, \Countable, \JsonSerializable
{
    private $addresses;
    private $current = 0;

    public function __construct(Address ...$addressCollection)
    {
        $this->addresses = $addressCollection;
    }

    public function current()
    {
        return $this->addresses[$this->current] ?? null;
    }

    public function next()
    {
        $this->current++;
    }

    public function key()
    {
        return $this->current;
    }

    public function valid(): bool
    {
        return isset($this->addresses[$this->current]);
    }

    public function rewind()
    {
        $this->current = 0;
    }

    public function seek($position)
    {
        if (!isset($this->addresses[$position])) {
            throw new \OutOfBoundsException('Invalid Seek Position ' . $position);
        }
        $this->current = $position;
    }

    public function count()
    {
        return count($this->addresses);
    }

    /**
     * @return Address[]
     */
    public function toArray(): array
    {
        return $this->addresses;
    }

    public function jsonSerialize()
    {
        return $this->addresses;
    }
}
