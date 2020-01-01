<?php

require __DIR__ . '/../../bootstrap.php';

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('users', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('name');
    $table->string('email');
    $table->string('password');
    $table->timestamps();
    $table->softDeletes();
});
