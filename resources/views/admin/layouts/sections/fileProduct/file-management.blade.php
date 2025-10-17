@extends('admin.layouts.partials.master')

@section('content')

    <div id="product-files" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت فایل‌های محصولات</h2>
                <a href="{{route('admin.file-product.create')}}">
                    <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transroition-colors">
                        <i class="fas fa-plus ml-2"></i>افزودن فایل
                    </button>
                </a>
            </div>
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
            <!-- Files List -->
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-6">
                    @foreach ($files as $file)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                                    <i class="fas fa-file-archive text-blue-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">{{$file->name}}</h3>
                                    <p class="text-sm text-gray-500">محصول: {{$file->product->title}}</p>
                                    <p class="text-sm text-gray-500">حجم: {{$file->size_label}}</p>
                                    <p class="text-sm text-gray-500">نوع: {{$file->type}}</p>

                                    <p class="text-sm text-gray-500">آدرس: {{$file->path}}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 items-center">
                                <a href="{{ route('product-files.download', $file->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 px-4 py-2 border border-blue-600 rounded-lg flex items-center">
                                    <i class="fas fa-download ml-1"></i>
                                    دانلود
                                </a>
                                <button class="text-red-600 hover:text-red-800 p-2 rounded-lg border border-red-600" 
                                        onclick="confirmDelete({{ $file->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <form id="delete-form-{{ $file->id }}" action="{{ route('admin.file-product.destroy', $file->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @section('js')
        <script src="{{asset('js/admin/faq.js')}}"></script>
        <script>
            function confirmDelete(fileId) {
                document.getElementById('delete-form-' + fileId).submit();
            }
        </script>
    @endsection
@endsection