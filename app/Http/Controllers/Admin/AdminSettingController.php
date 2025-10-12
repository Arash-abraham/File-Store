<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = WebSetting::first();
        
        if (!$settings) {
            $settings = new WebSetting();
        }
        
        return view('admin.layouts.sections.setting', compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = WebSetting::first();
        
        if (!$settings) {
            $settings = new WebSetting();
        }

        // Handle logo upload - ذخیره در public
        if ($request->hasFile('logo')) {
            // ایجاد پوشه اگر وجود نداشت
            $logoDirectory = public_path('uploads/settings/logo');
            if (!File::exists($logoDirectory)) {
                File::makeDirectory($logoDirectory, 0755, true);
            }

            // حذف لوگوی قبلی اگر وجود داشت
            if ($settings->logo_path && File::exists(public_path($settings->logo_path))) {
                File::delete(public_path($settings->logo_path));
            }
            
            // آپلود فایل جدید
            $logoFile = $request->file('logo');
            $logoFileName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move($logoDirectory, $logoFileName);
            
            $settings->logo_path = 'uploads/settings/logo/' . $logoFileName;
        }

        // Handle icon upload - ذخیره در public
        if ($request->hasFile('icon')) {
            // ایجاد پوشه اگر وجود نداشت
            $iconDirectory = public_path('uploads/settings/icon');
            if (!File::exists($iconDirectory)) {
                File::makeDirectory($iconDirectory, 0755, true);
            }

            // حذف آیکون قبلی اگر وجود داشت
            if ($settings->icon_path && File::exists(public_path($settings->icon_path))) {
                File::delete(public_path($settings->icon_path));
            }
            
            // آپلود فایل جدید
            $iconFile = $request->file('icon');
            $iconFileName = 'icon_' . time() . '.' . $iconFile->getClientOriginalExtension();
            $iconFile->move($iconDirectory, $iconFileName);
            
            $settings->icon_path = 'uploads/settings/icon/' . $iconFileName;
        }

        // Update other settings
        $settings->site_title = $request->site_title;
        $settings->site_description = $request->site_description;
        $settings->phone = $request->phone;
        $settings->email = $request->email;
        $settings->address = $request->address;
        
        $settings->save();

        return redirect()->back()->with('success', 'تنظیمات با موفقیت به روز شد.');
    }

    // متد جدید برای حذف تصاویر
    public function removeImage(Request $request)
    {
        $request->validate([
            'image_type' => 'required|in:logo,icon'
        ]);

        $settings = WebSetting::first();
        
        if ($settings) {
            $imageType = $request->image_type;
            $pathField = $imageType . '_path';
            
            if ($settings->$pathField && File::exists(public_path($settings->$pathField))) {
                File::delete(public_path($settings->$pathField));
                $settings->$pathField = null;
                $settings->save();
            }
        }

        return response()->json(['success' => true]);
    }
}