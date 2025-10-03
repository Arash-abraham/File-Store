@extends('admin.layouts.partials.master')

@section('content')
    <div class="p-8">
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
        <div class="bg-white rounded-xl p-8 max-w-4xl w-full mx-auto">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-black mb-2">مدیریت پرسش و پاسخ</h1>
                <p class="text-gray-600 dark:text-gray-400">ویرایش سوال و پاسخ در بخش سوالات متداول</p>
            </div>
            <form action="{{ route('admin.faq.update', $faq->id) }}" method="POST" class="space-y-6">
                @method('PUT')
                @csrf
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">سوال</label>
                    <input type="text" name="question" required 
                        value="{{ old('question', $faq->question) }}"
                        class="w-full px-4 py-4 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="متن سوال را وارد کنید...">
                </div>
                
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">پاسخ</label>
                    <textarea name="answer" rows="3" 
                        class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical"
                        placeholder="پاسخ سوال را وارد کنید...">{{ old('answer', $faq->answer) }}</textarea>
                </div>
            
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold text-base">
                        ثبت تغییرات
                    </button>
                    <a href="{{ route('admin.faq.index') }}" class="flex-1 border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors text-center font-semibold text-base">
                        لغو
                    </a>
                </div>
            </form>


        </div>
    </div>


@endsection