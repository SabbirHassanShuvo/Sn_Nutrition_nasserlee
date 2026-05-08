<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .logo {
            font-size: 42px;
            margin-bottom: 15px;
        }
        
        h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .message {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #555;
        }
        
        .button-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white !important;
            text-decoration: none;
            padding: 15px 35px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(37, 117, 252, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 117, 252, 0.4);
        }
        
        .footer {
            padding: 20px 40px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        
        .security-note {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-top: 10px;
        }
        
        .security-note i {
            color: #6a11cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-key"></i>
            </div>
            <h1>Reset Your Password</h1>
            <p class="subtitle">Securely regain access to your account</p>
        </div>
        
        <div class="content">
            <p class="message">
                Hello,
            </p>
            <p class="message">
                We received a request to reset the password for your account. Click the button below to choose a new password. This link is valid for a limited time only.
            </p>
            
            <div class="button-section">
                <a href="{{ $resetLink }}" class="btn">Reset Password</a>
            </div>
            
            <p class="message">
                If you did not request a password reset, please ignore this email or contact support if you have concerns.
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <div class="security-note">
                <i class="fas fa-lock"></i>
                <span>Secured with end-to-end encryption</span>
            </div>
        </div>
    </div>
</body>
</html>
