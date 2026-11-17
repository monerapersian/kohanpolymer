<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SiteContent;
use App\Models\Visit;
use App\Services\ActivityLogger;
use App\Support\ImageConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display products in the admin panel.
     */
    public function index(Request $request)
    {
        $query = Product::with('details');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where('name', 'like', "%{$search}%");
        }

        $sort = $request->input('sort', 'newest');

        $query->orderBy(
            'created_at',
            $sort === 'oldest' ? 'asc' : 'desc'
        );

        $products = $query
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Display published products on the public website.
     */
    public function publicIndex(Request $request)
    {
        Visit::record('products');

        $content = SiteContent::first();
        $siteContent = $content;

        $featuredProducts = Product::query()
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $query = Product::query()
            ->where('status', 'published');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where('name', 'like', "%{$search}%");
        }

        $sort = $request->input('sort', 'newest');

        if (!in_array($sort, ['newest', 'oldest'], true)) {
            $sort = 'newest';
        }

        if ($sort === 'oldest') {
            $query->orderBy('published_at')
                ->orderBy('created_at');
        } else {
            $query->orderByDesc('published_at')
                ->orderByDesc('created_at');
        }

        $products = $query
            ->paginate(12)
            ->withQueryString();

        $search = $request->input('search');

        return view('products.index', compact(
            'content',
            'siteContent',
            'featuredProducts',
            'products',
            'search',
            'sort'
        ));
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['sometimes', 'boolean'],

            'properties' => ['nullable', 'array'],
            'properties.*' => ['nullable', 'string', 'max:255'],

            'sizes' => ['nullable', 'array'],
            'sizes.*.thickness' => ['nullable', 'numeric', 'min:0'],
            'sizes.*.width' => ['nullable', 'numeric', 'min:0'],

            'eps' => ['nullable', 'array'],
            'eps.*' => ['nullable', 'string', 'max:255'],

            'goods_descriptions' => ['nullable', 'array'],
            'goods_descriptions.*' => ['nullable', 'string', 'max:255'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = ImageConverter::convertToWebp(
                $request->file('image'),
                'products'
            );
        }

        $product = DB::transaction(function () use (
            $validated,
            $imagePath
        ) {
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'image' => $imagePath,
                'status' => $validated['status'],
                'is_featured' => $validated['is_featured'] ?? false,
                'published_at' => $validated['status'] === 'published'
                    ? now()
                    : null,
            ]);

            $this->syncProductDetails($product, $validated);

            app(ActivityLogger::class)->log(
                action: 'product_created',
                description: 'محصول جدید «' . $product->name . '» ایجاد شد.',
                subject: $product,
                properties: [
                    'name' => $product->name,
                    'status' => $product->status,
                    'is_featured' => (bool) $product->is_featured,
                ]
            );

            return $product;
        });

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'products'])
            ->with('success', 'محصول با موفقیت ثبت شد.');
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['sometimes', 'boolean'],

            'properties' => ['nullable', 'array'],
            'properties.*' => ['nullable', 'string', 'max:255'],

            'sizes' => ['nullable', 'array'],
            'sizes.*.thickness' => ['nullable', 'numeric', 'min:0'],
            'sizes.*.width' => ['nullable', 'numeric', 'min:0'],

            'eps' => ['nullable', 'array'],
            'eps.*' => ['nullable', 'string', 'max:255'],

            'goods_descriptions' => ['nullable', 'array'],
            'goods_descriptions.*' => ['nullable', 'string', 'max:255'],
        ]);

        $oldValues = [
            'name' => $product->name,
            'status' => $product->status,
            'is_featured' => (bool) $product->is_featured,
        ];

        $oldImage = $product->image;
        $imagePath = $oldImage;

        if ($request->hasFile('image')) {
            $imagePath = ImageConverter::convertToWebp(
                $request->file('image'),
                'products'
            );
        }

        DB::transaction(function () use (
            $product,
            $validated,
            $imagePath,
            $oldValues
        ) {
            $publishedAt = null;

            if ($validated['status'] === 'published') {
                $publishedAt = $product->published_at ?? now();
            }

            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'image' => $imagePath,
                'status' => $validated['status'],
                'is_featured' => $validated['is_featured'] ?? false,
                'published_at' => $publishedAt,
            ]);

            $this->syncProductDetails($product, $validated);

            $newValues = [
                'name' => $product->name,
                'status' => $product->status,
                'is_featured' => (bool) $product->is_featured,
            ];

            $changes = [];

            foreach ($newValues as $field => $newValue) {
                if ($oldValues[$field] !== $newValue) {
                    $changes[$field] = [
                        'old' => $oldValues[$field],
                        'new' => $newValue,
                    ];
                }
            }

            $description = 'محصول «' . $product->name . '» ویرایش شد.';

            if (isset($changes['status'])) {
                $description .= ' وضعیت انتشار تغییر کرد.';
            }

            if (isset($changes['is_featured'])) {
                $description .= $newValues['is_featured']
                    ? ' محصول به محصولات ویژه اضافه شد.'
                    : ' محصول از محصولات ویژه خارج شد.';
            }

            app(ActivityLogger::class)->log(
                action: 'product_updated',
                description: $description,
                subject: $product,
                properties: [
                    'changes' => $changes,
                ]
            );
        });

        if ($request->hasFile('image') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'products'])
            ->with('success', 'محصول با موفقیت ویرایش شد.');
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $product)
    {
        $imagePath = $product->image;

        $deletedProduct = [
            'name' => $product->name,
            'status' => $product->status,
            'is_featured' => (bool) $product->is_featured,
        ];

        DB::transaction(function () use ($product, $deletedProduct) {
            $product->details()->delete();
            $product->delete();

            app(ActivityLogger::class)->log(
                action: 'product_deleted',
                description: 'محصول «' . $deletedProduct['name'] . '» حذف شد.',
                subject: $product,
                properties: $deletedProduct
            );
        });

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.site-content.edit', ['tab' => 'products'])
            ->with('success', 'محصول با موفقیت حذف شد.');
    }

    /**
     * Display a published product.
     */
    public function show(Product $product)
    {
        abort_unless($product->status === 'published', 404);

        Visit::record('product', $product->id);

        $product->load('details');

        $content = SiteContent::first();
        $siteContent = $content;

        return view('products.show', compact(
            'product',
            'content',
            'siteContent'
        ));
    }

    /**
     * Synchronize product details.
     */
    private function syncProductDetails(Product $product, array $data): void
    {
        $product->details()->delete();

        $sortOrder = 0;

        $textGroups = [
            'properties' => 'property',
            'eps' => 'ep',
            'goods_descriptions' => 'goods_description',
        ];

        foreach ($textGroups as $field => $type) {
            foreach ($data[$field] ?? [] as $value) {
                $value = trim((string) $value);

                if ($value === '') {
                    continue;
                }

                $product->details()->create([
                    'type' => $type,
                    'value' => $value,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        foreach ($data['sizes'] ?? [] as $size) {
            $thickness = $size['thickness'] ?? null;
            $width = $size['width'] ?? null;

            if (
                ($thickness === null || $thickness === '') &&
                ($width === null || $width === '')
            ) {
                continue;
            }

            $product->details()->create([
                'type' => 'size',
                'thickness' => $thickness !== '' ? $thickness : null,
                'width' => $width !== '' ? $width : null,
                'sort_order' => $sortOrder++,
            ]);
        }
    }
}