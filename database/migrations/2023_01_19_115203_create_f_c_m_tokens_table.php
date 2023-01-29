<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('f_c_m_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->boolean('enable')->default(1);
            $table->string('fcm_token')->unique();
            $table->string('device_name', 255)->nullable();
            $table->char('lang',10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('f_c_m_tokens');
    }
};
