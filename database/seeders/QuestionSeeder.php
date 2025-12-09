<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate table first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Question::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $questions = [
            // Love Knowledge (4 questions)
            [
                'question' => 'What is traditionally considered the "love hormone" released during physical affection?',
                'category' => 'Love Knowledge',
                'options' => json_encode(['Oxytocin', 'Dopamine', 'Serotonin', 'Adrenaline']),
                'correct_answer' => 0,
                'marks' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'In Greek mythology, who is the god of love and desire?',
                'category' => 'Love Knowledge',
                'options' => json_encode(['Apollo', 'Zeus', 'Eros (Cupid)', 'Hermes']),
                'correct_answer' => 2,
                'marks' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'On which date is Valentine\'s Day celebrated worldwide?',
                'category' => 'Love Knowledge',
                'options' => json_encode(['February 12', 'February 14', 'March 14', 'February 16']),
                'correct_answer' => 1,
                'marks' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'Which city is famously known as the "City of Love"?',
                'category' => 'Love Knowledge',
                'options' => json_encode(['Venice', 'Paris', 'Rome', 'Vienna']),
                'correct_answer' => 1,
                'marks' => 10,
                'is_active' => true,
            ],

            // Relationship Behavior (3 questions)
            [
                'question' => 'What is considered the most important factor in maintaining a healthy long-term relationship?',
                'category' => 'Relationship Behavior',
                'options' => json_encode(['Physical attraction', 'Open communication', 'Financial stability', 'Common hobbies']),
                'correct_answer' => 1,
                'marks' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'What is the best approach to resolve conflicts in a relationship?',
                'category' => 'Relationship Behavior',
                'options' => json_encode(['Avoid the issue', 'Listen actively and compromise', 'Always win the argument', 'Give silent treatment']),
                'correct_answer' => 1,
                'marks' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'What role does trust play in a successful relationship?',
                'category' => 'Relationship Behavior',
                'options' => json_encode(['Not very important', 'Somewhat important', 'Very important', 'It is the foundation']),
                'correct_answer' => 3,
                'marks' => 10,
                'is_active' => true,
            ],

            // Life Choices (3 questions)
            [
                'question' => 'What is more important for long-term relationship success?',
                'category' => 'Life Choices',
                'options' => json_encode(['Shared core values', 'Physical appearance', 'Income level', 'Social status']),
                'correct_answer' => 0,
                'marks' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'When is the best time to discuss major future plans (kids, career, location) with your partner?',
                'category' => 'Life Choices',
                'options' => json_encode(['Never discuss it', 'After marriage only', 'Early in a serious relationship', 'When problems arise']),
                'correct_answer' => 2,
                'marks' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'How important is personal growth and self-improvement in a relationship?',
                'category' => 'Life Choices',
                'options' => json_encode(['Not important', 'Essential for both partners', 'Only for one partner', 'It threatens the relationship']),
                'correct_answer' => 1,
                'marks' => 10,
                'is_active' => true,
            ],
        ];

        // Insert all questions
        foreach ($questions as $question) {
            Question::create($question);
        }

        $this->command->info('✅ Successfully seeded 10 Love Quiz questions (10 marks each)!');
    }
}