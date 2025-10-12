<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index() {

        return view('admin.layouts.sections.setting');
    }
    public function set() {
        $details = WebSetting::all();
        return view('admin.web-setting.set',compact('details'));
    }
    public function update(Request $request) {
        $details = WebSetting::first();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', 
            'icon' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', 
        ]);

        $uploadPath = public_path('uploads');
        $logoFileName = 'logo.png';
        $iconFileName = 'icon.png';
        $logoPath = 'uploads/' . $logoFileName;
        $iconPath = 'uploads/' . $iconFileName;

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        if ($details) {
            if ($request->hasFile('logo')) {

                if ($details->logo && file_exists(public_path($details->logo))) {
                    unlink(public_path($details->logo));
                }
                $request->file('logo')->move($uploadPath, $logoFileName);
                $validated['logo'] = $logoPath;
            } 
            else {
                $validated['logo'] = $details->logo; 
            }

            if ($request->hasFile('icon')) {
                if ($details->icon && file_exists(public_path($details->icon))) {
                    unlink(public_path($details->icon));
                }
                $request->file('icon')->move($uploadPath, $iconFileName);
                $validated['icon'] = $iconPath;
            } else {
                $validated['icon'] = $details->icon; 
            }

            $details->update($validated);

            return redirect()->route('admin.web-setting.index')->with('success', 'Settings updated successfully');
        }
        else {

            if ($request->hasFile('logo')) {
                $request->file('logo')->move($uploadPath, $logoFileName);
                $validated['logo'] = $logoPath;
            }
            if ($request->hasFile('icon')) {
                $request->file('icon')->move($uploadPath, $iconFileName);
                $validated['icon'] = $iconPath;
            }

            WebSetting::create($validated);

            return redirect()->route('admin.web-setting.index')->with('success', 'Settings created successfully');
        }
    }
}
