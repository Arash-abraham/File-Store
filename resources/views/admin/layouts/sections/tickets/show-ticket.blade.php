@extends('admin.layouts.partials.master')

@section('content')
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-4xl mx-auto">
        <!-- هدر تیکت -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">تیکت {{$ticket->id}}</h1>
                        <p class="text-gray-600 mt-1">{{$ticket->subject}}</p>
                    </div>
                    <div class="text-left">
                        @if ($ticket->status == 'open')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                باز
                            </span>
                        @elseif ($ticket->status == 'in_progress')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                درحال بررسی
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                بسته شد
                            </span>
                        @endif
                    </div>
                </div>
        
                <!-- اطلاعات تیکت -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex">
                    <span class="text-gray-500 w-24">کاربر:</span>
                    <span class="text-gray-800">{{$ticket->user->name}}</span>
                    </div>
                    <div class="flex">
                    <span class="text-gray-500 w-24">دسته‌بندی:</span>
                    <span class="text-gray-800">{{$ticket->assigned_to}}</span>
                    </div>
                    <div class="flex">
                    <span class="text-gray-500 w-24">تاریخ ایجاد:</span>
                    <span class="text-gray-800">
                        {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($ticket->created_at)->tz('Asia/Tehran'))->format('Y-m-d H:i:s') }}
                    </span>
                    </div>
                </div>
                </div>
    
        <!-- پیام اصلی کاربر -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                    {{substr($ticket->user->name,0,1)}}
                    </div>
                    <div class="mr-3">
                    <div class="font-medium text-gray-800">{{$ticket->user->name}}</div>
                    <div class="text-sm text-gray-500">1402/10/15 - 14:30</div>
                    </div>
                </div>
                <div class="text-gray-700 leading-relaxed">
                    با سلام،
                    <br><br>
                    هنگام تلاش برای پرداخت از طریق درگاه بانک سامان، با خطای "اتصال ناموفق" مواجه می‌شوم. این مشکل از دیروز شروع شده و تاکنون چندین بار تلاش کرده‌ام اما نتیجه‌ای نداشته است.
                    <br><br>
                    لطفاً راهنمایی بفرمایید.
                </div>
            </div>
    
        <!-- تاریخچه پاسخ‌ها -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
                <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                    پ
                </div>
                <div class="mr-3">
                    <div class="font-medium text-gray-800">پشتیبانی فنی
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded mr-2">پشتیبان</span>
                    </div>
                    <div class="text-sm text-gray-500">1402/10/15 - 15:45</div>
                </div>
                </div>
                <div class="text-gray-700 leading-relaxed">
                با سلام آقای رضایی،
                <br><br>
                مشکل شما را بررسی کردیم. به نظر می‌رسد مشکل از سمت سرور درگاه پرداخت باشد. لطفاً موارد زیر را بررسی کنید:
                <br><br>
                1. از آخرین نسخه مرورگر استفاده کنید
                <br>
                2. افزونه‌های مسدودکننده را غیرفعال کنید
                <br>
                3. پس از 1 ساعت مجدد تلاش کنید
                <br><br>
                در صورت ادامه مشکل، اطلاع دهید.
                </div>
            </div>
    
        <!-- فرم پاسخ -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">پاسخ به تیکت</h3>
            <form id="replyForm">
            <div class="mb-4">
                <textarea 
                id="message"
                rows="6" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="پاسخ خود را وارد کنید..."
                required
                ></textarea>
            </div>
            
            <div class="flex justify-between items-center">
                <div class="flex space-x-3 space-x-reverse">
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition duration-200"
                >
                    ارسال پاسخ
                </button>
                <button 
                    type="button" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition duration-200"
                >
                    درحال بررسی
                </button>
                <button 
                    type="button" 
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md transition duration-200"
                >
                    بستن تیکت
            </button>
                </div>
                
                <div class="flex items-center space-x-2 space-x-reverse">
                <label class="text-sm text-gray-600">اولویت:</label>
                <select id="priority" class="border border-gray-300 rounded px-3 py-1 text-sm">
                    <option value="کم">کم</option>
                    <option value="متوسط" selected>متوسط</option>
                    <option value="بالا">بالا</option>
                </select>
                </div>
            </div>
                </form>
            </div>
        </div>
    </div>
    @section('js')
        <script src="{{asset('js/admin/category.js')}}"></script>
        <script>
            document.getElementById('replyForm').addEventListener('submit', function(e) {
              e.preventDefault();
              const message = document.getElementById('message').value;
              const priority = document.getElementById('priority').value;
              
              if (message.trim()) {
                alert('پاسخ با موفقیت ارسال شد');
                document.getElementById('message').value = '';
              }
            });
        </script>
    @endsection
@endsection