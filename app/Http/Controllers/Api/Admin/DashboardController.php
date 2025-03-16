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

      $startDate = Carbon::now()->subDays(30);

      $countCustomer = User::role('customer')
          ->where('created_at', '>=', $startDate)
          ->count();
      $totalOrders = Order::where('created_at', '>=', $startDate)
          ->count();
      $totalRevenue = Order::where('created_at', '>=', $startDate)
          ->sum('total_amount') ?? 0;


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


      // Fetch net profit data
      $netProfit = OrderItem::join('products', 'products.id', '=', 'order_items.product_id')
          ->where('order_items.created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
          ->selectRaw("DATE(order_items.created_at) as day, SUM((products.unit_price) * order_items.quantity) as profit")
          ->groupBy('day')
          ->orderBy('day', 'asc')
          ->get()
          ->keyBy('day');
      
          $netProfitGraphData = collect($dateRange)->map(function ($date) use ($netProfit) {
            return [
               'day' => $date,
               'total' => $netProfit->has($date) ? $netProfit->get($date)->profit : 0,
            ];
         });

      $popularProducts = OrderItem::join('products', 'products.id', '=', 'order_items.product_id')
         ->select(
            'products.id',
            'products.name',
            DB::raw('COUNT(order_items.product_id) as order_count'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total')
         )
         ->groupBy('products.id', 'products.name')
         ->orderByDesc('order_count')
         ->limit(3)
         ->get();

         $popularProductCategories = OrderItem::join('products', 'products.id', '=', 'order_items.product_id')
         ->join('categories', 'categories.id', '=', 'products.category_id') // Correct join condition
         ->select(
             'categories.id',
             'categories.name',
             DB::raw('COUNT(order_items.product_id) as order_count'),
             DB::raw('SUM(order_items.quantity * order_items.price) as total')
         )
         ->groupBy('categories.id', 'categories.name') // Group by category fields
         ->orderByDesc('total')
         ->limit(3)
         ->get();

         $topSellingProducts = OrderItem::join('products', 'products.id', '=', 'order_items.product_id')
         ->select(
            'products.id',
            'products.name',
            DB::raw('COUNT(order_items.product_id) as order_count'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total')
         )
         ->groupBy('products.id', 'products.name')
         ->orderByDesc('total')
         ->limit(3)
         ->get();


      return responseSuccess([
         'last_30_days_customer' => $countCustomer,
         'last_30_days_order' => $totalOrders,
         'last_30_days_revenue' => $totalRevenue,
         'revenue_graph' => $revenueGraphData,
         'net_profit_graph_data'=>$netProfitGraphData,
         'top_category_by_revenue'=>$popularProductCategories,
         'popular_products' => PopularProductResource::collection($popularProducts),
         'top_selling_products'=>$topSellingProducts
      ]);
   }
}