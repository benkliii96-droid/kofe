<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['client', 'user'])->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('order_date')) {
            $query->whereDate('order_date', $request->order_date);
        }
        if ($request->filled('shipment_date')) {
            $query->whereDate('shipment_date', $request->shipment_date);
        }

        $orders = $query->get();
        $clients = Client::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $statuses = Order::STATUSES;

        return view('orders.index', compact('orders', 'clients', 'users', 'statuses'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('orders.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.discount' => 'nullable|numeric|min:0|max:100',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($data) {
            $order = Order::create([
                'number' => 'TMP',
                'client_id' => $data['client_id'],
                'user_id' => auth()->id(),
                'total' => 0,
                'status' => 'new',
                'order_date' => now()->toDateString(),
            ]);
            $order->number = 'ORD-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
            $order->save();

            $this->saveItems($order, $data['items']);
            $order->recalculateTotal();

            return $order;
        });

        return redirect()->route('orders.index')->with('success', 'Заказ создан');
    }

    public function edit(Order $order)
    {
        $order->load(['items.product', 'client']);
        $clients = Client::orderBy('name')->get();
        return view('orders.edit', compact('order', 'clients'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.discount' => 'nullable|numeric|min:0|max:100',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if (!$order->canEditItems()) {
            $order->client_id = $data['client_id'];
            $order->save();
            return redirect()->route('orders.index')->with('success', 'Заказ обновлен (позиции нельзя менять)');
        }

        DB::transaction(function () use ($order, $data) {
            $order->client_id = $data['client_id'];
            $order->save();
            $order->items()->delete();
            $this->saveItems($order, $data['items']);
            $order->recalculateTotal();
        });

        return redirect()->route('orders.index')->with('success', 'Заказ обновлен');
    }

    public function destroy(Order $order)
    {
        if ($order->status !== 'cancelled') {
            $order->status = 'cancelled';
            $order->save();
        }

        return redirect()->route('orders.index')->with('success', 'Заказ отменен');
    }

    public function changeStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:new,shipment,delivery,issued,cancelled',
        ]);

        $newStatus = $data['status'];

        if (!$order->canChangeTo($newStatus)) {
            return back()->withErrors(['status' => 'Нельзя перейти на предыдущий статус']);
        }

        if ($order->status === 'new' && $newStatus !== 'cancelled' && $newStatus !== 'new') {
            if (!$order->shipment_date) {
                $order->shipment_date = now()->toDateString();
            }
        }

        if ($newStatus === 'shipment' && !$order->shipment_date) {
            $order->shipment_date = now()->toDateString();
        }

        $order->status = $newStatus;
        $order->save();

        return back()->with('success', 'Статус обновлен');
    }

    private function saveItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $discount = (float) ($item['discount'] ?? 0);
            $quantity = (int) $item['quantity'];
            $sum = OrderItem::calcSum((float) $product->price, $discount, $quantity);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'discount' => $discount,
                'quantity' => $quantity,
                'sum' => $sum,
            ]);
        }
    }
}
