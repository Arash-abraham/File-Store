<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.layouts.sections.category.category',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.layouts.sections.category.add-category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:500',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ],
            'slug' => [
                'required',
                'string',
                'max:2000',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*(?:\/[a-z0-9]+(?:-[a-z0-9]+)*)*$/'
            ] ,
            'icon' => [
                'required'
            ],
            'color' => [
                'required'
            ]
        ], [
            'name.regex' => 'سوال فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'slug.regex' => 'پاسخ فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
        ]);
        // dd($validated);
        Category::create($validated);
        return redirect()->route('admin.category.index')->with('success', 'سوال متداول به همراه پاسخ با موفقیت ثبت شد');
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
