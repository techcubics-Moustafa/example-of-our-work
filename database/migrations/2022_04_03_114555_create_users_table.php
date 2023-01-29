<?php

use App\Enums\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('user_type', 15)->default(UserType::Individual->value);
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->string('phone', 100)->unique()->nullable();
            $table->string('password')->nullable();
            $table->char('lang', 10)->default(locale());
            $table->boolean('status')->default(true);
            $table->boolean('blocked')->default(false);
            $table->string('avatar', 100)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->foreignId('governorate_id')->nullable()->constrained('governorates');
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->string('address', 100)->nullable();
            $table->char('gender', 10)->nullable();
            $table->char('provider_type')->nullable();
            $table->string('provider_id')->nullable();
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
