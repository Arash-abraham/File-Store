<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    @include('admin.layouts.head-tag')
</head>
<body class="font-sans bg-gray-50">
    <!-- Header -->
    @include('admin.layouts.partials.header')

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            @include('admin.layouts.partials.sidebar')
            <!-- Main Content -->
            <div class="lg:w-3/4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    {{-- <x-add-product-modal></x-add-product-modal> --}}

    <!-- Add File Modal -->
    {{-- <x-add-file-modal></x-add-file-modal> --}}

    <!-- Add Category Modal -->
    {{-- <x-category.add-category></x-category.add-category> --}}

    <!-- Edit Category Modal -->
    {{-- <x-category.edit-category></x-category.edit-category> --}}

    <!-- Add Tag Modal -->
    {{-- <x-tag.add-tag-modal></x-tag.add-tag-modal> --}}

    <!-- Edit Tag Modal -->
    {{-- <x-tag.edit-tag-modal></x-tag.edit-tag-modal> --}}

    <!-- Add Coupon Modal -->
    {{-- <x-copan.add-copan-modal></x-copan.add-copan-modal> --}}

    <!-- Edit Coupon Modal -->
    {{-- <x-copan.edit-copan-modal></x-edit.edit-copan-modal> --}}


    <!-- Persian Date Picker Modal -->
    {{-- <x-copan.date-modal></x-edit.date-modal>
     --}}
    
    <!-- Add Menu Modal -->
        {{-- TODO --}}

    <!-- Edit Menu Modal -->    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="{{asset('js/admin.js')}}"></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/jalalidatepicker/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/jalalidatepicker/persian-datepicker.min.js') }}"></script>
    <script src="{{asset('js/date.js')}}"></script>
    <script src="{{asset('js/admin/faq.js')}}"></script>
    
</body>
</html>