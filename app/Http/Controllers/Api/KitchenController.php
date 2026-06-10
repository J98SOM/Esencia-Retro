<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class KitchenController extends Controller
{
    protected $storagePath = 'kitchen_orders.json';

    // Return list of orders for the kitchen
    public function index(Request $request)
    {
        if (!Storage::exists($this->storagePath)) {
            // seed with sample orders
            $sample = [
                ['id' => 1, 'table' => 'M-01', 'items' => [['name'=>'Hamburguesa','qty'=>2], ['name'=>'Papas fritas','qty'=>1]], 'status' => 'pending', 'created_at' => now()->toDateTimeString()],
                ['id' => 2, 'table' => 'M-03', 'items' => [['name'=>'Ensalada','qty'=>1]], 'status' => 'pending', 'created_at' => now()->subMinutes(5)->toDateTimeString()],
            ];
            Storage::put($this->storagePath, json_encode($sample));
        }

        $raw = Storage::get($this->storagePath);
        $orders = json_decode($raw, true) ?: [];

        return response()->json(['orders' => $orders]);
    }

    // Update status for an order (e.g., preparing, ready)
    public function updateStatus(Request $request, $id)
    {
        $status = $request->input('status');
        if (!$status) return response()->json(['message' => 'status required'], 400);

        $raw = Storage::exists($this->storagePath) ? Storage::get($this->storagePath) : '[]';
        $orders = json_decode($raw, true) ?: [];
        $found = false;
        foreach ($orders as &$o) {
            if (strval($o['id']) == strval($id)) {
                $o['status'] = $status;
                $o['updated_at'] = now()->toDateTimeString();
                $found = true;
                break;
            }
        }
        if (!$found) {
            return response()->json(['message' => 'order not found'], 404);
        }

        Storage::put($this->storagePath, json_encode($orders));
        return response()->json(['orders' => $orders]);
    }
}
