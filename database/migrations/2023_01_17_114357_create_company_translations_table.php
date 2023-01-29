<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('company_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name', 50)->index();
            $table->string('slug', 50)->index();
            $table->string('description')->index();
            $table->string('address', 100)->nullable()->index();
            $table->char('locale', 5)->index();
            $table->unique(['company_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_translations');
    }
};
