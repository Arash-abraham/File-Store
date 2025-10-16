<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileProduct;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminFileProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = FileProduct::all();
        return view('admin.layouts.sections.fileProduct.file-management',compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.layouts.sections.fileProduct.create-file',compact('products'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // DEBUG
        // dd('Available disks:', [
        //     'local_exists' => Storage::disk('local')->exists('.'),
        //     'public_exists' => Storage::disk('public')->exists('.'),
        //     'private_exists' => array_key_exists('private', config('filesystems.disks')),
        // ]);
    
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'names' => 'required|array',
            'names.*' => 'required|string|max:255',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:pdf,zip,rar|max:25600',
            'types' => 'required|array',
            'types.*' => 'required|in:pdf,zip,rar',
            'size_labels' => 'nullable|array',
            'size_labels.*' => 'nullable|string|max:50',
            'sort_orders' => 'required|array',
            'sort_orders.*' => 'required|integer|min:1|max:100',
        ]);
    
        try {
            DB::beginTransaction();
    
            $uploadedFiles = $request->file('files');
    
            foreach ($uploadedFiles as $index => $file) {
                $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                
                try {
                    $filePath = $file->storeAs('product-files', $fileName, 'private');
                } catch (\Exception $e) {
                    $filePath = $file->storeAs('product-files', $fileName, 'local');
                }
                
                // DEBUG
                // dd('File storage check:', [
                //     'file_path' => $filePath,
                //     'file_exists' => Storage::exists($filePath),
                //     'full_path' => storage_path('app/' . $filePath),
                // ]);
    
                $fileData = [
                    'product_id' => $request->product_id,
                    'name' => $request->names[$index],
                    'path' => $filePath,
                    'size_label' => $request->size_labels[$index] ?? null,
                    'type' => $request->types[$index],
                    'sort_order' => $request->sort_orders[$index],
                ];
    
                $createdFile = FileProduct::create($fileData);
    
                // dd('Database record created:', $createdFile->toArray());
            }
    
            DB::commit();
    
            return redirect()->route('admin.file-product.index')
                ->with('success', 'فایل‌ها با موفقیت ذخیره شدند.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            dd('Final error:', $e->getMessage());
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
        try {
            DB::beginTransaction();
    
            $file = FileProduct::with('product')->findOrFail($id);
            
            $fileInfo = [
                'id' => $file->id,
                'name' => $file->name,
                'path' => $file->path,
                'product_name' => $file->product->name ?? 'N/A'
            ];
            
    
            if (Storage::exists($file->path)) {
                $deletedFromStorage = Storage::delete($file->path);
                
                if (!$deletedFromStorage) {
                    throw new \Exception('حذف فایل از storage ناموفق بود');
                }
                
            } 
    
            $file->delete();
    
            DB::commit();
    
            return redirect()->route('admin.file-product.index')
                ->with('success', 'فایل "' . $file->name . '" با موفقیت حذف شد.');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.file-product.index')
                ->with('error', 'فایل مورد نظر یافت نشد.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.file-product.index')
                ->with('error', 'خطا در حذف فایل: ' . $e->getMessage());
        }
    }
    public function download($fileId)
    {
        $file = FileProduct::findOrFail($fileId);
        $user = auth()->user();
        if(!$user) {
            return redirect('login');
        } 
        // بررسی آیا کاربر ادمین است
        $isAdmin = $user->role === 'admin' || $user->hasRole('admin'); 


        if ($isAdmin) {
            if (!Storage::exists($file->path)) {
                abort(404, 'فایل یافت نشد.');
            }

            $filePath = Storage::path($file->path);
            $originalName = $file->name . '.' . pathinfo($file->path, PATHINFO_EXTENSION);

            return response()->download($filePath, $originalName);
        }

        $hasPurchased = Payment::where('user_id', $user->id)
            ->whereHas('order', function($query) use ($file) {
                $query->whereHas('orderItems', function($q) use ($file) {
                    $q->where('product_id', $file->product_id);
                });
            })
            ->where('status', 'completed')
            ->exists();

        if (!$hasPurchased) {
            abort(403, 'شما دسترسی به این فایل ندارید.');
        }

        if (!Storage::exists($file->path)) {
            abort(404, 'فایل یافت نشد.');
        }

        $filePath = Storage::path($file->path);
        $originalName = $file->name . '.' . pathinfo($file->path, PATHINFO_EXTENSION);

        return response()->download($filePath, $originalName);
    }
}
