@extends('admin.index')

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
            @if($errors->any())
                <div class="card border-danger mb-4" id="errorAlert">
                    <div class="card-header bg-danger text-white py-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <span class="fw-bold">خطا در ارسال فرم</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white" onclick="closeErrorAlert()" aria-label="Close"></button>
                    </div>
                    <div class="card-body text-danger py-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
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

    <div id="addFaqModal" class="modal-overlay">
        <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">افزودن پاسخ برای سوالات متداول</h3>
                <button onclick="hideFaqModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.faq.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">سوال</label>
                    <input type="text" name="question" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">پاسخ</label>
                        <textarea name="answer" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        ثبت
                    </button>
                    <button type="button" onclick="hideFaqModal()" class="flex-1 border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors">
                        لغو
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection