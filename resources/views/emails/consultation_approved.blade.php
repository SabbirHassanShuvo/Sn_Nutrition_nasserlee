<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consultation Booking Confirmed</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .details-card {
            background: #f8fafc;
            border-left: 4px solid #10b981;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .details-row {
            display: flex;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .details-row:last-child {
            margin-bottom: 0;
        }
        .details-label {
            font-weight: bold;
            width: 140px;
            color: #64748b;
        }
        .details-value {
            color: #1e293b;
            font-weight: 600;
        }
        .zoom-box {
            text-align: center;
            background: #ecfdf5;
            border: 1px dashed #10b981;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }
        .btn-join {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 30px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: background 0.3s;
        }
        .btn-join:hover {
            background-color: #059669;
        }
        .passcode-text {
            margin-top: 12px;
            font-size: 13px;
            color: #64748b;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Consultation Confirmed!</h1>
            <p>Your 1-on-1 expert session has been scheduled</p>
        </div>
        
        <div class="content">
            <div class="greeting">Hello {{ $booking->user_name }},</div>
            <p>Great news! Your consultation request has been approved by our admin team. Below are your session details:</p>

            <div class="details-card">
                <div class="details-row">
                    <span class="details-label">Booking Reference:</span>
                    <span class="details-value">#{{ $booking->booking_number }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Specialist:</span>
                    <span class="details-value">{{ $specialist ? $specialist->name : 'Nutrition Specialist' }} ({{ $specialist ? $specialist->title : 'Expert' }})</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Session Type:</span>
                    <span class="details-value">{{ $booking->call_type === 'video_call' ? 'Video Call' : 'Audio Call / Callback' }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Date:</span>
                    <span class="details-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Time Slot:</span>
                    <span class="details-value">{{ $booking->booking_time }}</span>
                </div>
                @if($booking->notes)
                <div class="details-row">
                    <span class="details-label">Topic / Note:</span>
                    <span class="details-value">{{ $booking->notes }}</span>
                </div>
                @endif
            </div>

            <div class="zoom-box">
                <h3 style="margin-top:0; color:#065f46;">Join Your Session</h3>
                <p style="font-size: 14px; color: #047857; margin-bottom: 20px;">Please click the button below at the scheduled time to join your meeting via Zoom.</p>
                
                <a href="{{ $booking->zoom_join_url }}" target="_blank" class="btn-join">Join Zoom Meeting</a>

                @if($booking->zoom_password)
                <div class="passcode-text">
                    Passcode: <strong>{{ $booking->zoom_password }}</strong>
                </div>
                @endif
            </div>

            <p style="font-size: 13px; color: #64748b; margin-top: 20px;">
                If you have any questions or need to reschedule, please contact our support team.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} SN Nutrition. All rights reserved.
        </div>
    </div>
</body>
</html>
