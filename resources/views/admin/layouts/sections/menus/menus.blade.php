@extends('admin.layouts.partials.master')

@section('content')
    <div id="menus" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت منوی سایت</h2>
                <a href="{{ route('admin.menu.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus ml-2"></i>منو جدید
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="card border-success mb-4 shadow-lg" id="successAlert">
                    <div class="card-header bg-gradient bg-success text-white py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <span class="fw-bold fs-6">عملیات موفق</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white" onclick="closeSuccessAlert()" aria-label="Close"></button>
                    </div>
                    <div class="card-body bg-light py-3">
                        <ul class="mb-0 text-success fs-7">
                            <li class="mb-1">
                                <i class="fas fa-check me-2 small"></i>
                                {{ session('success') }}
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <i class="fas fa-bars text-blue-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ count($menus) }}</h3>
                    <p class="text-gray-600">کل منوها</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $active_count }}</h3>
                    <p class="text-gray-600">فعال</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <i class="fas fa-times-circle text-gray-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $inactive_count }}</h3>
                    <p class="text-gray-600">غیرفعال</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">ترتیب</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">عنوان</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">آدرس URL</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">آیکون</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">نمایش در</th>
                            <th class="p-3 text-right text-gray-600 font-semibold border-b">وضعیت</th>
                            <th class="p-3 text-center text-gray-600 font-semibold border-b">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border-b">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-bold">
                                    {{ $menu->sort_order }}
                                </span>
                            </td>
                            <td class="p-3 border-b">
                                <div class="flex items-center gap-2">
                                    @if($menu->icon)
                                        <i class="{{ $menu->icon }} text-blue-600"></i>
                                    @endif
                                    <span class="font-semibold">{{ $menu->title }}</span>
                                </div>
                            </td>
                            <td class="p-3 border-b">
                                <a href="{{ $menu->url }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ Str::limit($menu->url, 40) }}
                                </a>
                            </td>
                            <td class="p-3 border-b">
                                <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $menu->icon ?? '-' }}</code>
                            </td>
                            <td class="p-3 border-b">
                                @if($menu->target === '_blank')
                                    <span class="text-purple-600">
                                        <i class="fas fa-external-link-alt ml-1"></i>صفحه جدید
                                    </span>
                                @else
                                    <span class="text-gray-600">
                                        <i class="fas fa-window-maximize ml-1"></i>همان صفحه
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                @if($menu->status === 'active')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        <i class="fas fa-check ml-1"></i>فعال
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">
                                        <i class="fas fa-times ml-1"></i>غیرفعال
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.menu.edit', $menu->id) }}" 
                                       class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                       title="ویرایش">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.menu.toggle-status', $menu->id) }}" 
                                       class="p-2 {{ $menu->status === 'active' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600' }} rounded-lg hover:opacity-80 transition"
                                       title="{{ $menu->status === 'active' ? 'غیرفعال کردن' : 'فعال کردن' }}">
                                        <i class="fas fa-power-off"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                                title="حذف"
                                                onclick="return confirm('آیا از حذف این منو اطمینان دارید؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p>هیچ منویی یافت نشد</p>
                                <a href="{{ route('admin.menu.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                    ایجاد اولین منو
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function closeSuccessAlert() {
            document.getElementById('successAlert').style.display = 'none';
        }

        // Auto hide success message after 5 seconds
        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);
    </script>
@endsection

