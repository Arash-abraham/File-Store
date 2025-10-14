<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    @include('layouts.head-tag')
</head>

<body class="font-sans bg-gray-50">
    @include('layouts.partials.header')
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('layouts.partials.sidebar')

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Dashboard Section -->
                @include('layouts.main.dashboard-section')

                <!-- Orders Section -->
                @include('layouts.main.orders-section')

                <!-- Downloads Section -->
                @include('layouts.main.downloads-section')


                <!-- Tickets Section -->
                @include('layouts.main.tickets-section')


                <!-- Wallet Section -->
                @include('layouts.main.wallet-section')


                <!-- Profile Section -->
                @include('layouts.main.profile-section')


                <!-- Password Section -->
                @include('layouts.main.password-section')

            </div>
        </div>
    </div>

    <!-- New Ticket Modal -->
    @include('layouts.main.new-ticket-modal')

    <script src="{{asset('js/dashboard.js')}}"></script>
</body>
</html>