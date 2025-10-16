<div id="downloads" class="content-section hidden">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">لیست دانلودها</h2>
        
        @if($downloadableFiles->count() > 0)
            <div class="space-y-4">
                @foreach($downloadableFiles as $file)
                    <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center ml-4 
                                    @if($file->type === 'pdf') bg-red-100 text-red-600
                                    @elseif($file->type === 'zip') bg-blue-100 text-blue-600
                                    @elseif($file->type === 'rar') bg-purple-100 text-purple-600
                                    @else bg-gray-100 text-gray-600 @endif">
                                    @if($file->type === 'pdf')
                                        <i class="fas fa-file-pdf text-xl"></i>
                                    @elseif($file->type === 'zip' || $file->type === 'rar')
                                        <i class="fas fa-file-archive text-xl"></i>
                                    @else
                                        <i class="fas fa-file text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $file->name }}</h3>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-sm text-gray-500">
                                            @if($file->size_label)
                                                {{ $file->size_label }}
                                            @else
                                                {{ $file->formatted_size }}
                                            @endif
                                        </span>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($file->type === 'pdf') bg-red-100 text-red-800
                                            @elseif($file->type === 'zip') bg-blue-100 text-blue-800
                                            @elseif($file->type === 'rar') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $file->type_label }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            محصول: {{ $file->product->title ?? 'نامشخص' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('product-files.download', $file->id) }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                                    <i class="fas fa-download"></i>
                                    دانلود
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-download text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">فایلی برای دانلود وجود ندارد</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    شما هنوز هیچ محصولی خریداری نکرده‌اید یا محصولات خریداری شده فایل دانلودی ندارند.
                </p>
                <a href="{{ route('products') }}" 
                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    مشاهده محصولات
                </a>
            </div>
        @endif
    </div>
</div>