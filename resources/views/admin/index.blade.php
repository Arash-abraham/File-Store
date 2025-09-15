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
                <!-- Dashboard Section -->
                @include('admin.layouts.sections.dashborad')

                <!-- Products Management Section -->
                @include('admin.layouts.sections.products')

                <!-- Product Files Management Section -->
                @include('admin.layouts.sections.file-management')

                <!-- Categories Management Section -->
                @include('admin.layouts.sections.category')

                <!-- Tags Management Section -->
                @include('admin.layouts.sections.tags')

                <!-- Coupons Management Section -->
                @include('admin.layouts.sections.coupons')

                <!-- Comments Management Section -->
                @include('admin.layouts.sections.comments')

                <!-- Site Menus Section -->
                {{-- TODO --}}
                
                <!-- Payments Section -->
                @include('admin.layouts.sections.payments')

                <!-- Support Tickets Section -->
                @include('admin.layouts.sections.tickets')


                <!-- FAQ Section -->
                @include('admin.layouts.sections.faq')


                <!-- Site Settings Section -->
                @include('admin.layouts.sections.setting')


                <!-- Other sections will be added in the next part due to length -->
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <x-add-product-modal></x-add-product-modal>

    <!-- Add File Modal -->
    <x-add-file-modal></x-add-file-modal>

    <!-- Add Category Modal -->
    <x-category.add-category></x-category.add-category>

    <!-- Edit Category Modal -->
    <x-category.edit-category></x-category.edit-category>

    <!-- Add Tag Modal -->
    <x-tag.add-tag-modal></x-tag.add-tag-modal>

    <!-- Edit Tag Modal -->
    <x-tag.edit-tag-modal></x-tag.edit-tag-modal>

    <!-- Add Coupon Modal -->
    <x-copan.add-copan-modal></x-copan.add-copan-modal>

    <!-- Edit Coupon Modal -->
    <x-copan.edit-copan-modal></x-edit.add-copan-modal>


    <!-- Persian Date Picker Modal -->
    <x-copan.date-modal></x-edit.date-modal>
    
    
    <!-- Add Menu Modal -->
        {{-- TODO --}}

    <!-- Edit Menu Modal -->
        {{-- TODO --}}
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="{{asset('js/admin.js')}}"></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/jalalidatepicker/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/jalalidatepicker/persian-datepicker.min.js') }}"></script>
    <script src="{{asset('js/date.js')}}"></script>
    
</body>
</html>