<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('report_comment_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('comment_id')->constrained('comments');
            $table->foreignId('report_comment_id')->constrained('report_comments');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_comment_users');
    }
};
