<!DOCTYPE html>
<html>
<head>
    <style>
        .email-container { font-family: sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; }
        .header { text-align: center; padding-bottom: 20px; }
        .button { background-color: #3BB77E; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .footer { font-size: 12px; color: #777; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Albatross Recruit</h2>
        </div>
        
        <div class="content">
            @yield('content')
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Albatross Recruit. All rights reserved.
        </div>
    </div>
</body>
</html>