<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CompleteOrderRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Models\UserStatistics;
use App\Presenters\JsonPresenter;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /**
     * get orders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // todo: need to add sort by price
        // todo: need to add filter by date period

        $page = $request->input('page') ?? 1;
        $perPage = $request->input('per_page') ?? 20;

        $query = Order::query()
            ->orderByDesc('updated_at');

        $orderType = $request->input('order_type') ?? 'send';

        $fromCityId = $request->input('from_city_id') ?? null;
        if ($fromCityId) {
            $query->where('from_city_id', $fromCityId);
        }

        $toCityId = $request->input('to_city_id') ?? null;
        if ($toCityId) {
            $query->where('to_city_id', $toCityId);
        }

        $orders = $query
            ->where('order_type', $orderType)
            ->paginate($perPage, ['*'], 'page', $page);

        return JsonPresenter::make()
            ->setData(OrderResource::collection($orders))
            ->setPagination($orders)
            ->setStatusCode(200)
            ->respond();
    }

    /**
     * create order
     *
     * @param CreateOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateOrderRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        $data['author_id'] = $user->id;

        $order = Order::query()->create($data);

        if ($order) {
            $order->user->statistics->order_send_created_count = $order->author->statistics->order_send_created_count + 1;
        }

        return JsonPresenter::make()
            ->setMessage('Order created successfully')
            ->setData(OrderResource::make($order))
            ->setStatusCode(201)
            ->respond();
    }

    public function orderTake(String $id, Request $request)
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return JsonPresenter::make()
                ->setError('Order not found')
                ->setStatusCode(404)
                ->respond();
        }

        $contractor = $request->user();

        $order->status = 'in_way';
        $order->save();

        $contractor->statistics->order_take_created_count = $contractor->statistics->order_take_created_count + 1;
    }

    public function orderComplete(String $id, CompleteOrderRequest $request)
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return JsonPresenter::make()
                ->setError('Order not found')
                ->setStatusCode(404)
                ->respond();
        }

        $data = $request->validated();

        if ($data['status']) {
            $order->author->statistics->order_send_completed_count = $order->author->statistics->order_send_completed_count + 1;
            $order->contractor->statistics->order_take_completed_count = $order->contractor->statistics->order_take_completed_count + 1;
            $order->status = 'completed';
            $order->save();
        } else {
            $order->author->statistics->order_send_failed_count = $order->author->statistics->order_send_failed_count + 1;
            $order->contractor->statistics->order_take_failed_count = $order->contractor->statistics->order_take_failed_count + 1;
            $order->status = 'failed';
            $order->save();
        }

        return JsonPresenter::make()
            ->setMessage('Order status updated successfully')
            ->setStatusCode(200)
            ->respond();
    }

    /**
     * show order
     *
     * @param String $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(String $id)
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return JsonPresenter::make()
                ->setError('Order not found')
                ->setStatusCode(404)
                ->respond();
        }

        return JsonPresenter::make()
            ->setData(OrderResource::make($order))
            ->setStatusCode(200)
            ->respond();
    }

    /**
     * update order
     *
     * @param String $id
     * @param UpdateOrderRequest $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function update(String $id, UpdateOrderRequest $request)
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return JsonPresenter::make()
                ->setError('Order not found')
                ->setStatusCode(404)
                ->respond();
        }

        $data = $request->validated();

        $order->update($data);

        return JsonPresenter::make()
            ->setMessage('Order updated successfully')
            ->setData(OrderResource::make($order))
            ->setStatusCode(200)
            ->respond();
    }

    /**
     * @param String $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(String $id)
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return JsonPresenter::make()
                ->setError('Order not found')
                ->setStatusCode(404)
                ->respond();
        }
        $order->delete();

        return JsonPresenter::make()
            ->setMessage('Order deleted successfully')
            ->setStatusCode(200)
            ->respond();
    }
}
