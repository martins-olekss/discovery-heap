<?php

require __DIR__ . '/../../bootstrap.php';

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('posts', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('title');
    $table->string('content');
    $table->string('author');
    $table->timestamps();
});
