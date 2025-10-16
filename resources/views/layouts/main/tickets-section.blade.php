<div id="tickets" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">تیکت‌های پشتیبانی</h2>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showNewTicketModal()">
                تیکت جدید
            </button>
        </div>
        
        @if($tickets->count() > 0)
            <div class="space-y-4">
                @foreach ($tickets as $ticket)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-800">{{$ticket->subject}}</h3>
                                <p class="text-gray-600 text-sm mt-1">تیکت #{{$ticket->id}}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-clock ml-1"></i>
                                    {{ \Morilog\Jalali\Jalalian::forge($ticket->created_at)->format('Y/m/d H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if ($ticket->status == 'open')
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-circle ml-1 text-xs"></i>
                                        باز
                                    </span>
                                @elseif ($ticket->status == 'in_progress')
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-sync-alt ml-1 text-xs"></i>
                                        درحال بررسی
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check ml-1 text-xs"></i>
                                        بسته شد
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-question-circle ml-2 text-blue-600"></i>
                                    سوال شما:
                                </h4>
                                <p class="text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200">{{$ticket->message}}</p>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-reply ml-2 text-green-600"></i>
                                    پاسخ پشتیبانی:
                                </h4>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    @if ($ticket->response)
                                        <p class="text-gray-700">{{$ticket->response}}</p>
                                        @if($ticket->updated_at)
                                            <p class="text-xs text-gray-500 mt-2">
                                                <i class="fas fa-clock ml-1"></i>
                                                پاسخ داده شده در: {{ \Morilog\Jalali\Jalalian::forge($ticket->updated_at)->format('Y/m/d H:i') }}
                                            </p>
                                        @endif
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-clock text-gray-400 text-2xl mb-2"></i>
                                            <p class="text-gray-500">هنوز پاسخی ثبت نشده است</p>
                                            <p class="text-sm text-gray-400 mt-1">پشتیبان‌ها به زودی پاسخ خواهند داد</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-ticket-alt text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">تیکتی ثبت نشده است</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    شما تاکنون هیچ تیکت پشتیبانی ثبت نکرده‌اید. در صورت وجود هرگونه سوال یا مشکل می‌توانید تیکت جدید ایجاد کنید.
                </p>
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors" onclick="showNewTicketModal()">
                    <i class="fas fa-plus ml-2"></i>
                    ایجاد تیکت جدید
                </button>
            </div>
        @endif
    </div>
</div>