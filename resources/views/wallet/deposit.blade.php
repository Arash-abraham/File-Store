<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شارژ کیف پول</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .amount-btn {
            transition: all 0.3s ease;
        }
        .amount-btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ url('/dashboard') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-arrow-right ml-2"></i>
                    بازگشت به داشبورد
                </a>
                <h1 class="text-xl font-bold text-gray-800">شارژ کیف پول</h1>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-xl shadow-lg p-8">
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle ml-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle ml-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="gradient-bg rounded-xl p-6 text-white mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-blue-100 mb-2">موجودی فعلی</p>
                            <p class="text-3xl font-bold">
                                @auth
                                    {{ auth()->user()->wallet->formatted_balance }}
                                @else
                                    ۰ تومان
                                @endauth
                            </p>
                        </div>
                        <i class="fas fa-wallet text-4xl opacity-50"></i>
                    </div>
                </div>

                <form action="{{ route('wallet.deposit.submit') }}" method="POST" id="depositForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            مبلغ مورد نظر برای شارژ (تومان)
                        </label>
                        <input type="number" 
                               name="amount" 
                               id="amount"
                               min="1000"
                               max="10000000"
                               step="1000"
                               required
                               value="{{ old('amount') }}"
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-lg font-semibold transition-colors"
                               placeholder="مثال: 50000">
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            حداقل مبلغ: ۱,۰۰۰ تومان | حداکثر مبلغ: ۱۰,۰۰۰,۰۰۰ تومان
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-6">
                        @foreach([10000, 50000, 100000, 200000, 500000, 1000000] as $suggestedAmount)
                            <button type="button" 
                                    onclick="setAmount({{ $suggestedAmount }}, this)"
                                    class="amount-btn bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg transition-all text-sm font-medium border border-gray-200">
                                {{ number_format($suggestedAmount) }}
                            </button>
                        @endforeach
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-shield-alt ml-2"></i>
                            درگاه پرداخت امن
                        </h4>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-blue-700">
                                <i class="fas fa-lock ml-2"></i>
                                پرداخت از طریق زرین‌پال
                            </div>

                        </div>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                            <i class="fas fa-info-circle ml-2"></i>
                            راهنمای شارژ کیف پول
                        </h4>
                        <ul class="text-sm text-green-700 space-y-1">
                            <li class="flex items-center">
                                <i class="fas fa-check ml-2 text-xs"></i>
                                مبلغ مورد نظر را وارد کنید
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check ml-2 text-xs"></i>
                                به درگاه پرداخت امن زرین‌پال هدایت خواهید شد
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check ml-2 text-xs"></i>
                                پس از پرداخت موفق، موجودی به کیف پول شما اضافه می‌شود
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check ml-2 text-xs"></i>
                                امکان استفاده از موجودی برای خریدهای آینده
                            </li>
                        </ul>
                    </div>

                    <button type="submit" 
                            id="submitBtn"
                            class="w-full bg-green-600 text-white py-4 rounded-lg hover:bg-green-700 transition-colors font-semibold text-lg shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-credit-card ml-2"></i>
                        پرداخت و افزایش موجودی
                    </button>

                    <div class="flex justify-between items-center mt-4 text-sm">
                        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                            <i class="fas fa-home ml-1"></i>
                            بازگشت به داشبورد
                        </a>
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                            <i class="fas fa-store ml-1"></i>
                            بازگشت به فروشگاه
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </main>


    <script>
        let selectedButton = null;

        function setAmount(amount, element) {
            document.getElementById('amount').value = amount;
            
            if (selectedButton) {
                selectedButton.classList.remove('bg-blue-500', 'text-white', 'border-blue-500', 'shadow-md');
                selectedButton.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
            }
            
            element.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-200');
            element.classList.add('bg-blue-500', 'text-white', 'border-blue-500', 'shadow-md');
            selectedButton = element;

            document.getElementById('amount').blur();
        }

        document.getElementById('amount').addEventListener('focus', function() {
            if (selectedButton) {
                selectedButton.classList.remove('bg-blue-500', 'text-white', 'border-blue-500', 'shadow-md');
                selectedButton.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
                selectedButton = null;
            }
        });

        document.getElementById('amount').addEventListener('input', function() {
            if (selectedButton) {
                selectedButton.classList.remove('bg-blue-500', 'text-white', 'border-blue-500', 'shadow-md');
                selectedButton.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
                selectedButton = null;
            }
        });

        document.getElementById('depositForm').addEventListener('submit', function(e) {
            const amount = document.getElementById('amount').value;
            const submitBtn = document.getElementById('submitBtn');
            
            if (!amount || amount < 1000 || amount > 10000000) {
                e.preventDefault();
                alert('لطفاً مبلغی بین ۱,۰۰۰ تا ۱۰,۰۰۰,۰۰۰ تومان وارد کنید');
                return false;
            }
            
            // نمایش لودینگ
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> در حال انتقال به درگاه...';
            submitBtn.disabled = true;
            submitBtn.classList.remove('hover:bg-green-700', 'hover:shadow-xl');
            submitBtn.classList.add('bg-green-400', 'cursor-not-allowed');
        });

        document.getElementById('amount').addEventListener('blur', function(e) {
            const value = e.target.value.replace(/,/g, '');
            if (!isNaN(value) && value !== '') {
                e.target.value = parseInt(value).toLocaleString('fa-IR');
            }
        });

        document.getElementById('amount').addEventListener('focus', function(e) {
            const value = e.target.value.replace(/,/g, '');
            if (!isNaN(value) && value !== '') {
                e.target.value = value;
            }
        });

        document.getElementById('amount').addEventListener('keypress', function(e) {
            const charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                e.preventDefault();
                return false;
            }
            return true;
        });
    </script>
</body>
</html>