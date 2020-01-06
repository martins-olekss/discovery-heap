<?php

require __DIR__ . '/../bootstrap.php';

use App\Controllers\Post;

$postController = new Post();
$postController->searchIndex();