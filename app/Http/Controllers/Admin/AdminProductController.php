<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.layouts.sections.product.products',compact('products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.layouts.sections.product.add-product',compact('categories','tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ],
            'category' => 'required|exists:categories,id',
            'originalPrice' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,draft',
            'availability' => 'required|in:true,false',
            'description' => [
                'required',
                'string',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ],
            'tag' => 'required|exists:tags,id',
            'productImages.*' => 'nullable',
        ],[
            'title.regex' => 'تایتل فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'description.regex' => 'پاسخ فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
        ]);
    
        $directory = public_path('products');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
    
        // ذخیره تصاویر
        $imageUrls = [];
        if ($request->has('productImages')) {
            foreach ($request->productImages as $imageData) {
                if ($imageData) {
                    $imageData = str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,', 'data:image/webp;base64,', ' '], ['', '', '', '+'], $imageData);
                    $imageContent = base64_decode($imageData);
                    
                    if ($imageContent === false) {
                        return back()->withErrors(['productImages' => 'تصویر نامعتبر است']);
                    }
    
                    if (strlen($imageContent) > 2 * 1024 * 1024) {
                        return back()->withErrors(['productImages' => 'حجم تصویر باید کمتر از 2MB باشد']);
                    }
    
                    $fileName = 'products/' . Str::random(40) . '.png';
                    $filePath = public_path($fileName);
                    file_put_contents($filePath, $imageContent);
                    $imageUrls[] = $fileName;
                }
            }
        }
    
        // ایجاد محصول
        $product = Product::create([
            'category_id' => $validated['category'],
            'original_price' => $validated['originalPrice'],
            'status' => $validated['status'],
            'availability' => $validated['availability'] === 'true',
            'tag_id' => $validated['tag'],
            'description' => $validated['description'],
            'title' => $validated['title'],
            'image_urls' => $imageUrls, 
        ]);
    
        return redirect()->route('admin.product.index')->with('success', 'محصول با موفقیت اضافه شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.layouts.sections.product.edit-product',compact('product','categories','tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // اعتبارسنجی ورودی‌ها
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ],
            'category' => 'required|exists:categories,id',
            'originalPrice' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,draft',
            'availability' => 'required|in:true,false',
            'description' => [
                'required',
                'string',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ],
            'tag' => 'required|exists:tags,id',
            'productImages.*' => 'nullable|string',
            'existingImages' => 'nullable|array',
        ], [
            'title.regex' => 'تایتل فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
            'description.regex' => 'پاسخ فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
        ]);

        // مدیریت تصاویر
        $imageUrls = $request->input('existingImages', []); // تصاویر موجود

        // ذخیره تصاویر جدید
        if ($request->has('productImages')) {
            $directory = public_path('products');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            foreach ($request->productImages as $imageData) {
                if ($imageData) {
                    // حذف پیشوند Base64 و اصلاح رشته
                    $imageData = str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,', 'data:image/webp;base64,', ' '], ['', '', '', '+'], $imageData);
                    $imageContent = base64_decode($imageData);

                    if ($imageContent === false) {
                        return back()->withErrors(['productImages' => 'تصویر نامعتبر است']);
                    }

                    if (strlen($imageContent) > 2 * 1024 * 1024) {
                        return back()->withErrors(['productImages' => 'حجم تصویر باید کمتر از 2MB باشد']);
                    }

                    $fileName = 'products/' . Str::random(40) . '.png';
                    $filePath = public_path($fileName);
                    file_put_contents($filePath, $imageContent);
                    $imageUrls[] = $fileName;
                }
            }
        }

        // به‌روزرسانی محصول
        $product->update([
            'title' => $validated['title'],
            'category_id' => $validated['category'],
            'original_price' => $validated['originalPrice'],
            'status' => $validated['status'],
            'availability' => $validated['availability'] === 'true',
            'tag_id' => $validated['tag'],
            'description' => $validated['description'],
            'image_urls' => $imageUrls,
        ]);

        return redirect()->route('admin.product.index')->with('success', 'محصول با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.product.index')->with('success', 'با موفقیقت حذف شد');
    }
}
