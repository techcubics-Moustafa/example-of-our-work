<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('country_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->string('nationality', 50)->nullable();
            $table->char('locale', 5)->index();
            $table->unique(['country_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('country_translations');
    }
};
