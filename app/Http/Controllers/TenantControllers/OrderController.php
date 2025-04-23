<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\TouristSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Initialize tenancy to get the current tenant
            $tenant = tenancy()->tenant;
            $tenantName = $tenant->tenant_city; 
            
            // Get unique customers by name only with their latest order
            $customers = Order::with('touristspot')
                ->select('name')
                ->selectRaw('MAX(created_at) as latest_order_date')
                ->groupBy('name')
                ->orderBy('latest_order_date', 'desc')
                ->get()
                ->map(function ($group) {
                    return Order::with('touristspot')
                        ->where('name', $group->name)
                        ->latest()
                        ->first();
                });
                
            return view('tenantviews.tenantdash.tenanttrackorder', compact('tenantName', 'customers'));
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load dashboard.');
        }
    }
    
    public function getCustomerOrders($name)
    {
        $orders = Order::with('touristspot')
            ->where('name', $name)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json($orders);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $touristSpots = TouristSpot::all();
        return view('tenant.orders.create', compact('touristSpots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'touristspot_id' => 'required|exists:touristspot,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'quantity' => 'required|integer|min:1',
            'order_type' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
        ]);

        Order::create($validated);

        return redirect()->back()->with('success', 'Order submitted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('tenant.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $touristSpots = TouristSpot::all();
        return view('tenant.orders.edit', compact('order', 'touristSpots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'touristspot_id' => 'required|exists:tourist_spots,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'quantity' => 'required|integer|min:1',
            'order_type' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}