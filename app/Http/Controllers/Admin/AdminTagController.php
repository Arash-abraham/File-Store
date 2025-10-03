<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.layouts.sections.tag.tags',compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.layouts.sections.tag.add-tag');
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
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u',
                Rule::unique('tags')->whereNull('deleted_at')

            ],
            'slug' => [
                'required',
                'string',
                'max:2000',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*(?:\/[a-z0-9]+(?:-[a-z0-9]+)*)*$/',
                Rule::unique('tags')->whereNull('deleted_at')
            ] ,
        ], [
            'name.regex' => 'نام برچسب فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'slug.regex' => 'اسلاگ فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
        ]);

        $nameExists = Tag::where('name', $validated['name'])->exists();
        $slugExists = Tag::where('slug', $validated['slug'])->exists();

        if($nameExists && $slugExists) {
            return redirect()->back()->withErrors(['tag' => 'هم نام و هم اسلاگ از قبل ثبت شده‌اند.'])->withInput();
        } 
        elseif($nameExists) {
            return redirect()->back()->withErrors(['name' => 'این نام از قبل ثبت شده است.'])->withInput();
        } 
        elseif($slugExists) {
            return redirect()->back()->withErrors(['slug' => 'این اسلاگ از قبل ثبت شده است.'])->withInput();
        }
        else {
            
            Tag::create($validated);
            return redirect()->route('admin.tag.index')->with('success', 'برچسب با موفقیت ثبت شد');
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
        $tag = Tag::findOrFail($id);
        return view('admin.layouts.sections.tag.edit-tag',compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
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
            ] 
        ], [
            'name.regex' => 'نام برچسب فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'slug.regex' => 'اسلاگ فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
        ]);
        $duplicateExists = Tag::where('id', '!=', $tag->id)->where(
            function($query) use ($validated) {
            $query->where('name', $validated['name'])->orWhere('slug', $validated['slug']);
        })->exists();
        
        $isSameAsCurrent = ($validated['name'] === $tag->name && $validated['slug'] === $tag->slug);
        
        if (!$duplicateExists || $isSameAsCurrent) {
            $tag->update($validated);
            return redirect()->route("admin.tag.index")->with('success', 'تغییرات مورد نظر با موفقیت ثبت شد');
        }
        else {
            return redirect()->back()->withErrors(['tag' => ' برچسب یا اسلاگ از قبل ثبت شده است. یک نام جدید یا اسلاگ جدید استفاده کنید.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tag.index')->with('success', 'با موفقیقت حذف شد');
    }
}
