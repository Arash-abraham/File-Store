<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کد تأیید</title>
    <style>
        body {
            direction: rtl;
            text-align: right;
            font-family: Tahoma, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin: 25px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border: 2px dashed #007bff;
            border-radius: 8px;
            color: #007bff;
        }
        
        .info-text {
            text-align: right;
            margin: 15px 0;
            padding: 10px;
            line-height: 1.8;
        }
        
        .expiry {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 12px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
        
        .warning {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 12px;
            margin: 20px 0;
            text-align: center;
            color: #721c24;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        
        .salutation {
            text-align: left;
            margin-top: 25px;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>کد تأیید ورود</h1>
        </div>
        
        <div class="info-text">
            سلام و درود
        </div>
        
        <div class="otp-code">
            {{ $otp }}
        </div>
        
        <div class="info-text">
            این کد ۶ رقمی برای ورود به حساب کاربری شما می‌باشد.
        </div>
        
        <div class="expiry">
            ⏰ این کد تا ساعت <strong>{{ $expiryTime }}</strong> معتبر است.
        </div>
        
        <div class="warning">
            ⚠️ اگر این درخواست را انجام نداده‌اید، لطفاً این ایمیل را نادیده بگیرید.
        </div>
        
        <div class="footer">
            با احترام،<br>
            <strong>تیم پشتیبانی ما - فایل استور</strong>
        </div>
    </div>
</body>
</html>