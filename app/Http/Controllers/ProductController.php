<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Support\ImageConverter;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sort = $request->input('sort', 'newest');

        $query->orderBy(
            'created_at',
            $sort === 'oldest' ? 'asc' : 'desc'
        );

        $products = $query->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = ImageConverter::convertToWebp(
                $request->file('image'),
                'products'
            );
        }

        $validated['published_at'] =
            $validated['status'] === 'published' ? now() : null;

        Product::create($validated);

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'products'])
            ->with('active_tab', 'products')
            ->with(
                'success',
                $validated['status'] === 'published'
                    ? 'محصول با موفقیت منتشر شد.'
                    : 'محصول با موفقیت در پیش‌نویس ذخیره شد.'
            );
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        if ($request->hasFile('image')) {
            $newImagePath = ImageConverter::convertToWebp(
                $request->file('image'),
                'products'
            );

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $newImagePath;
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] =
                $product->published_at ?? now();
        } else {
            $validated['published_at'] = null;
        }

        $product->update($validated);

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'products'])
            ->with('active_tab', 'products')
            ->with('success', 'محصول با موفقیت ویرایش شد.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'products'])
            ->with('active_tab', 'products')
            ->with('success', 'محصول با موفقیت حذف شد.');
    }
}