<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('name')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Client::create($data);
        return redirect()->route('clients.index')->with('success', 'Клиент добавлен');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $this->validated($request);
        $client->update($data);
        return redirect()->route('clients.index')->with('success', 'Клиент обновлен');
    }

    public function destroy(Client $client)
    {
        if (!$client->canDelete()) {
            return back()->withErrors(['delete' => 'Клиент используется в заказах и не может быть удален']);
        }

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Клиент удален');
    }

    public function importForm()
    {
        return view('clients.import');
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
            if (count(array_filter($row)) === 0 || count($row) < 4) {
                continue;
            }

            Client::create([
                'name' => trim($row[0]),
                'email' => trim($row[1]),
                'address' => trim($row[2]),
                'phone' => trim($row[3]),
                'note' => isset($row[4]) ? trim($row[4]) : null,
            ]);
        }

        fclose($handle);

        return redirect()->route('clients.index')->with('success', 'Клиенты загружены');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^\S+\s+\S+/u'],
            'email' => 'required|email',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'note' => 'nullable|string',
        ], [
            'name.regex' => 'ФИО должно содержать минимум два слова',
        ]);
    }
}
