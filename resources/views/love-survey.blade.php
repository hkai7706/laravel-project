<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Love Survey ❤️</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #FFF0F5 0%, #FFE4E1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        .heart {
            position: absolute;
            color: #FF1493;
            font-size: 24px;
            animation: float 15s infinite;
            opacity: 0.3;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-100vh) rotate(360deg); }
        }

        .container {
            max-width: 600px;
            width: 90%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(255, 20, 147, 0.2);
            padding: 40px;
            position: relative;
            z-index: 1;
            margin: 20px;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #FF1493;
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #FFB6C1;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #FF1493;
            box-shadow: 0 0 0 3px rgba(255, 20, 147, 0.1);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #FF1493 0%, #FF69B4 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 20, 147, 0.3);
        }

        .btn:disabled {
            background: #CCC;
            cursor: not-allowed;
        }

        .question-container {
            display: none;
        }

        .question-container.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #FFE4E1;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #FF1493 0%, #FF69B4 100%);
            transition: width 0.3s ease;
        }

        .progress-text {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .question-title {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .option {
            background: #FFF0F5;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: flex;
            align-items: center;
        }

        .option:hover {
            background: #FFE4E1;
            transform: translateX(5px);
        }

        .option.selected {
            background: #FF1493;
            color: white;
            border-color: #FF1493;
        }

        .option input[type="radio"] {
            margin-right: 12px;
            width: 20px;
            height: 20px;
            accent-color: #FF1493;
        }

        .error-message {
            background: #FFE4E4;
            color: #D8000C;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #D8000C;
        }

        .hidden {
            display: none;
        }

        .loading {
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid #FFE4E1;
            border-top: 4px solid #FF1493;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            .container {
                padding: 25px;
            }

            .question-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="heart" style="left: 10%; animation-delay: 0s;">❤️</div>
    <div class="heart" style="left: 25%; animation-delay: 2s;">💕</div>
    <div class="heart" style="left: 40%; animation-delay: 4s;">💖</div>
    <div class="heart" style="left: 60%; animation-delay: 1s;">💗</div>
    <div class="heart" style="left: 75%; animation-delay: 3s;">💝</div>
    <div class="heart" style="left: 90%; animation-delay: 5s;">💘</div>

    <div class="container">
        <h1>Love Survey ❤️</h1>
        <p class="subtitle">Help us understand love better - Share your thoughts!</p>

        @if(session('error'))
        <div class="error-message">
            {{ session('error') }}
        </div>
        @endif

        <div id="landing-page">
            <form id="start-form">
                <div class="form-group">
                    <label for="user-name">Your Name</label>
                    <input type="text" id="user-name" name="name" placeholder="Enter your name" required minlength="2" maxlength="100">
                    <div class="error-message hidden" id="name-error"></div>
                </div>
                <button type="submit" class="btn">Start Survey</button>
            </form>
        </div>

        <div id="survey-page" class="hidden">
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
            </div>
            <div class="progress-text" id="progress-text">Question 1 of 10</div>

            <form id="survey-form" action="{{ route('survey.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="name" id="hidden-name">
                
                @foreach($questions as $index => $q)
                <div class="question-container" data-question="{{ $index + 1 }}">
                    <h2 class="question-title">{{ $q['question'] }}</h2>
                    
                    @foreach($q['options'] as $key => $option)
                    <div class="option" data-value="{{ $key }}">
                        <input type="radio" name="answers[{{ $index + 1 }}]" value="{{ $key }}" id="q{{ $index + 1 }}_{{ $key }}">
                        <label for="q{{ $index + 1 }}_{{ $key }}" style="cursor: pointer; flex: 1; margin: 0;">
                            <strong>{{ $key }}.</strong> {{ $option }}
                        </label>
                    </div>
                    @endforeach

                    <button type="button" class="btn next-btn" data-question="{{ $index + 1 }}" disabled>
                        {{ $index < 9 ? 'Next' : 'Submit Survey' }}
                    </button>
                </div>
                @endforeach
            </form>
        </div>

        <div id="loading-page" class="hidden">
            <div class="loading">
                <div class="spinner"></div>
                <p style="margin-top: 15px; color: #666;">Submitting your responses...</p>
            </div>
        </div>
    </div>

    <script>
        const totalQuestions = {{ count($questions) }};
        let currentQuestion = 1;
        let userName = '';
        const answers = {};

        const landingPage = document.getElementById('landing-page');
        const surveyPage = document.getElementById('survey-page');
        const loadingPage = document.getElementById('loading-page');
        const startForm = document.getElementById('start-form');
        const surveyForm = document.getElementById('survey-form');
        const progressFill = document.getElementById('progress-fill');
        const progressText = document.getElementById('progress-text');

        startForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const nameInput = document.getElementById('user-name');
            const nameError = document.getElementById('name-error');
            
            userName = nameInput.value.trim();
            
            if (userName.length < 2) {
                nameError.textContent = 'Name must be at least 2 characters.';
                nameError.classList.remove('hidden');
                return;
            }

            document.getElementById('hidden-name').value = userName;
            
            landingPage.classList.add('hidden');
            surveyPage.classList.remove('hidden');
            showQuestion(1);
        });

        function showQuestion(questionNum) {
            document.querySelectorAll('.question-container').forEach(q => {
                q.classList.remove('active');
            });

            const questionEl = document.querySelector(`[data-question="${questionNum}"]`);
            if (questionEl) {
                questionEl.classList.add('active');
            }

            const progress = (questionNum / totalQuestions) * 100;
            progressFill.style.width = progress + '%';
            progressText.textContent = `Question ${questionNum} of ${totalQuestions}`;

            if (answers[questionNum]) {
                const radio = document.querySelector(`input[name="answers[${questionNum}]"][value="${answers[questionNum]}"]`);
                if (radio) {
                    radio.checked = true;
                    updateOptionStyles(questionNum);
                    enableNextButton(questionNum);
                }
            }
        }

        document.querySelectorAll('.option').forEach(option => {
            option.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                const questionNum = parseInt(this.closest('.question-container').dataset.question);
                answers[questionNum] = radio.value;
                
                updateOptionStyles(questionNum);
                enableNextButton(questionNum);
            });
        });

        function updateOptionStyles(questionNum) {
            const container = document.querySelector(`[data-question="${questionNum}"]`);
            container.querySelectorAll('.option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            const selectedRadio = container.querySelector('input[type="radio"]:checked');
            if (selectedRadio) {
                selectedRadio.closest('.option').classList.add('selected');
            }
        }

        function enableNextButton(questionNum) {
            const btn = document.querySelector(`button[data-question="${questionNum}"]`);
            const answered = document.querySelector(`input[name="answers[${questionNum}]"]:checked`);
            btn.disabled = !answered;
        }

        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const questionNum = parseInt(this.dataset.question);
                
                if (questionNum < totalQuestions) {
                    currentQuestion = questionNum + 1;
                    showQuestion(currentQuestion);
                } else {
                    submitSurvey();
                }
            });
        });

        function submitSurvey() {
            surveyPage.classList.add('hidden');
            loadingPage.classList.remove('hidden');
            
            surveyForm.submit();
        }

        window.addEventListener('load', function() {
            landingPage.classList.remove('hidden');
        });
    </script>
</body>
</html>