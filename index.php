<?php
require_once "Services/Sort.php";
$sort = new \Services\Sort();
for ($n = 100; $n <= 10_000_000;$n *= 10) {
//$n = 100;

$sort->setRandomArray($n);
//$sort->countingSort();
//    $sort->radixSort();
$sort->bucketSort();
$sort->toString();
}