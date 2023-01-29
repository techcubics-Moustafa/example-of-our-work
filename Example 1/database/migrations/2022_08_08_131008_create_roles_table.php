<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('created_by')->after('guard_name')->nullable()->constrained('roles');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
