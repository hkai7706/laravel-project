<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('difficulty'); // Easy, Medium, Hard
            $table->integer('total_score')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('incorrect_answers')->default(0);
            $table->integer('time_taken')->nullable(); // in seconds
            $table->string('badge')->nullable(); // Badge name
            $table->string('rank_title')->nullable(); // Rank title
            $table->json('category_scores')->nullable(); // Scores by category
            $table->boolean('completed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'completed']);
            $table->index('total_score');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};