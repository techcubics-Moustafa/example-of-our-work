<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->morphs('modelable');
            $table->string('code');
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('verification_codes');
    }
};
