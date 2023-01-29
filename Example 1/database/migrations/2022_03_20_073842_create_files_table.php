<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('relationable');
            $table->string('name')->nullable();
            $table->integer('size')->nullable();
            $table->string('file')->nullable();
            $table->string('path',50)->nullable();
            $table->string('full_file')->nullable();
            $table->string('mime_type',20)->nullable();
            $table->string('column_name',20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};
