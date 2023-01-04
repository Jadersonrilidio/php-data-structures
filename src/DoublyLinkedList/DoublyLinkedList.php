<?php

namespace Src\DoublyLinkedList;

use \Src\DoublyLinkedList\Cell;
// use \ArrayAccess;
use \Countable;
// use \Iterator;
// use \Serializable;

/**
 *   //todo
 * - Need to implement method unserialize($data) properly in order to use the Serializable interface;
 * - Need to implement next() properly in order to use the Iterator interface (amongst other methods);
 * - Find out how to use the inferface ArrayAccess methods properly, and advantages;
 * - Why I cannot use the 'mixed' type for variables and outputs???
 * 
 */
class DoublyLinkedList implements Countable //,Serializable //,Iterator
{
    /**
     * First element of the List.
     * 
     * @var Cell|null
     */
    private $first;

    /**
     * Last element of the List.
     * 
     * @var Cell|null
     */
    private $last;

    /**
     * Current element of the List.
     * 
     * @var Cell|null
     */
    private $current;

    /**
     * Current element index.
     * 
     * @var int|null
     */
    private $pointer;

    /**
     * Number of elements on the List;
     * 
     * @var int
     */
    private $length;

    /**
     * DoublyLinkedList instance constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->clear();
    }

    /**
     * Add first element when the list is empty.
     * 
     * @param  $content
     * @return bool
     */
    private function addFirst($content): bool
    {
        $this->first = new Cell($content);
        $this->last = $this->first;
        $this->current = $this->first;

        $this->pointer = 0;
        $this->length++;

        return true;
    }

    /**
     * Append an element to the end of the List.
     * 
     * @param  $content
     * @return bool
     */
    public function push($content): bool
    {
        if ($this->isEmpty()) {
            return $this->addFirst($content);
        }

        $newCell = new Cell($content);

        $this->last->setNext($newCell);
        $newCell->setPrev($this->last);
        $newCell->setNext(null);
        $this->last = $newCell;

        $this->length++;

        return true;
    }

    /**
     * Prepend an element on the beginning of the List.
     * 
     * @param  $content
     * @return bool
     */
    public function unshift($content): bool
    {
        if ($this->isEmpty()) {
            return $this->addFirst($content);
        }

        $newCell = new Cell($content);

        $this->first->setPrev($newCell);
        $newCell->setNext($this->first);
        $newCell->setPrev(null);
        $this->first = $newCell;

        $this->pointer++;
        $this->length++;

        return true;
    }

    /**
     * Remove an element from the end of the List.
     * 
     * @return bool
     */
    public function pop(): bool
    {
        if ($this->first === $this->last) {
            return $this->clear();
        }

        $lastCell = $this->last->getPrev();
        $lastCell->setNext(null);

        if ($this->current === $this->last) {
            $this->current = $lastCell;
            $this->pointer--;
        }

        $this->last = $lastCell;

        $this->length--;

        return true;
    }

    /**
     * Remove an element from the beginning of the List.
     * 
     * @return bool
     */
    public function shift(): bool
    {
        if ($this->first === $this->last) {
            return $this->clear();
        }

        $firstCell = $this->first->getNext();
        $firstCell->setPrev(null);

        if ($this->current == $this->first) {
            $this->current = $firstCell;
            $this->pointer = 0;
        }

        $this->first = $firstCell;

        if ($this->pointer > 0) {
            $this->pointer--;
        }

        $this->length--;

        return true;
    }

    /**
     * //test
     * 
     * @param  int  $index
     * @param  $content
     * @return bool
     */
    public function add(int $index, $content): bool
    {
        if (!$this->get($index)) {
            return false;
        }

        $newCell = new Cell($content);

        $prevCell = $this->current->getPrev();
        $nextCell = $this->current;

        $newCell->setPrev($prevCell);
        $newCell->setNext($nextCell);
        $this->current = $newCell;

        $prevCell->setNext($this->current);
        $nextCell->setPrev($this->current);

        $this->length++;
        return true;
    }

    /**
     * //test
     * 
     * @param  int  $index
     * @return bool
     */
    public function remove(int $index): bool
    {
        if (!$this->get($index)) {
            return false;
        }

        $prevCell = $this->current->getPrev();
        $nextCell = $this->current->getNext();
        $this->current = $nextCell;

        $prevCell->setNext($this->current);
        $this->current->setPrev($prevCell);

        $this->length--;
        return true;
    }

    /**
     * Return the content of the element from a given index, or return false.
     * 
     * @param  int  $index
     * @return mixed|bool
     */
    public function get(int $index)
    {
        if (!$this->validateInputs($index)) {
            return false;
        }

        $steps = $index - $this->pointer;
        $this->walk($steps);

        return $this->current->getContent();
    }

    /**
     * Validates if provided index exists on the list.
     * 
     * @param  int  $index
     * @return bool
     */
    private function validateInputs($index): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        if ($index >= $this->length) {
            return false;
        }

        return true;
    }

    /**
     * Update the content of the element from a given index and return true if the update is done. return false when fails.
     * 
     * @param  int  $index
     * @param  $content
     * @return bool
     */
    public function update(int $index, $content): bool
    {
        if (!$this->get($index)) {
            return false;
        }

        $this->current->setContent($content);

        return true;
    }

    /**
     * Move along the List, by increasing/decreasing its current and pointer attributes.
     * 
     * @param  int  $steps
     * @return void
     */
    private function walk(int $steps): void
    {
        if ($steps > 0) {
            $this->walkForward($steps);
        } else if ($steps < 0) {
            $this->walkBackward($steps);
        }
    }

    /**
     * Move forward along the List by increasing its current and pointer attributes.
     * 
     * @param  int  $steps
     * @return void
     */
    private function walkForward(int $steps): void
    {
        for ($i = 0; $i < $steps; $i++) {
            $nextCell = $this->current->getNext();
            $this->current = $nextCell;
            $this->pointer++;
        }
    }

    /**
     * Move backward along the List by decreasing its current and pointer attributes.
     * 
     * @param  int  $steps
     * @return void
     */
    private function walkBackward(int $steps): void
    {
        for ($i = 0; $i > $steps; $i--) {
            $prevCell = $this->current->getPrev();
            $this->current = $prevCell;
            $this->pointer--;
        }
    }

    /**
     * //todo - just for fun!
     */
    private function walkRecursive(int $steps): Cell
    {
        return new Cell;
    }

    /**
     * Return whether the List is empty or not;
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        if ($this->first === null) {
            return true;
        }
        return false;
    }

    /**
     * Return the first element of the List.
     * 
     * @return
     */
    public function first()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->first->getContent();
    }

    /**
     * Return the last element of the List.
     * 
     * @return
     */
    public function last()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->last->getContent();
    }

    /**
     * Return the current element of the List.
     * 
     * @return
     */
    public function current()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->current->getContent();
    }

    /**
     * Return the current element of the List.
     * 
     * @return
     */
    public function prev()
    {
        if ($this->isEmpty()) {
            return null;
        }

        if ($this->current->getPrev() === null) {
            return null;
        }

        $prevCell = $this->current->getPrev();
        $this->current = $prevCell;

        $this->pointer--;

        return $this->current->getContent();
    }

    /**
     * Return the current element of the List.
     * 
     * @return
     */
    public function next()
    {
        if ($this->isEmpty()) {
            return null;
        }

        if ($this->current->getNext() === null) {
            return null;
        }

        $nextCell = $this->current->getNext();
        $this->current = $nextCell;

        $this->pointer++;

        return $this->current->getContent();
    }

    /**
     * Return the number of elements of the List.
     * 
     * @return int|null
     */
    public function pointer()
    {
        return $this->pointer;
    }

    /**
     * Empty the List.
     * 
     * @return bool
     */
    public function clear(): bool
    {
        $this->first = null;
        $this->last = null;
        $this->current = $this->first;
        $this->pointer = null;
        $this->length = 0;

        return true;
    }

    /**
     * Return the number of elements of the List.
     * 
     * @return int
     */
    public function length(): int
    {
        return $this->length;
    }

    /**
     * Alias for length().
     * 
     * @return int
     */
    public function count(): int
    {
        return $this->length;
    }

    /**
     * 
     * 
     * @return void
     */
    public function rewind(): void
    {
        $this->current = $this->first;
        $this->pointer = 0;
    }

    /**
     * //review
     * 
     * @return array
     */
    public function toArray(): array
    {
        $array = array();

        $this->rewind();

        for ($this->pointer = 0; $this->pointer < $this->length; $this->pointer++) {
            $array[(string) $this->pointer] = $this->current->getContent();
            if ($this->current->getNext() !== null) {
                $this->current = $this->current->getNext();
            }
        }

        $this->rewind();

        return $array;
    }

    // /**
    //  * //todo
    //  * 
    //  * @return string
    //  */
    // public function serialize(): string
    // {
    //     $array = $this->toArray();
    //     return serialize($array);
    // }

    // /**
    //  * //todo
    //  * 
    //  * @param  $serialized
    //  * @return void
    //  */
    // public function unserialize($serialized): void
    // {
    //     $this->clear();

    //     $array = unserialize($serialized);

    //     foreach ($array as $content) {
    //         $this->push($content);
    //     }
    // }

    /**
     * //review
     * 
     * @return 
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * //todo
     * 
     * @return bool
     */
    public function valid(): bool
    {
        if ($this->current === null) {
            return false;
        }

        return true;
    }

    /**
     * //review
     * 
     * @return string
     */
    public function __toString(): string
    {
        $array = $this->toArray();

        return serialize($array);
    }

    /**
     * //review
     * 
     * @return string
     */
    public function __serialize(): string
    {
        return serialize($this->toArray());
    }

    /**
     * //todo
     * 
     * @param  string  $serialized
     * @return void
     */
    public function __unserialize(string $serialized): void
    {
        $this->clear();

        $array = unserialize($serialized);

        foreach ($array as $content) {
            $this->push($content);
        }
    }
}
