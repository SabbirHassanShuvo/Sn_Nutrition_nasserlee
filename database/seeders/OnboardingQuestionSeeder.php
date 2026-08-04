<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnboardingQuestion;
use App\Models\OnboardingSetting;

class OnboardingQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Onboarding CMS Settings
        OnboardingSetting::updateOrCreate(
            ['id' => 1],
            [
                'badge_text' => 'Tailored for you',
                'title' => 'Not sure where to start?',
                'description' => 'Take our 2-minute assessment created by nutritionists to get a personalized supplement routine based on your goals, lifestyle, and diet.',
                'footnote_text' => '2min • No account needed • 100% free',
            ]
        );

        // 2. Seed Questions and Answers
        $questions = [
            [
                'question_text' => 'What is your main health goal?',
                'serial_number' => 1,
                'answers' => [
                    'Weight Loss',
                    'Muscle Gain',
                    'Energy Boost',
                    'General Wellness'
                ]
            ],
            [
                'question_text' => 'How active are you on a daily basis?',
                'serial_number' => 2,
                'answers' => [
                    'Sedentary (No exercise)',
                    'Lightly Active (1-3 days/week)',
                    'Moderately Active (3-5 days/week)',
                    'Very Active (6-7 days/week)'
                ]
            ],
            [
                'question_text' => 'Do you follow any specific diet?',
                'serial_number' => 3,
                'answers' => [
                    'No specific diet',
                    'Vegetarian / Vegan',
                    'Keto / Low-Carb',
                    'High-Protein'
                ]
            ],
            [
                'question_text' => 'How is your sleep quality?',
                'serial_number' => 4,
                'answers' => [
                    'Poor',
                    'Fair',
                    'Good',
                    'Great'
                ]
            ],
            [
                'question_text' => 'Do you have any known food allergies?',
                'serial_number' => 5,
                'answers' => [
                    'None',
                    'Dairy / Lactose',
                    'Gluten / Wheat',
                    'Nuts / Tree Nuts'
                ]
            ],
            [
                'question_text' => 'How much water do you drink daily?',
                'serial_number' => 6,
                'answers' => [
                    'Less than 1 Liter',
                    '1 to 2 Liters',
                    '2 to 3 Liters',
                    'More than 3 Liters'
                ]
            ],
        ];

        foreach ($questions as $qData) {
            $question = OnboardingQuestion::updateOrCreate(
                ['question_text' => $qData['question_text']],
                [
                    'serial_number' => $qData['serial_number'],
                    'status' => true,
                ]
            );

            // Re-create answers to prevent duplicates if seeder runs multiple times
            $question->answers()->delete();
            foreach ($qData['answers'] as $answerText) {
                $question->answers()->create([
                    'answer_text' => $answerText,
                ]);
            }
        }
    }
}
