<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سبد خرید و پرداخت</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 font-iransans">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">سبد خرید شما</h1>

        @if ($cartItems->isEmpty())
            <p class="text-center text-gray-500">سبد خرید شما خالی است!</p>
        @else
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                @foreach ($cartItems as $item)
                    <div class="flex items-center justify-between py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <img src="{{ asset($item->product->image_urls[0]) }}" alt="{{ $item->product->title }}" class="w-16 h-16 object-cover rounded mr-4">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $item->product->title }}</h3>
                                <p class="text-gray-600">{{ number_format($item->unit_price) }} تومان × {{ $item->quantity }}</p>
                            </div>
                        </div>
                        <p class="text-gray-800 font-bold">{{ number_format($item->subtotal) }} تومان</p>
                    </div>
                @endforeach

                <!-- کد تخفیف -->
                <div class="mt-4">
                    <div class="flex gap-2">
                        <input type="text" id="couponInput" placeholder="کد تخفیف" class="flex-1 p-2 border rounded">
                        <button onclick="applyCouponCheckout()" class="bg-yellow-500 text-white px-4 py-2 rounded">اعمال</button>
                    </div>
                    <p id="couponMessage" class="text-sm text-green-600 mt-1"></p>
                </div>

                <!-- مجموع -->
                <div class="mt-6 text-right">
                    <p class="text-lg">جمع کل: <span id="totalAmount">{{ number_format($total) }}</span> تومان</p>
                    @if ($discount > 0)
                        <p class="text-lg text-green-600">تخفیف: {{ number_format($discount) }} تومان</p>
                        <p class="text-lg font-bold">قابل پرداخت: {{ number_format($total - $discount) }} تومان</p>
                    @endif
                </div>
            </div>

            <!-- دکمه پرداخت -->
            <form id="paymentForm" action="{{ route('api.checkout') }}" method="POST" class="text-center">
                @csrf
                <input type="hidden" name="session_token" value="{{ request()->header('session-token') }}">
                <input type="hidden" name="payment_gateway" value="zarinpal">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-credit-card mr-2"></i> پرداخت
                </button>
            </form>
        @endif

        <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-600 hover:underline">بازگشت به صفحه اصلی</a>
    </div>

    <script>
        let discount = {{ $discount ?? 0 }};
        let total = {{ $total }};

        function applyCouponCheckout() {
            const couponInput = document.getElementById('couponInput').value;
            const couponMessage = document.getElementById('couponMessage');
            const totalAmount = document.getElementById('totalAmount');

            if (couponInput === 'DISCOUNT10') {
                discount = Math.floor(total * 0.1);
                couponMessage.textContent = 'کد تخفیف 10% اعمال شد!';
            } else {
                discount = 0;
                couponMessage.textContent = 'کد تخفیف نامعتبر است!';
            }
            document.getElementById('totalAmount').textContent = number_format(total - discount);
        }

        function number_format(number) {
            return Number(number).toLocaleString('fa-IR');
        }

        // ارسال فرم و هدایت به درگاه
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    session_token: this.querySelector('input[name="session_token"]').value,
                    payment_gateway: 'zarinpal'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.payment_url) {
                    window.location.href = data.data.payment_url; // هدایت به درگاه
                } else {
                    alert('خطا در ایجاد پرداخت: ' + data.message);
                }
            })
            .catch(error => console.error('خطا:', error));
        });
    </script>
</body>
</html>