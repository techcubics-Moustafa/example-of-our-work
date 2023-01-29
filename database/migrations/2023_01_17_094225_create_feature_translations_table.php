<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feature_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_id')->constrained('features')->cascadeOnDelete();
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->char('locale',5)->index();
            $table->unique(['feature_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('feature_translations');
    }
};
