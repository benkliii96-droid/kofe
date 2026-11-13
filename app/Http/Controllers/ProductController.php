<?php

namespace App\Http\Controllers;

use App\Models\Characteristic;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $characteristics = Characteristic::orderBy('name')->get();
        return view('products.create', compact('characteristics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg|max:2048',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|in:кг.,шт.,гр.,л.,мл.',
            'short_description' => 'required|string|max:255',
            'full_description' => 'required|string',
            'char_id' => 'nullable|array',
            'char_id.*' => 'exists:characteristics,id',
            'char_value' => 'nullable|array',
            'char_value.*' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('products', 'public');
        } else {
            $data['photo'] = null;
        }

        $product = Product::create($data);
        $this->syncCharacteristics($product, $request);

        return redirect()->route('products.index')->with('success', 'Товар добавлен');
    }

    public function edit(Product $product)
    {
        $characteristics = Characteristic::orderBy('name')->get();
        $product->load('characteristics');
        return view('products.edit', compact('product', 'characteristics'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'manufacturer' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg|max:2048',
            'price' => 'required|numeric|min:0',
            'short_description' => 'required|string|max:255',
            'full_description' => 'required|string',
            'char_id' => 'nullable|array',
            'char_id.*' => 'exists:characteristics,id',
            'char_value' => 'nullable|array',
            'char_value.*' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }

        $product->update($data);
        $this->syncCharacteristics($product, $request);

        return redirect()->route('products.index')->with('success', 'Товар обновлен');
    }

    public function destroy(Product $product)
    {
        if (!$product->canDelete()) {
            return back()->withErrors(['delete' => 'Товар используется в заказах и не может быть удален']);
        }

        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Товар удален');
    }

    public function importForm()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $content = file_get_contents($path);
        $delimiter = substr_count($content, ';') >= substr_count($content, ',') ? ';' : ',';

        $handle = fopen($path, 'r');
        fgetcsv($handle, 0, $delimiter);

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count($row) < 5 || count(array_filter($row)) === 0) {
                continue;
            }

            Product::create([
                'name' => trim($row[0]),
                'manufacturer' => trim($row[1]),
                'price' => (float) str_replace(',', '.', trim($row[2])),
                'unit' => trim($row[3]),
                'short_description' => mb_substr(trim($row[4]), 0, 255),
                'full_description' => trim($row[5] ?? $row[4]),
                'photo' => null,
            ]);
        }

        fclose($handle);

        return redirect()->route('products.index')->with('success', 'Товары загружены');
    }

    public function apiIndex()
    {
        $products = Product::orderBy('name')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'photo' => $p->photo ? asset('storage/' . $p->photo) : asset('images/placeholder.png'),
                'unit' => $p->unit,
                'price' => (float) $p->price,
            ];
        });

        return response()->json($products);
    }

    private function syncCharacteristics(Product $product, Request $request): void
    {
        $sync = [];
        $ids = $request->input('char_id', []);
        $values = $request->input('char_value', []);

        foreach ($ids as $i => $id) {
            $value = trim($values[$i] ?? '');
            if ($id && $value !== '') {
                $sync[$id] = ['value' => $value];
            }
        }

        $product->characteristics()->sync($sync);
    }
}
