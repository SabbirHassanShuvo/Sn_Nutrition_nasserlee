<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; width: 100% !important;">
    @php 
        $setting = \App\Models\Setting::first(); 
        $baseUrl = request()->getSchemeAndHttpHost() ?: config('app.url');
        $logoUrl = ($setting && $setting->logo) ? $baseUrl . '/' . ltrim($setting->logo, '/') : null;
    @endphp
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f7f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); overflow: hidden;">
                    <!-- Top Logo Header -->
                    <tr>
                        <td align="center" style="padding: 40px 0 20px 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="font-size: 26px; font-weight: 800; color: #0ab39c; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; letter-spacing: 0.5px;">
                                SN <span style="color: #405189;">NUTRITION</span>
                            </span>
                        </td>
                    </tr>
                    
                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 50px 30px 50px;">
                            <h2 style="margin: 0 0 15px 0; color: #1e293b; font-size: 22px; font-weight: 700; text-align: left;">
                                Password Reset Request
                            </h2>
                            <p style="margin: 0 0 25px 0; font-size: 15px; line-height: 1.6; color: #475569; text-align: left;">
                                Hello,
                            </p>
                            <p style="margin: 0 0 25px 0; font-size: 15px; line-height: 1.6; color: #475569; text-align: left;">
                                We received a request to reset the password associated with your account. Use the verification code below to complete the process.
                            </p>
                            
                            <!-- OTP Box -->
                            <div style="background: #f0fdf4; border: 1px dashed #bbf7d0; border-radius: 8px; padding: 25px; text-align: center; margin: 30px 0;">
                                <span style="display: block; font-size: 12px; color: #16a34a; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px;">One-Time Verification Code</span>
                                <span style="font-size: 34px; font-weight: 800; color: #15803d; letter-spacing: 8px; font-family: 'Courier New', Courier, monospace; display: inline-block;">{{ $otp }}</span>
                            </div>

                            <p style="margin: 0 0 25px 0; font-size: 14px; color: #64748b; text-align: center;">
                                This OTP is valid for <strong>{{ $ttl }} minutes</strong>.
                            </p>
                            
                            <p style="margin: 0 0 25px 0; font-size: 15px; line-height: 1.6; color: #475569; text-align: left; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                                If you did not make this request, you can safely ignore this email. Your password will remain unchanged.
                            </p>
                            
                            <p style="margin: 30px 0 0 0; font-size: 15px; line-height: 1.6; color: #475569; text-align: left;">
                                Best regards,<br>
                                <strong style="color: #0ab39c;">The SN Nutrition Team</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer Note -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 25px 50px; border-top: 1px solid #f1f5f9; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                            <p style="margin: 0 0 8px 0;">This is an automated message. Please do not reply directly to this email.</p>
                            <p style="margin: 0;">&copy; {{ date('Y') }} SN Nutrition. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>