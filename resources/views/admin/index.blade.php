@extends('admin.layouts.partials.master')

@section('content')
    <div id="dashboard" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">داشبورد مدیریت</h2>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <i class="fas fa-box text-blue-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">127</h3>
                    <p class="text-gray-600">کل محصولات</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <i class="fas fa-shopping-bag text-green-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">1,543</h3>
                    <p class="text-gray-600">کل فروش‌ها</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-6 text-center">
                    <i class="fas fa-users text-purple-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">12,847</h3>
                    <p class="text-gray-600">کاربران</p>
                </div>
                <div class="bg-orange-50 rounded-lg p-6 text-center">
                    <i class="fas fa-dollar-sign text-orange-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">۴۵,۶۷۸,۹۰۰</h3>
                    <p class="text-gray-600">درآمد (تومان)</p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white border rounded-xl p-6">
                    <h3 class="text-lg font-bold mb-4">نمودار فروش ماهانه</h3>
                    <canvas id="monthlySalesChart"></canvas>
                </div>
                <div class="bg-white border rounded-xl p-6">
                    <h3 class="text-lg font-bold mb-4">محصولات پرفروش</h3>
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="border-t pt-6 mt-8">
                <h3 class="text-lg font-bold mb-4">آخرین فعالیت‌ها</h3>
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-plus text-green-600 text-lg ml-4"></i>
                        <div class="flex-1">
                            <p class="font-semibold">محصول جدید اضافه شد: Adobe Illustrator 2024</p>
                            <p class="text-sm text-gray-500">5 دقیقه پیش</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-shopping-cart text-blue-600 text-lg ml-4"></i>
                        <div class="flex-1">
                            <p class="font-semibold">سفارش جدید: #ORD-1547 - 2,500,000 تومان</p>
                            <p class="text-sm text-gray-500">12 دقیقه پیش</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-ticket-alt text-orange-600 text-lg ml-4"></i>
                        <div class="flex-1">
                            <p class="font-semibold">تیکت جدید: مشکل در دانلود فایل</p>
                            <p class="text-sm text-gray-500">25 دقیقه پیش</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection