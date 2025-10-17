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
                    
                @if(session('success'))
                    <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
                        <button type="button" 
                                class="close-btn absolute left-3 top-3 w-6 h-6 flex items-center justify-center text-green-600 hover:text-green-800 hover:bg-green-200 rounded-full transition-colors"
                                onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                        <div class="flex items-center pr-8">
                            <i class="fas fa-check-circle ml-2"></i>
                            {{ session('success') }}
                            @if(session('ref_id'))
                                <span class="mr-4">کد پیگیری: <strong>{{ session('ref_id') }}</strong></span>
                            @endif
                        </div>
                    </div>
                @endif
    
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg relative">
                        <span class="close-btn" onclick="this.parentElement.style.display='none'">&times;</span>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
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