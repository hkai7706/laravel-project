<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You! ❤️</title>
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
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #FF1493;
            animation: confetti-fall 3s linear forwards;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        .container {
            max-width: 600px;
            width: 90%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(255, 20, 147, 0.2);
            padding: 60px 40px;
            text-align: center;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: heartBeat 1s ease infinite;
        }

        @keyframes heartBeat {
            0%, 100% { transform: scale(1); }
            10%, 30% { transform: scale(1.1); }
            20%, 40% { transform: scale(1); }
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #FF1493;
            margin-bottom: 20px;
        }

        .message {
            font-size: 1.2rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .secondary-message {
            font-size: 1rem;
            color: #999;
            line-height: 1.5;
            margin-bottom: 40px;
        }

        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #FF1493 0%, #FF69B4 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 20, 147, 0.3);
        }

        .decorative-hearts {
            margin: 30px 0;
            font-size: 30px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            .container {
                padding: 40px 25px;
            }

            .success-icon {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">💕</div>
        
        <h1>Thank You for Completing<br>the Love Survey!</h1>
        
        <p class="message">
            Your responses help us understand love and relationships better. 
            We truly appreciate you taking the time to share your honest thoughts with us.
        </p>
        
        <p class="secondary-message">
            Your answers have been recorded and will contribute to our research 
            on modern relationships and the many ways people experience love.
        </p>

        <div class="decorative-hearts">
            💖 💗 💝 💘 💓
        </div>
        
        <a href="{{ route('survey.index') }}" class="btn">Take Survey Again</a>
    </div>

    <script>
        function createConfetti() {
            const colors = ['#FF1493', '#FF69B4', '#FFB6C1', '#FFC0CB', '#DDA0DD'];
            const confettiCount = 50;

            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 0.5 + 's';
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    
                    document.body.appendChild(confetti);

                    setTimeout(() => {
                        confetti.remove();
                    }, 5000);
                }, i * 30);
            }
        }

        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>