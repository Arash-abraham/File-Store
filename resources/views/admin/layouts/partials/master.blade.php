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
    
    <!-- Add Menu Modal -->
        {{-- TODO --}}

    <!-- Edit Menu Modal -->    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/jalalidatepicker/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/jalalidatepicker/persian-datepicker.min.js') }}"></script>
    <script src="{{asset('js/date.js')}}"></script>
    @yield('js')
    
</body>
</html>