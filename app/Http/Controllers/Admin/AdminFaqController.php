<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    public function index() {
        $faqs = Faq::all();
        return view('admin.layouts.sections.faq',compact('faqs'));
    }
    public function create(Request $request) {
        $validated = $request->validate([
            'question' => [
                'required',
                'string',
                'max:500',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ],
            'answer' => [
                'required',
                'string',
                'max:2000',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛\n\r]+$/u'
            ]
        ], [
            'question.regex' => 'سوال فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'answer.regex' => 'پاسخ فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'question.required' => 'وارد کردن سوال الزامی است.',
            'answer.required' => 'وارد کردن پاسخ الزامی است.'
        ]);
        Faq::create($validated);
        return redirect()->route('admin.faq.index')->with('success', 'سوال متداول به همراه پاسخ با موفقیت ثبت شد');
    }
}
