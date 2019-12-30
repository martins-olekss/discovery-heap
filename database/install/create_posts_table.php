<?php

require __DIR__ . '/../../bootstrap.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::create('posts', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('title');
    $table->string('content');
    $table->string('author');
    $table->timestamps();
});
