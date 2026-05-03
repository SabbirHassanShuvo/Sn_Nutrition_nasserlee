<!DOCTYPE html>
<html>
<head>
    <title>Queued Test Email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .info-box { background: #f8f9fa; border-left: 4px solid #4F46E5; padding: 15px; margin: 15px 0; }
        .footer { background: #f1f5f9; padding: 15px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚀 Queued Email Test</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userData['name'] ?? 'User' }}!</h2>
        <p>This email was sent via Laravel's queue system for better performance.</p>
        
        <div class="info-box">
            <h3>📊 Queue Information:</h3>
            <ul>
                <li><strong>Sent at:</strong> {{ now()->format('Y-m-d H:i:s') }}</li>
                <li><strong>Queue Driver:</strong> {{ config('queue.default') }}</li>
                <li><strong>App:</strong> {{ config('app.name') }}</li>
                <li><strong>Environment:</strong> {{ app()->environment() }}</li>
                @if(isset($userData['login_time']))
                <li><strong>Login Time:</strong> {{ $userData['login_time'] }}</li>
                @endif
            </ul>
        </div>
        
        <p>Your request was processed immediately while this email was queued for background processing!</p>
    </div>
    
    <div class="footer">
        <p>This is a test email from your Laravel application with Mailtrap.</p>
        <p>Queued at: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>