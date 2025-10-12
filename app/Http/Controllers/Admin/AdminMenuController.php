<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    /**
     * Display a listing of menus.
     */
    public function index()
    {
        $menus = Menu::orderBy('sort_order')->get();
        $active_count = Menu::active()->count();
        $inactive_count = Menu::inactive()->count();

        return view('admin.layouts.sections.menus.menus', compact('menus', 'active_count', 'inactive_count'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        return view('admin.layouts.sections.menus.add-menu');
    }

    /**
     * Store a newly created menu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:150',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ],
            'url' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'required|integer|min:1',
            'target' => 'required|in:_self,_blank',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:500',
        ], [
            'title.required' => 'عنوان منو الزامی است',
            'title.regex' => 'عنوان فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد',
            'url.required' => 'آدرس URL الزامی است',
            'sort_order.required' => 'ترتیب نمایش الزامی است',
            'sort_order.min' => 'ترتیب نمایش باید حداقل 1 باشد',
        ]);

        Menu::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'منو با موفقیت ایجاد شد');
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.layouts.sections.menus.edit-menu', compact('menu'));
    }

    /**
     * Update the specified menu.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:150',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ],
            'url' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'required|integer|min:1',
            'target' => 'required|in:_self,_blank',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:500',
        ], [
            'title.required' => 'عنوان منو الزامی است',
            'title.regex' => 'عنوان فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد',
            'url.required' => 'آدرس URL الزامی است',
            'sort_order.required' => 'ترتیب نمایش الزامی است',
            'sort_order.min' => 'ترتیب نمایش باید حداقل 1 باشد',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'منو با موفقیت بروزرسانی شد');
    }

    /**
     * Remove the specified menu.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        
        return redirect()->route('admin.menu.index')
            ->with('success', 'منو با موفقیت حذف شد');
    }

    /**
     * Toggle menu status.
     */
    public function toggleStatus($id)
    {
        $menu = Menu::findOrFail($id);
        
        $menu->status = $menu->status === 'active' ? 'inactive' : 'active';
        $menu->save();

        return back()->with('success', 'وضعیت منو تغییر یافت');
    }
}

