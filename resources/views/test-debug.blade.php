<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Simple</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: white;
        }
        
        .hero {
            background: linear-gradient(135deg, #475569 0%, #64748b 50%, #0f766e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        
        h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .btn {
            background: white;
            color: #333;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <section class="hero">
        <div>
            <h1>RUNER RUNNING CLUB</h1>
            <p>Testing - No White Space Above</p>
            <a href="#" class="btn">JOIN NOW</a>
        </div>
    </section>
</body>
</html>
