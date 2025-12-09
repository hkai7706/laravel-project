<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->enum('category', [
                'Love Knowledge',
                'Relationship Behavior',
                'Life Choices'
            ])->default('Love Knowledge');
            $table->json('options');
            $table->tinyInteger('correct_answer')->unsigned();
            $table->tinyInteger('marks')->unsigned()->default(10); // Changed to 10
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('category');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
