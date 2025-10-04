@extends('admin.layouts.partials.master')

@section('content')
    <div id="tickets" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت تیکت‌های پشتیبانی</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-8">
                <div class="bg-blue-50 rounded-lg p-6 text-center shadow-md">
                    <i class="fas fa-ticket-alt text-blue-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="totalTickets">{{count($tickets)}}</h3>
                    <p class="text-gray-600">کل تیکت‌ها</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-6 text-center shadow-md">
                    <i class="fas fa-clock text-yellow-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="openTickets">0</h3>
                    <p class="text-gray-600">باز</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center shadow-md">
                    <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="closedTickets">0</h3>
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
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اولویت</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ ایجاد</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                        </tr>
                    </thead>
                    <tbody id="ticketsTableBody" class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-bold">TKT-1001</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">احمد محمدی</div>
                                    <div class="text-sm text-gray-500">ahmad@example.com</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">مشکل در دانلود فایل</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">فنی</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    بالا
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    باز
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1403/07/16</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <button class="text-blue-600 hover:text-blue-800" onclick="viewTicket(1)" title="مشاهده">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800" onclick="replyToTicket(1)" title="پاسخ">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </div>
                            </td>
                            </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-bold">TKT-1002</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">مریم حسینی</div>
                                    <div class="text-sm text-gray-500">maryam@example.com</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">درخواست بازگشت وجه</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">مالی</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    متوسط
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    در حال بررسی
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1403/07/15</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <button class="text-blue-600 hover:text-blue-800" onclick="viewTicket(2)" title="مشاهده">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800" onclick="replyToTicket(2)" title="پاسخ">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-bold">TKT-1003</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">رضا کریمی</div>
                                    <div class="text-sm text-gray-500">reza@example.com</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">سوال درباره قیمت‌گذاری</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">عمومی</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    پایین
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    بسته شده
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1403/07/14</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <button class="text-blue-600 hover:text-blue-800" onclick="viewTicket(3)" title="مشاهده">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800" onclick="replyToTicket(3)" title="پاسخ">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @section('js')
        <script src="{{asset('js/admin/category.js')}}"></script>
    @endsection
@endsection