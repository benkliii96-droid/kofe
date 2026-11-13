<?php

namespace App\Http\Controllers;

use App\Models\Characteristic;
use Illuminate\Http\Request;

class CharacteristicController extends Controller
{
    public function create()
    {
        $characteristics = Characteristic::orderBy('name')->get();
        return view('characteristics.create', compact('characteristics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:characteristics,name',
        ]);

        Characteristic::create($data);

        return redirect()->route('products.index')->with('success', 'Характеристика добавлена');
    }
}
