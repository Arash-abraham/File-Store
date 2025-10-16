<div id="wallet" class="content-section hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @if(session('success'))
            <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle ml-2"></i>
                    {{ session('success') }}
                    @if(session('ref_id'))
                        <span class="mr-4">کد پیگیری: <strong>{{ session('ref_id') }}</strong></span>
                    @endif
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle ml-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif
        
        <!-- Wallet Balance -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">موجودی کیف پول</h2>
            
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-blue-100 mb-2">موجودی فعلی</p>
                        <p class="text-3xl font-bold">{{ $wallet->formatted_balance }}</p>
                    </div>
                    <i class="fas fa-wallet text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="space-y-4">
                <a href="{{ route('wallet.deposit') }}" 
                   class="block w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold text-center">
                    <i class="fas fa-plus ml-2"></i>
                    شارژ کیف پول
                </a>
                <button class="w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors"
                        onclick="showSection('dashboard')">
                    بازگشت به داشبورد
                </button>
            </div>

            <!-- اطلاعات سریع -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-3">اطلاعات حساب</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="text-gray-600">تعداد تراکنش‌ها:</div>
                    <div class="text-gray-800 font-medium">{{ $wallet->transactions()->count() }}</div>
                    
                    <div class="text-gray-600">آخرین تراکنش:</div>
                    <div class="text-gray-800 font-medium">
                        @if($walletTransactions->count() > 0)
                            {{ \Morilog\Jalali\Jalalian::forge($walletTransactions->first()->created_at)->format('Y/m/d') }}
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6">آخرین تراکنش‌ها</h3>
            
            @if($walletTransactions->count() > 0)
                <div class="space-y-4">
                    @foreach($walletTransactions as $transaction)
                        <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center ml-3 
                                    @if(in_array($transaction->type, ['deposit', 'refund'])) bg-green-100 text-green-600
                                    @else bg-red-100 text-red-600 @endif">
                                    @if(in_array($transaction->type, ['deposit', 'refund']))
                                        <i class="fas fa-plus"></i>
                                    @else
                                        <i class="fas fa-minus"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $transaction->type_label }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Morilog\Jalali\Jalalian::forge($transaction->created_at)->format('Y/m/d H:i') }}
                                    </p>
                                    @if($transaction->ref_id)
                                        <p class="text-xs text-gray-400">کد پیگیری: {{ $transaction->ref_id }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-left">
                                <p class="@if(in_array($transaction->type, ['deposit', 'refund'])) text-green-600 @else text-red-600 @endif font-semibold">
                                    {{ $transaction->formatted_amount }}
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($transaction->status == 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $transaction->status_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500">هنوز تراکنشی ثبت نشده است</p>
                    <p class="text-sm text-gray-400 mt-1">پس از اولین تراکنش، تاریخچه اینجا نمایش داده می‌شود</p>
                </div>
            @endif

            <!-- راهنمای سریع -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-3">راهنمای کیف پول</h4>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-center">
                        <i class="fas fa-plus text-green-500 ml-2 text-xs"></i>
                        افزایش موجودی از طریق درگاه پرداخت
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-shopping-cart text-blue-500 ml-2 text-xs"></i>
                        پرداخت سریع خریدها با موجودی کیف پول
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-history text-purple-500 ml-2 text-xs"></i>
                        مشاهده تاریخچه کامل تراکنش‌ها در همین بخش
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>