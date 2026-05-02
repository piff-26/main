<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #111;
            padding: 24px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            color: #ff5b1d;
            margin: 0;
            font-size: 24px;
        }

        .body {
            background: #f9f9f9;
            padding: 24px;
            border: 1px solid #eee;
            white-space: pre-wrap; /* to preserve newlines from textarea */
        }

        .footer {
            background: #111;
            color: #888;
            text-align: center;
            padding: 16px;
            font-size: 12px;
            border-radius: 0 0 8px 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>PIFF 2026</h1>
            <p style="color:#ccc; margin:4px 0 0;">{{ $subjectText }}</p>
        </div>
        <div class="body">
            {!! nl2br(e($messageContent)) !!}
        </div>
        <div class="footer">
            &copy; 2026 PIFF - Petra International Film Festival
        </div>
    </div>
</body>

</html>
