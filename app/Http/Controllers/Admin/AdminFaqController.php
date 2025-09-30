<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class AdminFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $published_count = 0;
        $draft_count = 0;

        $faqs = Faq::all();
        foreach($faqs as $faq) {
            if($faq->status == 'published') {
                $published_count += 1;
            }
            if($faq->status != 'published') {
                $draft_count += 1;
            }
        }

        
        return view('admin.layouts.sections.faq',compact('faqs','published_count','draft_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
