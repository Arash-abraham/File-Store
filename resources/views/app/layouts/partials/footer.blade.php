<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                @foreach ($setting as $item)
                    <h3 class="text-xl font-bold mb-4">{{ $item->site_title }}</h3>
                    <p class="text-gray-300 mb-4">{{ $item->site_description }}</p>
                @endforeach
                <div class="flex gap-4">
                    <a href="#" class="text-gray-300 hover:text-white transition-colors"><i class="fab fa-telegram"></i></a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">دسترسی سریع</h4>
                <ul class="space-y-2">
                    @foreach ($setting as $item)
                        <li><a href="{{route('about')}}" class="text-gray-300 hover:text-white transition-colors">درباره ما</a></li>
                        <li><a href="tel:{{$item->phone}}" class="text-gray-300 hover:text-white transition-colors">تماس با ما</a></li>
                        <li><a href="{{route('faq')}}" class="text-gray-300 hover:text-white transition-colors">سوالات متداول</a></li>
                    @endforeach

                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">دسته‌بندی‌ها</h4>
                <ul class="space-y-2">
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('productsWithCategory', ['category' => $category->id]) }}" class="text-gray-300 hover:text-white transition-colors">
                            {{$category->name}}
                            </a>
                        </li>
                    @endforeach

                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">اطلاعات تماس</h4>
                @foreach ($setting as $item)
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-map-marker-alt ml-2"></i>{{$item->address}}</li>
                        <li><i class="fas fa-phone ml-2"></i>{{$item->phone}}</li>
                        <li><i class="fas fa-envelope ml-2"></i>{{$item->email}}</li>
                    </ul>
                @endforeach

            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025  فایل استور. تمامی حقوق محفوظ است.</p>
        </div>
    </div>
</footer>