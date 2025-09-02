<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    @include('app.layouts.head-tag')
</head>
<body class="font-sans bg-gray-50">
    @include('app.layouts.partials.header')
    
    <!-- Main Content -->
    <div id="main-content">
        @yield('content')
    </div>

    @include('app.layouts.partials.footer')
    
    @yield('scripts')
</body>
</html>