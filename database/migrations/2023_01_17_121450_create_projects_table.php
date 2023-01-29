<?php

use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->char('status', 20)->default(ProjectStatus::Not_Available->value);
            $table->integer('number_blocks')->default(0);
            $table->integer('number_floors')->default(0);
            $table->integer('number_flats')->default(0);
            $table->float('min_price')->default(0);
            $table->float('max_price', 14)->default(0);
            $table->dateTime('open_sell_date')->nullable();
            $table->dateTime('finish_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
