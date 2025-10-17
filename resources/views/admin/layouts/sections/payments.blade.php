@extends('admin.layouts.partials.master')

@section('content')
    <div id="payments" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Payments Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">شناسه پرداخت</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کاربر</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مبلغ</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">درگاه</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ</th>
                        </tr>
                    </thead>
                    <tbody id="paymentsTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach ($peyments as $peyment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$peyment->id}}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$peyment->user->name}}</td>
                                @if ($peyment->order->final_amount)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{number_format($peyment->order->final_amount)}} تومان</td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{number_format($peyment->order->total_amount)}} تومان</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">زرین پال</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        موفق
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">           
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($peyment->created_at)->tz('Asia/Tehran'))->format('Y-m-d H:i:s') }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection