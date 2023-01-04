<?php

require_once "./vendor/autoload.php";

# Testing site

use \Src\DoublyLinkedList\DoublyLinkedList;

$dll = new DoublyLinkedList();

$dll->push('test content 01');
$dll->push('test content 02');
$dll->push('test content 03');
$dll->push('test content 04');

echo $dll . "<br><br>" . PHP_EOL . PHP_EOL;

var_dump($dll);
