<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'username' => 'required|string',
            'phone' => 'required|string',
            'cart' => 'required|array',
            'cart.*.name' => 'required|string',
            'cart.*.qty' => 'required|integer',
            'cart.*.price' => 'required|numeric',
        ]);

        // Debug: log validated data
        \Log::info('Order validated from API', ['validated' => $validated]);

        // Save order to database
        $order = Order::create([
            'username' => $validated['username'],
            'phone' => $validated['phone'],
            'cart' => json_encode($validated['cart']), // Encode cart as JSON
        ]);

        // Debug: log created order
        \Log::info('Order created from API', ['order' => $order]);

        // --- Telegram Bot Notification ---
        $botToken = '7409975411:AAH92HER0iGfGGR9Lsr3227J-ORwkPgF_NM';
        $chatId = '1208908312';

        $cartItems = collect($validated['cart'])->map(function ($item) {
            return "• {$item['name']} x{$item['qty']} = {$item['price']}";
        })->implode("\n");

        $message = "🛒 New Order\n"
            . "👤 Name: {$validated['username']}\n"
            . "📞 Phone: {$validated['phone']}\n"
            . "🛍️ Items:\n{$cartItems}";

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $params = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ];

        // Send Telegram message
        try {
            $client = new \GuzzleHttp\Client();
            $client->post($url, ['form_params' => $params]);
        } catch (\Exception $e) {
            \Log::error('Telegram notification failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Telegram notification failed.']);
        }

        return redirect()->route('order.index')->with('success', 'Order created successfully.');
    }

    public function index()
    {
        $orders = Order::all();
        return inertia('order/Index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        return inertia('order/create');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return inertia('order/edit', [
            'order' => $order
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'username' => 'required|string',
            'phone' => 'required|string',
            'cart' => 'required', // Should be JSON string
        ]);
        $order->update($validated);
        return redirect()->route('order.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('order.index')->with('success', 'Order deleted successfully.');
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'phone' => 'required|string',
            'cart' => 'required|array',
            'cart.*.name' => 'required|string',
            'cart.*.qty' => 'required|integer',
            'cart.*.price' => 'required|numeric',
        ]);

        $order = Order::create([
            'username' => $validated['username'],
            'phone' => $validated['phone'],
            'cart' => json_encode($validated['cart']),
        ]);

        // Optionally: send Telegram notification here

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }
}
