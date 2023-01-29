<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('email', 100);
            $table->string('phone', 100);
            $table->string('whatsapp_number', 100);
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('governorate_id')->constrained('governorates');
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('sub_category_id')->constrained('categories');
            $table->boolean('status')->default(0);
            $table->string('logo', 100)->nullable();
            $table->string('location')->nullable();
            $table->text('social_media')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
