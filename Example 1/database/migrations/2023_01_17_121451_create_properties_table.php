<?php

use App\Enums\ModerationStatus;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->char('type', 10)->default(PropertyType::Rent->value);
            $table->char('status', 25)->default(PropertyStatus::Not_Available->value);
            $table->char('moderation_status', 25)->default(ModerationStatus::Pending->value);
            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->integer('number_bedrooms')->default(0);
            $table->integer('number_bathrooms')->default(0);
            $table->integer('number_floors')->default(0);
            $table->float('square')->default(0);
            $table->float('price', 14)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
