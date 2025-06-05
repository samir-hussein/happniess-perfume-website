<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderLog;
use App\Interfaces\IOrderRepo;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;

class OrderRepo extends BaseRepository implements IOrderRepo
{
	public function __construct(Order $order)
	{
		parent::__construct($order);
	}

	public function createOrder(array $order, array $orderItems)
	{
		$order = $this->create($order);

		$order->orderItems()->createMany($orderItems);

		$order->orderLogs()->create([
			"action_ar" => "تم وضع الطلب",
			"action_en" => "Order Placed",
			"description_ar" => "تم وضع الطلب بنجاح بواسطة " . Auth::user()->name,
			"description_en" => "Order Placed Successfully by " . Auth::user()->name,
		]);

		return $order;
	}

	public function countOrdersByStatus(int $clinetId)
	{
		$counts = $this->model->select('order_status', DB::raw('COUNT(*) as count'))
			->where('client_id', $clinetId)
			->groupBy('order_status')
			->get()
			->keyBy('order_status');

		return [
			'all' => $counts->sum('count'),
			'pending' => $counts['pending']->count ?? 0,
			'processing' => $counts['processing']->count ?? 0,
			'shipped' => $counts['shipped']->count ?? 0,
			'delivered' => $counts['delivered']->count ?? 0,
			'cancelled' => $counts['cancelled']->count ?? 0,
		];
	}

	public function getOrders(int $clientId, string|null $status, string|null $search, int $page = 1, int $perPage = 3)
	{
		return $this->model->where('client_id', $clientId)
			->when($status, function ($query) use ($status) {
				return $query->where('order_status', $status);
			})
			->when($search, function ($query) use ($search) {
				return $query->where('order_number', 'like', "%{$search}%");
			})
			->with('orderItems', 'orderItems.product')
			->orderBy('id', 'desc')
			->paginate($perPage, ['*'], 'page', $page);
	}

	public function cancelOrder(int $orderId)
	{
		$order = $this->getById($orderId);

		$order->update([
			"order_status" => "cancelled",
		]);

		$order->orderLogs()->create([
			"action_ar" => "تم الغاء الطلب",
			"action_en" => "Order Cancelled",
			"description_ar" => "تم إلغاء الطلب بواسطة " . Auth::user()->name,
			"description_en" => "Order Cancelled by " . Auth::user()->name,
		]);

		return $order;
	}

	public function getOrderByNumberAndInvoiceId(string $orderNumber, int $invoiceId)
	{
		return $this->model->where('order_number', $orderNumber)->where('invoice_id', $invoiceId)->first();
	}

	public function updatePaymentStatus(int $id, array $data)
	{
		OrderLog::create([
			"order_id" => $id,
			"action_ar" => $data["action_ar"],
			"action_en" => $data["action_en"],
			"description_ar" => $data["description_ar"],
			"description_en" => $data["description_en"],
		]);
	}
}
