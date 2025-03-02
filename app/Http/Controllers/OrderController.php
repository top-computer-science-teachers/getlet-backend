<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        return response()->json(OrderResource::collection($orders), 200);
    }

    public function store(CreateOrderRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        $data['user_id'] = $user->id;

        $order = Order::query()->create($data);

        return response()->json([
            'message' => 'Order created successfully',
            'data' => OrderResource::make($order)
        ], 201);
    }

    public function show(String $id)
    {
        $oder = Order::query()->find($id);

        return response()->json(OrderResource::make($oder), 200);
    }

    public function destroy(String $id)
    {
        $order = Order::query()->find($id);

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
