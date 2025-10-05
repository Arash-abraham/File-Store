@extends('admin.layouts.partials.master')

@section('content')
    <div id="tickets" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت تیکت‌های پشتیبانی</h2>
            </div>
            
            <!-- Ticket Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-red-50 rounded-lg p-6 text-center">
                    <i class="fas fa-ticket-alt text-red-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="totalTickets">{{count($tickets)}}</h3>
                    <p class="text-gray-600">کل تیکت‌ها</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-6 text-center">
                    <i class="fas fa-clock text-yellow-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="openTickets">{{$open_count}}</h3>
                    <p class="text-gray-600">باز</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <i class="fas fa-sync-alt text-blue-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="urgentTickets">{{$progress_count}}</h3>
                    <p class="text-gray-600">در حال بررسی</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="closedTickets">{{$close_count}}</h3>
                    <p class="text-gray-600">بسته شده</p>
                </div>
            </div>
            
            <!-- Tickets Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">شناسه</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کاربر</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">موضوع</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">دسته‌بندی</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ ایجاد</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                        </tr>
                    </thead>
                    <tbody id="ticketsTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach ($tickets as $ticket)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-bold">{{$ticket->id}}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{$ticket->user->name}}</div>
                                        <div class="text-sm text-gray-500">{{$ticket->user->email}}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{$ticket->subject}}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{$ticket->assigned_to}}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($ticket->created_at)->tz('Asia/Tehran'))->format('Y-m-d H:i:s') }}
                                </td>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.ticket.show',$ticket->id) }}">
                                            <button class="text-blue-600 hover:text-blue-800" title="مشاهده">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @section('js')
        <script src="{{asset('js/admin/category.js')}}"></script>
    @endsection
@endsection