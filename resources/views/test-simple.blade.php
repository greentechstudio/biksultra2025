<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        
        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .btn {
            background: white;
            color: #333;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🏃‍♂️ RUNER Running Club</h1>
        <p>Simple test page - If you can see this, Laravel is working correctly!</p>
        
        <div style="margin: 30px 0;">
            <h3>Quick Navigation:</h3>
            @auth
                <a href="{{ route('dashboard') }}" class="btn">Go to Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn">Logout</button>
                </form>
            @else
                <a href="{{ route('register') }}" class="btn">Register</a>
                <a href="{{ route('login') }}" class="btn">Login</a>
            @endauth
            <a href="{{ url('/') }}" class="btn">Back to Home</a>
        </div>
        
        <div style="margin: 30px 0; font-size: 14px; opacity: 0.8;">
            <p>Current Time: {{ now()->format('Y-m-d H:i:s') }}</p>
            <p>User Status: {{ auth()->check() ? 'Logged In as ' . auth()->user()->name : 'Not Logged In' }}</p>
        </div>
    </div>
</body>
</html>
