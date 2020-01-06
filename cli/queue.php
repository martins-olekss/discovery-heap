<?php
require __DIR__ . '/../bootstrap.php';

use Queue;

$queue = new Queue();
$queue->consume();