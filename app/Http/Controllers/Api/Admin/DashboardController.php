<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\PopularProductResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
   {

      $countCustomer = User::role('customer')->count();


      $totalOrders = Order::count();


      $totalRevenue = Order::sum('total_amount') ?? 0;


      $dateRange = [];
      for ($i = 29; $i >= 0; $i--) {
         $dateRange[] = Carbon::now()->subDays($i)->format('Y-m-d');
      }


      $totalRevenues = Order::where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
         ->selectRaw("DATE(created_at) as day, SUM(total_amount) as total")
         ->groupBy('day')
         ->orderBy('day', 'asc')
         ->get()
         ->keyBy('day');


      $revenueGraphData = collect($dateRange)->map(function ($date) use ($totalRevenues) {
         return [
            'day' => $date,
            'total' => $totalRevenues->has($date) ? $totalRevenues->get($date)->total : 0,
         ];
      });

      $popularProducts = OrderItem::join('products', 'products.id', '=', 'order_items.product_id')
      ->select('products.id', 'products.name', DB::raw('COUNT(order_items.product_id) as order_count'))
      ->groupBy('products.id', 'products.name')
      ->orderByDesc('order_count')
      ->limit(15)
      ->with('products')
      ->get();


      return responseSuccess([
         'total_customer' => $countCustomer,
         'total_order' => $totalOrders,
         'total_revenue' => $totalRevenue,
         'revenue_graph' => $revenueGraphData,
         'popular_products'=>PopularProductResource::collection($popularProducts)
      ]);
   }
}
