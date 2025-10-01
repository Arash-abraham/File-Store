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
            'name.regex' => 'نام دسته بندی فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'slug.regex' => 'اسلاگ فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
        ]);

        $nameExists = Category::where('name', $validated['name'])->exists();
        $slugExists = Category::where('slug', $validated['slug'])->exists();

        if($nameExists && $slugExists) {
            return redirect()->back()->withErrors(['category' => 'هم نام و هم اسلاگ از قبل ثبت شده‌اند.'])->withInput();
        } elseif($nameExists) {
            return redirect()->back()->withErrors(['name' => 'این نام از قبل ثبت شده است.'])->withInput();
        } elseif($slugExists) {
            return redirect()->back()->withErrors(['slug' => 'این اسلاگ از قبل ثبت شده است.'])->withInput();
        } else {
            Category::create($validated);
            return redirect()->route('admin.category.index')->with('success', 'دسته بندی با موفقیت ثبت شد');
        }
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
        $category = Category::findOrFail($id);
        return view('admin.layouts.sections.category.edit-category',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
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
            'name.regex' => 'نام دسته بندی فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'slug.regex' => 'اسلاگ فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
        ]);
        $duplicateExists = Category::where('id', '!=', $category->id)->where(
            function($query) use ($validated) {
            $query->where('name', $validated['name'])->orWhere('slug', $validated['slug']);
        })->exists();
        
        $isSameAsCurrent = ($validated['name'] === $category->name && $validated['slug'] === $category->slug);
        
        if (!$duplicateExists || $isSameAsCurrent) {
            $category->update($validated);
            return redirect()->route("admin.category.index")->with('success', 'تغییرات مورد نظر با موفقیت ثبت شد');
        }
        else {
            return redirect()->back()->withErrors(['category' => 'دسته بندی یا اسلاگ از قبل ثبت شده است. یک نام جدید یا اسلاگ جدید استفاده کنید.'])->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'با موفقیقت حذف شد');
    }
}
