<?php

namespace Src\DoublyLinkedList;

class Cell
{
    /**
     * 
     */
    private $content;

    /**
     * 
     * 
     * @var Cell|null
     */
    private $prev;

    /**
     * 
     * 
     * @var Cell|null
     */
    private $next;

    /**
     * Class Constructor.
     * 
     * @param  Cell|null  $prev
     * @param  Cell|null  $next
     * @return void
     */
    public function __construct($content = null, Cell $prev = null, Cell $next = null)
    {
        $this->content = $content;
        $this->prev = $prev;
        $this->next = $next;
    }

    /**
     * 
     * 
     * @param  $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * 
     * 
     * @return
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 
     * 
     * @param  Cell|null  $prev
     */
    public function setPrev(Cell $prev = null)
    {
        $this->prev = $prev;
    }

    /**
     * 
     * 
     * @return
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * 
     * 
     * @param  Cell|null  $next
     */
    public function setNext(Cell $next = null)
    {
        $this->next = $next;
    }

    /**
     * 
     * 
     * @return
     */
    public function getNext()
    {
        return $this->next;
    }
}
