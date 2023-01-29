<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('real_estates', function (Blueprint $table) {
            $table->id();
            $table->morphs('modelable');
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('publish')->default(false);
            $table->foreignId('special_id')->constrained('specials');
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('governorate_id')->constrained('governorates');
            $table->foreignId('region_id')->constrained('regions');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('sub_category_id')->constrained('categories');
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->string('location');
            $table->string('youtube_video_thumbnail', 100)->nullable();
            $table->string('youtube_video_url')->nullable();
            $table->string('image');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('feature_real_estates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_id')->constrained('features');
            $table->foreignId('real_estate_id')->constrained('real_estates');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('real_estates');
        Schema::dropIfExists('feature_real_estates');
    }
};
