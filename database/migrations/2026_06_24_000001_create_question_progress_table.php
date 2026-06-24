<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_question_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('level')->default(1);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('last_answered_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'quiz_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_progress');
    }
};
