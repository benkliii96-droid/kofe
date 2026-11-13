<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Администратор Системы',
            'email' => 'admin@kofemany.local',
            'login' => 'admin',
            'role' => 'admin',
            'password' => Hash::make('kofeman'),
        ]);

        User::create([
            'name' => 'Менеджер Первый',
            'email' => 'manager1@kofemany.local',
            'login' => 'manager1',
            'role' => 'manager',
            'password' => Hash::make('manager2026'),
        ]);

        User::create([
            'name' => 'Менеджер Второй',
            'email' => 'manager2@kofemany.local',
            'login' => 'manager2',
            'role' => 'manager',
            'password' => Hash::make('manager20262'),
        ]);

        $productsFile = database_path('data/tovary.csv');
        if (file_exists($productsFile)) {
            $handle = fopen($productsFile, 'r');
            fgetcsv($handle, 0, ';');
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (count(array_filter($row)) < 5) {
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
        }

        $clientsFile = database_path('data/klienty.csv');
        if (file_exists($clientsFile)) {
            $handle = fopen($clientsFile, 'r');
            fgetcsv($handle, 0, ';');
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (count(array_filter($row)) < 4) {
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
        }
    }
}
