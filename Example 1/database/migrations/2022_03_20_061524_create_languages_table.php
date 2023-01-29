<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->char('direction', 3)->default('ltr');
            $table->char('code', 10)->unique();
            $table->char('flag', 15)->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('languages');
    }
};
