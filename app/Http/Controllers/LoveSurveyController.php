<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SurveySubmitted;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Love Survey Controller
 * Handles the love survey flow: display survey, collect responses, and send via email
 */
class LoveSurveyController extends Controller
{
    /**
     * Survey questions with their options
     * @var array
     */
    private $questions = [
        [
            'id' => 1,
            'question' => 'What matters most to you in a relationship?',
            'options' => [
                'A' => 'Trust and honesty',
                'B' => 'Fun and adventure',
                'C' => 'Deep emotional connection',
                'D' => 'Shared goals and values'
            ]
        ],
        [
            'id' => 2,
            'question' => 'How do you prefer to express love?',
            'options' => [
                'A' => 'Words of affirmation',
                'B' => 'Quality time together',
                'C' => 'Physical touch',
                'D' => 'Acts of service'
            ]
        ],
        [
            'id' => 3,
            'question' => 'What\'s your ideal date night?',
            'options' => [
                'A' => 'Romantic dinner at a nice restaurant',
                'B' => 'Outdoor adventure or activity',
                'C' => 'Cozy night in with movies',
                'D' => 'Trying something new together'
            ]
        ],
        [
            'id' => 4,
            'question' => 'How do you handle conflicts in relationships?',
            'options' => [
                'A' => 'Talk it out immediately',
                'B' => 'Take time to cool down first',
                'C' => 'Seek to understand their perspective',
                'D' => 'Find a compromise quickly'
            ]
        ],
        [
            'id' => 5,
            'question' => 'What first attracts you to someone?',
            'options' => [
                'A' => 'Physical appearance',
                'B' => 'Sense of humor',
                'C' => 'Intelligence and conversation',
                'D' => 'Kindness and compassion'
            ]
        ],
        [
            'id' => 6,
            'question' => 'How important is communication in your relationships?',
            'options' => [
                'A' => 'Extremely important - must talk daily',
                'B' => 'Important but need personal space',
                'C' => 'Quality over quantity of communication',
                'D' => 'Actions speak louder than words'
            ]
        ],
        [
            'id' => 7,
            'question' => 'What\'s your love language?',
            'options' => [
                'A' => 'Words of affirmation',
                'B' => 'Acts of service',
                'C' => 'Receiving gifts',
                'D' => 'Quality time'
            ]
        ],
        [
            'id' => 8,
            'question' => 'How do you know you\'re in love?',
            'options' => [
                'A' => 'Constant thoughts about them',
                'B' => 'Feeling comfortable being yourself',
                'C' => 'Wanting their happiness above all',
                'D' => 'Physical chemistry and attraction'
            ]
        ],
        [
            'id' => 9,
            'question' => 'What would you prioritize in a long-term relationship?',
            'options' => [
                'A' => 'Financial stability',
                'B' => 'Emotional support',
                'C' => 'Shared interests and hobbies',
                'D' => 'Family and future planning'
            ]
        ],
        [
            'id' => 10,
            'question' => 'How do you maintain romance in relationships?',
            'options' => [
                'A' => 'Surprise dates and gestures',
                'B' => 'Regular communication and check-ins',
                'C' => 'Physical intimacy',
                'D' => 'Supporting each other\'s dreams'
            ]
        ]
    ];

    /**
     * Display the survey landing page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Pass questions to view as JSON for JavaScript handling
        return view('love-survey', [
            'questions' => $this->questions
        ]);
    }

    /**
     * Handle survey submission
     * Validate data, send email, and redirect to completion page
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitSurvey(Request $request)
    {
        // Rate limiting: max 3 submissions per IP per hour
        $executed = RateLimiter::attempt(
            'submit-survey:' . $request->ip(),
            3, // Maximum attempts
            function() {},
            3600 // Time window in seconds (1 hour)
        );

        if (!$executed) {
            return back()->with('error', 'Too many submissions. Please try again later.');
        }

        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'answers' => 'required|array|size:10',
            'answers.*' => 'required|in:A,B,C,D',
        ], [
            'name.required' => 'Please enter your name.',
            'name.min' => 'Name must be at least 2 characters.',
            'answers.required' => 'Please answer all questions.',
            'answers.size' => 'Please answer all 10 questions.',
        ]);

        // Prepare response data with full question text and selected answers
        $responses = [];
        foreach ($this->questions as $index => $questionData) {
            $questionNum = $index + 1;
            $selectedOption = $validated['answers'][$questionNum];
            
            $responses[] = [
                'number' => $questionNum,
                'question' => $questionData['question'],
                'selected_option' => $selectedOption,
                'answer' => $questionData['options'][$selectedOption]
            ];
        }

        // Attempt to send email
        try {
            Mail::to(env('SURVEY_RECIPIENT_EMAIL', 'your-email@example.com'))
                ->send(new SurveySubmitted($validated['name'], $responses));

            // Clear session data and redirect to completion page
            $request->session()->forget('survey_progress');
            
            return redirect()->route('survey.complete')
                ->with('success', 'Thank you! Your survey has been submitted successfully.');
                
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Survey email failed: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the completion/thank you page
     * @return \Illuminate\View\View
     */
    public function complete()
    {
        return view('survey-complete');
    }
}