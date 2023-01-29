<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('report_comment_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_comment_id')->constrained('report_comments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title');
            $table->char('locale', 5)->index();
            $table->unique(['report_comment_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_comment_translations');
    }
};
