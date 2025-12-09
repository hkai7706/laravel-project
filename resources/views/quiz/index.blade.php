<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>💕 Love Quiz - Test Your Love Knowledge</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        /* Floating hearts animation */
        .heart {
            position: fixed;
            font-size: 24px;
            animation: float 15s infinite;
            opacity: 0.3;
            pointer-events: none;
        }
        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease;
        }
        .header h1 {
            font-size: 48px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            margin-bottom: 10px;
        }
        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 18px;
        }
        .quiz-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease;
        }
        /* Start Screen */
        .start-screen {
            text-align: center;
        }
        .start-screen h2 {
            color: #764ba2;
            margin-bottom: 30px;
            font-size: 32px;
        }
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        .form-group label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
        }
        .difficulty-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(118, 75, 162, 0.6);
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        /* Quiz Screen */
        .quiz-screen {
            display: none;
        }
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .quiz-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .info-badge {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            color: #555;
        }
        .timer {
            background: #ff6b6b;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }
        .timer.warning {
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        .progress-bar {
            width: 100%;
            height: 10px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
        .question-container {
            margin-bottom: 30px;
        }
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .question-number {
            background: #764ba2;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }
        .category-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .question-text {
            font-size: 22px;
            color: #333;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .options-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .option {
            display: flex;
            align-items: center;
            padding: 18px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        .option:hover {
            border-color: #764ba2;
            background: #f8f9fa;
            transform: translateX(5px);
        }
        .option input[type="radio"] {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .option label {
            cursor: pointer;
            font-size: 16px;
            color: #555;
            flex: 1;
        }
        .option.selected {
            border-color: #764ba2;
            background: rgba(118, 75, 162, 0.1);
        }
        .option.correct {
            border-color: #4caf50;
            background: rgba(76, 175, 80, 0.1);
        }
        .option.incorrect {
            border-color: #f44336;
            background: rgba(244, 67, 54, 0.1);
        }
        .quiz-actions {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        /* Results Screen */
        .results-screen {
            display: none;
        }
        .results-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .badge-container {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounceIn 0.8s ease;
        }
        @keyframes bounceIn {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
        .rank-title {
            font-size: 32px;
            color: #764ba2;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .score-display {
            font-size: 48px;
            color: #333;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .score-display span {
            color: #764ba2;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #764ba2;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        .category-scores {
            margin-bottom: 30px;
        }
        .category-scores h3 {
            color: #764ba2;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .category-name {
            font-weight: 600;
            color: #555;
        }
        .category-score {
            font-weight: bold;
            color: #764ba2;
        }
        .results-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-secondary {
            background: #e0e0e0;
            color: #555;
        }
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        /* Leaderboard */
        .leaderboard-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease;
        }
        .leaderboard-card h2 {
            color: #764ba2;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }
        .leaderboard-table {
            width: 100%;
        }
        .leaderboard-header {
            display: grid;
            grid-template-columns: 60px 1fr 100px 150px;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            font-weight: 600;
            color: #555;
            margin-bottom: 15px;
        }
        .leaderboard-row {
            display: grid;
            grid-template-columns: 60px 1fr 100px 150px;
            gap: 15px;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            align-items: center;
        }
        .leaderboard-row:hover {
            border-color: #764ba2;
            background: rgba(118, 75, 162, 0.05);
            transform: translateX(5px);
        }
        .rank-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
        .rank-1 { background: linear-gradient(135deg, #ffd700, #ffed4e); color: #333; }
        .rank-2 { background: linear-gradient(135deg, #c0c0c0, #e8e8e8); color: #333; }
        .rank-3 { background: linear-gradient(135deg, #cd7f32, #e3a16d); color: white; }
        .rank-other { background: #764ba2; }
        .player-info {
            display: flex;
            flex-direction: column;
        }
        .player-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .player-badge {
            font-size: 12px;
            color: #666;
        }
        .score {
            font-size: 24px;
            font-weight: bold;
            color: #764ba2;
        }
        .difficulty-tag {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        .difficulty-Easy { background: #c8e6c9; color: #2e7d32; }
        .difficulty-Medium { background: #fff9c4; color: #f57f17; }
        .difficulty-Hard { background: #ffcdd2; color: #c62828; }
        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Responsive */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 36px;
            }
            .quiz-card {
                padding: 25px;
            }
            .leaderboard-header,
            .leaderboard-row {
                grid-template-columns: 50px 1fr 80px;
                gap: 10px;
                font-size: 14px;
            }
            .difficulty-tag {
                display: none;
            }
            .quiz-info {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Hearts -->
    <div class="heart" style="left: 10%; animation-delay: 0s;">💖</div>
    <div class="heart" style="left: 20%; animation-delay: 2s;">💕</div>
    <div class="heart" style="left: 30%; animation-delay: 4s;">💗</div>
    <div class="heart" style="left: 40%; animation-delay: 1s;">💓</div>
    <div class="heart" style="left: 50%; animation-delay: 3s;">💝</div>
    <div class="heart" style="left: 60%; animation-delay: 5s;">💖</div>
    <div class="heart" style="left: 70%; animation-delay: 2.5s;">💕</div>
    <div class="heart" style="left: 80%; animation-delay: 4.5s;">💗</div>
    <div class="heart" style="left: 90%; animation-delay: 1.5s;">💓</div>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>💕 Love Quiz 💕</h1>
            <p>တစ်ယောက်အကြောင်းတစ်ယောက်ဘယ်လောက်သိလဲပြိုင်ကြမယ်,ဖြေကြည့်ကြည့်နော်အဟိ<:3 fighting💓</p>
        </div>
        <!-- Quiz Card -->
        <div class="quiz-card">
            <!-- Start Screen -->
            <div class="start-screen" id="startScreen">
                <h2>Welcome to the Love Quiz!</h2>
                <div id="errorMessage"></div>
                
                <form id="startForm">
                    <div class="form-group">
                        <label for="playerName">နာမည်ထည့်ပါ*</label>
                        <input type="text" id="playerName" name="name" required
                               placeholder="Enter your name" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label for="difficulty">ခက်ခဲမှုအဆင့်ကိုရွှေးပါ *</label>
                        <select id="difficulty" name="difficulty" required>
                            <option value="">Choose difficulty level</option>
                            <option value="Easy">Easy (60 seconds per question)</option>
                            <option value="Medium">Medium (45 seconds per question)</option>
                            <option value="Hard">Hard (30 seconds per question)</option>
                        </select>
                        <div class="difficulty-info">
                            💡 သေချာ‌ဖြေနော်ဘေဘီ နှစ်ယောက်လုံးနဲ့ပက်သတ်တာတွေပဲအကုန်ကအမှတ်များများရအောင်ဖြေနော်  40မကျော်ရင်စိတ်ကောက်ပြီဗျ"<br/> 
                            💕 result ကိုmemory wall မှာပြန်ရေးသွားဖို့လည်းမမေ့နဲ့နော်ဘေဘီ! 💕
                        </div>
                    </div>
                    <button type="submit" class="btn" id="startBtn">
                        Start Quiz 💖
                    </button>
                </form>
            </div>
            <!-- Quiz Screen -->
            <div class="quiz-screen" id="quizScreen">
                <div class="quiz-header">
                    <div class="quiz-info">
                        <div class="info-badge">
                            <span id="playerNameDisplay"></span>
                        </div>
                        <div class="info-badge">
                            Difficulty: <span id="difficultyDisplay"></span>
                        </div>
                    </div>
                    <div class="timer" id="timer">
                        ⏱️ <span id="timeLeft">60</span>s
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="question-container">
                    <div class="question-header">
                        <div class="question-number" id="questionNumber">
                            Question 1 of 20
                        </div>
                        <div class="category-badge" id="categoryBadge">
                            Category
                        </div>
                    </div>
                    <div class="question-text" id="questionText">
                        Question will appear here
                    </div>
                    <div class="options-container" id="optionsContainer">
                        <!-- Options will be inserted here -->
                    </div>
                </div>
                <div class="quiz-actions">
                    <button class="btn" id="submitAnswerBtn" disabled>
                        Submit Answer 💝
                    </button>
                </div>
            </div>
            <!-- Results Screen -->
            <div class="results-screen" id="resultsScreen">
                <div class="results-header">
                    <div class="badge-container" id="badgeDisplay">
                        💖
                    </div>
                    <div class="rank-title" id="rankTitle">
                        Love Master
                    </div>
                    <div class="score-display">
                        Score: <span id="finalScore">0</span>/100
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value" id="correctCount">0</div>
                        <div class="stat-label">Correct</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="incorrectCount">0</div>
                        <div class="stat-label">Incorrect</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="timeTaken">0:00</div>
                        <div class="stat-label">Time Taken</div>
                    </div>
                </div>
                <div class="category-scores">
                    <h3>Category Breakdown</h3>
                    <div id="categoryScoresContainer">
                        <!-- Category scores will be inserted here -->
                    </div>
                </div>
                <div class="results-actions">
                    <button class="btn" onclick="location.reload()">
                        Try Again 💕
                    </button>
                    <button class="btn btn-secondary" id="shareBtn">
                        Share Results 📤
                    </button>
                </div>
            </div>
        </div>
        <!-- Leaderboard -->
        <div class="leaderboard-card">
            <h2>🏆 Leaderboard 🏆</h2>
            <div class="leaderboard-header">
                <div>Rank</div>
                <div>Player</div>
                <div>Score</div>
                <div>Difficulty</div>
            </div>
            <div id="leaderboardContainer">
                @if(isset($leaderboard) && count($leaderboard) > 0)
                    @foreach($leaderboard as $entry)
                    <div class="leaderboard-row">
                        <div class="rank-badge rank-{{ $entry['rank'] <= 3 ? $entry['rank'] : 'other' }}">
                            {{ $entry['rank'] }}
                        </div>
                        <div class="player-info">
                            <div class="player-name">{{ $entry['name'] }}</div>
                            <div class="player-badge">{{ $entry['badge'] }}</div>
                        </div>
                        <div class="score">{{ $entry['score'] }}</div>
                        <div class="difficulty-tag difficulty-{{ $entry['difficulty'] }}">
                            {{ $entry['difficulty'] }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 40px; color: #999;">
                        No entries yet. Be the first to take the quiz!
                    </div>
                @endif
            </div>
        </div>
    </div>
   <script>
    // Quiz State
    let quizState = {
        questions: [],
        currentQuestionIndex: 0,
        selectedAnswer: null,
        timeLimit: 60,
        timeLeft: 60,
        timerInterval: null,
        startTime: null,
        answers: []
    };
    
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // DOM Elements
    const startScreen = document.getElementById('startScreen');
    const quizScreen = document.getElementById('quizScreen');
    const resultsScreen = document.getElementById('resultsScreen');
    const startForm = document.getElementById('startForm');
    const errorMessage = document.getElementById('errorMessage');
    
    // Start Quiz
    startForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(startForm);
        const startBtn = document.getElementById('startBtn');
        
        startBtn.disabled = true;
        startBtn.textContent = 'Starting...';
        errorMessage.innerHTML = '';
        
        try {
            const response = await fetch('/quiz/start', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            console.log('Quiz data received:', data); // Debug log
            
            if (!response.ok) {
                throw new Error(data.error || 'Failed to start quiz');
            }
            
            // Initialize quiz
            quizState.questions = data.questions;
            quizState.timeLimit = data.time_limit;
            quizState.timeLeft = data.time_limit;
            quizState.startTime = Date.now();
            
            console.log('Questions loaded:', quizState.questions); // Debug log
            
            // Update UI
            document.getElementById('playerNameDisplay').textContent = data.user.name;
            document.getElementById('difficultyDisplay').textContent = data.attempt.difficulty;
            
            // Show quiz screen
            startScreen.style.display = 'none';
            quizScreen.style.display = 'block';
            
            // Load first question
            loadQuestion();
            startTimer();
            
        } catch (error) {
            console.error('Start quiz error:', error);
            errorMessage.innerHTML = `<div class="error-message">${error.message}</div>`;
            startBtn.disabled = false;
            startBtn.textContent = 'Start Quiz 💖';
        }
    });
    
    // Load Question - FIXED
    function loadQuestion() {
        const question = quizState.questions[quizState.currentQuestionIndex];
        const progress = ((quizState.currentQuestionIndex + 1) / quizState.questions.length) * 100;
        
        console.log('Loading question:', question); // Debug log
        
        document.getElementById('questionNumber').textContent =
            `Question ${quizState.currentQuestionIndex + 1} of ${quizState.questions.length}`;
        document.getElementById('categoryBadge').textContent = question.category;
        document.getElementById('questionText').textContent = question.question;
        document.getElementById('progressFill').style.width = `${progress}%`;
        
        // Load options - FIXED
        const optionsContainer = document.getElementById('optionsContainer');
        optionsContainer.innerHTML = '';
        
        // Handle both array and JSON string formats
        let options = question.options;
        if (typeof options === 'string') {
            try {
                options = JSON.parse(options);
            } catch (e) {
                console.error('Error parsing options:', e);
                options = [];
            }
        }
        
        console.log('Options to display:', options); // Debug log
        
        if (!Array.isArray(options) || options.length === 0) {
            console.error('No valid options found!');
            optionsContainer.innerHTML = '<p style="color: red;">Error loading options</p>';
            return;
        }
        
        options.forEach((option, index) => {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'option';
            optionDiv.innerHTML = `
                <input type="radio" name="answer" id="option${index}" value="${index}">
                <label for="option${index}">${option}</label>
            `;
            optionDiv.addEventListener('click', () => selectOption(index));
            optionsContainer.appendChild(optionDiv);
        });
        
        // Reset timer
        quizState.timeLeft = quizState.timeLimit;
        updateTimerDisplay();
        
        // Reset selected answer
        quizState.selectedAnswer = null;
        document.getElementById('submitAnswerBtn').disabled = true;
    }
    
    // Select Option
    function selectOption(index) {
        quizState.selectedAnswer = index;
        
        // Update UI
        document.querySelectorAll('.option').forEach((opt, i) => {
            opt.classList.remove('selected');
            if (i === index) {
                opt.classList.add('selected');
                opt.querySelector('input').checked = true;
            }
        });
        
        document.getElementById('submitAnswerBtn').disabled = false;
    }
    
    // Timer - FIXED
    function startTimer() {
        // Clear any existing timer
        if (quizState.timerInterval) {
            clearInterval(quizState.timerInterval);
        }
        
        console.log('Starting timer...'); // Debug log
        
        quizState.timerInterval = setInterval(() => {
            quizState.timeLeft--;
            updateTimerDisplay();
            
            if (quizState.timeLeft <= 10) {
                document.getElementById('timer').classList.add('warning');
            } else {
                document.getElementById('timer').classList.remove('warning');
            }
            
            if (quizState.timeLeft <= 0) {
                console.log('Time out!');
                submitAnswer(true);
            }
        }, 1000);
    }
    
    function updateTimerDisplay() {
        const timerElement = document.getElementById('timeLeft');
        if (timerElement) {
            timerElement.textContent = quizState.timeLeft;
        }
    }
    
    function stopTimer() {
        if (quizState.timerInterval) {
            clearInterval(quizState.timerInterval);
            quizState.timerInterval = null;
        }
    }
    
    // Submit Answer
    document.getElementById('submitAnswerBtn').addEventListener('click', () => {
        submitAnswer(false);
    });
    
    async function submitAnswer(isTimeout) {
        stopTimer();
        
        const question = quizState.questions[quizState.currentQuestionIndex];
        const answer = isTimeout ? -1 : quizState.selectedAnswer;
        const timeTaken = quizState.timeLimit - quizState.timeLeft;
        
        try {
            const response = await fetch('/quiz/answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    question_id: question.id,
                    answer: answer,
                    time_taken: timeTaken
                })
            });
            
            const data = await response.json();
            
            // Store answer
            quizState.answers.push({
                questionId: question.id,
                selectedAnswer: answer,
                isCorrect: data.is_correct,
                correctAnswer: data.correct_answer
            });
            
            // Show correct/incorrect briefly
            if (!isTimeout) {
                highlightAnswer(data.correct_answer, data.is_correct);
                await new Promise(resolve => setTimeout(resolve, 1500));
            }
            
            // Move to next question or finish
            quizState.currentQuestionIndex++;
            
            if (quizState.currentQuestionIndex < quizState.questions.length) {
                loadQuestion();
                startTimer();
            } else {
                finishQuiz();
            }
            
        } catch (error) {
            console.error('Error submitting answer:', error);
            alert('Error submitting answer. Please try again.');
            startTimer();
        }
    }
    
    function highlightAnswer(correctIndex, isCorrect) {
        document.querySelectorAll('.option').forEach((opt, i) => {
            if (i === correctIndex) {
                opt.classList.add('correct');
            } else if (i === quizState.selectedAnswer && !isCorrect) {
                opt.classList.add('incorrect');
            }
        });
    }
    
    // ... Rest of your JavaScript code stays the same ...
</script>
</body>
</html>