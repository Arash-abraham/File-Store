<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت موفق - فایل استور</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body { 
            font-family: 'Tahoma', 'Segoe UI', Arial, sans-serif; 
            direction: rtl;
            text-align: right;
            line-height: 1.6;
            background: #f8f9fa;
            color: #333;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        .header { 
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white; 
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .content { 
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .success-message {
            background: #e8f5e8;
            border: 2px solid #4CAF50;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.1);
        }
        .success-message h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 8px;
        }
        .payment-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #d4edda;
        }
        .payment-detail:last-child {
            border-bottom: none;
        }
        .payment-detail strong {
            color: #2c3e50;
            min-width: 120px;
        }
        .payment-detail span {
            color: #27ae60;
            font-weight: bold;
        }
        .cta-section {
            text-align: center;
            margin: 30px 0 20px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 14px 35px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(76, 175, 80, 0.4);
        }
        .footer { 
            text-align: center; 
            padding: 30px;
            color: #7f8c8d;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
        }
        .footer strong {
            color: #2c3e50;
        }
        .contact-info {
            margin-top: 15px;
            line-height: 1.8;
        }
        .icon {
            margin-left: 8px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .header, .content {
                padding: 25px 20px;
            }
            .payment-detail {
                flex-direction: column;
                align-items: flex-start;
            }
            .payment-detail span {
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ پرداخت شما با موفقیت انجام شد</h1>
            <p>سفارش شما با موفقیت ثبت و پرداخت گردید</p>
        </div>
        
        <div class="content">
            <p class="greeting">سلام <strong>{{ $user->name ?? 'کاربر گرامی' }}</strong>،</p>
            <p>پرداخت شما در تاریخ <strong>{{ $paymentTime }}</strong> با موفقیت انجام شد.</p>
            
            <div class="success-message">
                <h3>📋 جزئیات پرداخت</h3>
                <div class="payment-detail">
                    <strong>💰 مبلغ پرداختی:</strong>
                    <span>{{ number_format($payment['amount']) }} تومان</span>
                </div>
                <div class="payment-detail">
                    <strong>📦 شماره سفارش:</strong>
                    <span>{{ $payment['order_id'] ?? '---' }}</span>
                </div>
                <div class="payment-detail">
                    <strong>🆔 کد رهگیری:</strong>
                    <span>{{ $payment['ref_id'] ?? $payment['authority'] ?? 'در حال پردازش' }}</span>
                </div>
                <div class="payment-detail">
                    <strong>🕒 زمان پرداخت:</strong>
                    <span>{{ $paymentTime }}</span>
                </div>
            </div>
            
            <p style="text-align: center; color: #27ae60; font-size: 16px; margin: 20px 0;">
                ✅ از خرید شما متشکریم! فایل‌های خریداری شده در پنل کاربری شما قابل دسترسی هستند.
            </p>
            
            <div class="cta-section">
                <a href="{{ url('/dashboard') }}" class="btn">
                    🚀 رفتن به پنل کاربری
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>با تشکر از اعتماد شما<br><strong>فایل استور</strong></p>
            <div class="contact-info">
                <p>
                    <span class="icon">📧</span>ایمیل: support@filestore.ir<br>
                    <span class="icon">📞</span>تلفن: ۰۲۱-۱۲۳۴۵۶۷۸
                </p>
            </div>
        </div>
    </div>
</body>
</html>