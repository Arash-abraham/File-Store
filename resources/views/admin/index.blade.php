@extends('admin.layouts.partials.master')

@section('content')
@php
    $totalSales = \App\Models\Payment::where('status', 'completed')->count();
    $totalRevenue = \App\Models\Payment::where('status', 'completed')->sum('amount');
    
    $salesData = [];
    $revenueData = [];
    $months = [];
    
    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $monthSales = \App\Models\Payment::where('status', 'completed')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
            
        $monthRevenue = \App\Models\Payment::where('status', 'completed')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->sum('amount');
            
        $salesData[] = $monthSales;
        $revenueData[] = $monthRevenue;
        $months[] = verta($date)->format('F');
    }
@endphp

<div id="dashboard" class="content-section">
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">داشبورد مدیریت</h2>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-50 rounded-lg p-6 text-center">
                <i class="fas fa-box text-blue-600 text-3xl mb-3"></i>
                <h3 class="text-2xl font-bold text-gray-800">{{ count($product) }}</h3>
                <p class="text-gray-600">کل محصولات</p>
            </div>
            <div class="bg-green-50 rounded-lg p-6 text-center">
                <i class="fas fa-shopping-bag text-green-600 text-3xl mb-3"></i>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalSales) }}</h3>
                <p class="text-gray-600">کل فروش‌ها</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-6 text-center">
                <i class="fas fa-users text-purple-600 text-3xl mb-3"></i>
                <h3 class="text-2xl font-bold text-gray-800">{{ count($user) }}</h3>
                <p class="text-gray-600">کاربران</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-6 text-center">
                <i class="fas fa-dollar-sign text-orange-600 text-3xl mb-3"></i>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalRevenue) }}</h3>
                <p class="text-gray-600">درآمد (تومان)</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white border rounded-xl p-6">
                <h3 class="text-lg font-bold mb-4">نمودار فروش ماهانه</h3>
                <canvas id="salesChart" height="250"></canvas>
            </div>
            <div class="bg-white border rounded-xl p-6">
                <h3 class="text-lg font-bold mb-4">نمودار درآمد ماهانه</h3>
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="border-t pt-6 mt-8">
            <h3 class="text-lg font-bold mb-4">آخرین فعالیت‌ها</h3>
            <div class="space-y-4">
                @php
                    $lastProduct = $product->last();
                    $lastTicket = $ticket->last();
                    $lastPayment = \App\Models\Payment::with('user')
                        ->where('status', 'completed')
                        ->latest()
                        ->first();
                @endphp

                @if($lastProduct)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-plus text-green-600 text-lg ml-4"></i>
                    <div class="flex-1">
                        <p class="font-semibold">محصول جدید اضافه شد: {{ $lastProduct->title }}</p>
                        <p class="text-sm text-gray-500">{{ $lastProduct->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endif

                @if($lastPayment)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-shopping-cart text-blue-600 text-lg ml-4"></i>
                    <div class="flex-1">
                        <p class="font-semibold">فروش جدید: {{ number_format($lastPayment->amount) }} تومان - کاربر: {{ $lastPayment->user->name ?? 'ناشناس' }}</p>
                        <p class="text-sm text-gray-500">{{ $lastPayment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endif

                @if($lastTicket)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-ticket-alt text-orange-600 text-lg ml-4"></i>
                    <div class="flex-1">
                        <p class="font-semibold">تیکت جدید: {{ $lastTicket->subject }}</p>
                        <p class="text-sm text-gray-500">{{ $lastTicket->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const months = @json($months);
        const salesData = @json($salesData);
        const revenueData = @json($revenueData);

        console.log('Chart Data:', { months, salesData, revenueData });

        // نمودار فروش
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            const salesChart = new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'تعداد فروش',
                        data: salesData,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'آمار فروش ۱۲ ماه اخیر'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'تعداد فروش'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'ماه'
                            }
                        }
                    }
                }
            });
        }

        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'درآمد (تومان)',
                        data: revenueData,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'آمار درآمد ۱۲ ماه اخیر'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'مبلغ (تومان)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' تومان';
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'ماه'
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
@endsection