<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $from = Carbon::today()->subDays(2)->startOfDay();
        $to = Carbon::today()->endOfDay();

        $orders = Order::with('user')
            ->whereBetween('created_at', [$from, $to])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalCount = $orders->count();
        $totalSum = $orders->sum('total');

        $byUser = $orders->groupBy('user_id')->map(function ($items) {
            $user = $items->first()->user;
            return [
                'name' => $user ? $user->name : '—',
                'count' => $items->count(),
                'sum' => $items->sum('total'),
            ];
        })->values();

        $users = User::orderBy('name')->get();

        return view('home.index', compact('totalCount', 'totalSum', 'byUser', 'from', 'to', 'users'));
    }
}
