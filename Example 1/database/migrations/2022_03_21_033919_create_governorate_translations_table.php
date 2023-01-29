<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('governorate_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('governorate_id')->constrained('governorates')->cascadeOnDelete();
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->char('locale', 5)->index();
            $table->unique(['governorate_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('governorate_translations');
    }
};
