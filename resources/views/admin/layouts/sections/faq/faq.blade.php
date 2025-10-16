@extends('admin.layouts.partials.master')

@section('content')
    <div id="faq" class="content-section">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">مدیریت سوالات متداول</h2>
                <button onclick="showModalFaq()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-semibold">
                    <i class="fas fa-plus ml-1"></i>افزودن سوال جدید
                </button>
            </div>
            
            <!-- FAQ Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <i class="fas fa-question-circle text-blue-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="totalFaqs">{{count($faqs)}}</h3>
                    <p class="text-gray-600">کل سوالات</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <i class="fas fa-eye text-green-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="publishedFaqs">{{ $published_count }}</h3>
                    <p class="text-gray-600">منتشر شده</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-6 text-center">
                    <i class="fas fa-ban text-red-600 text-3xl mb-3"></i>
                    <h3 class="text-2xl font-bold text-gray-800" id="publishedFaqs">{{ $draft_count }}</h3>
                    <p class="text-gray-600">منتشر نشده است</p>
                </div>
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
            <!-- FAQ Items -->
            <div id="faqAccordion" class="space-y-4">
                <div class="border border-gray-200 rounded-lg">
                    @foreach ($faqs as $faq)
                        <div class="flex items-center justify-between p-4 bg-gray-50">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $faq->question }}</h4>
                                    
                                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                        <p>پاسخ: {{$faq->answer}} </p>
                                    </div>
                                    <br>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.faq.edit',$faq->id) }}">
                                            <button class="text-green-600 hover:text-green-800" title="ویرایش">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>    
                                        @if ($faq->status == 'published')
                                            <a href="{{route('admin.faq.status', $faq->id)}}"> 
                                                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors font-semibold text-sm" title="عدم انتشار">
                                                    <i class="fas fa-eye-slash ml-1"></i>عدم انتشار
                                                </button>
                                            </a>
                                        @else
                                            <a href="{{route('admin.faq.status', $faq->id)}}">
                                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors font-semibold text-sm" title="انتشار">
                                                    <i class="fas fa-eye ml-1"></i>انتشار
                                                </button>
                                            </a>
                                        @endif
                                        <br>
                                        @if ($faq->status == 'published')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                منتشر شده
                                            </span>
                                            <br>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-red-600">
                                                منتشر نشده است
                                            </span>
                                        @endif
                                    </div>
                                </div>                        
                        </div>
                    @endforeach

                    <div id="faqAnswer2" class="p-4 border-t border-gray-200 hidden">
                        <p class="text-gray-700 leading-relaxed">بله، در صورت عدم رضایت از محصول، ظرف 7 روز از تاریخ خرید می‌توانید درخواست بازگشت وجه دهید. شرایط و قوانین بازگشت وجه در صفحه "شرایط و قوانین" موجود است.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.sections.faq.add-faq')
    
    @section('js')
        <script src="{{asset('js/admin/faq.js')}}"></script>
    @endsection

@endsection