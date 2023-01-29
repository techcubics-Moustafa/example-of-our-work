<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('real_estate_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('real_estate_id')->constrained('real_estates')->cascadeOnDelete();
            $table->string('title', 100)->index();
            $table->string('slug', 100)->index();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->char('locale', 5)->index();
            $table->unique(['real_estate_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('real_estate_translations');
    }
};
