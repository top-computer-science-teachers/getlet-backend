<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CompleteOrderRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Presenters\JsonPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $query = Order::query()
            ->orderByDesc('updated_at');

        $orderType = $request->input('type') ?? 'send';

        $fromCityId = $request->input('from_city_id', null);
        if ($fromCityId) {
            $query->where('from_city_id', $fromCityId);
        }

        $toCityId = $request->input('to_city_id', null);
        if ($toCityId) {
            $query->where('to_city_id', $toCityId);
        }

        $orders = $query
            ->where('type', $orderType)
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

        $data['user_id'] = $user->id;

        return DB::transaction(function () use ($data, $user) {

            if ($data['from_city_id'] == $data['to_city_id']) {
                return JsonPresenter::make()
                    ->setMessage('Невозможно создать заказ в одном городе!')
                    ->setStatusCode(400)
                    ->respond();
            }

            $packages = $data['packages'];
            unset($data['packages']);

            $order = Order::query()->create($data);

            foreach ($packages as $package) {
                OrderPackage::query()->create([
                    'order_id' => $order->id,
                    'title' => $package['title'],
                    'description' => $package['description'] ?? null,
                    'weight' => $package['weight'] ?? 0,
                ]);
            }

            return JsonPresenter::make()
                ->setMessage('Order created successfully')
                ->setData(OrderResource::make($order))
                ->setStatusCode(201)
                ->respond();
        });
    }

    /**
     * @param String $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderTake(String $id, Request $request)
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return JsonPresenter::make()
                ->setError('Order not found')
                ->setStatusCode(404)
                ->respond();
        }

        $order->status = 'in_way';
        $order->save();

        return JsonPresenter::make()
            ->setMessage('Create order take')
            ->setStatusCode(200)
            ->respond();
    }

    /**
     * @param String $id
     * @param CompleteOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $order->status = 'completed';
        } else {
            $order->status = 'failed';
        }

        $order->save();

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

        if ($order->status != 'pending') {
            return JsonPresenter::make()
                ->setMessage('Невозможно редактировать заказ который уже в пути!')
                ->setStatusCode(400)
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
