<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreOrderRequest;

class OrdersController extends Controller
{
    public function index(): Response
    {
        $query = Order::query();

        $statuses = [];

        if (Gate::allows('view-pending-orders')) {
            $statuses[] = 'pending';
        }
        
        if (Gate::allows('approve-orders')) {
            $statuses[] = 'pending';
        }

        if (Gate::allows('ship-orders')) {
            $statuses[] = 'approved';
        }
        
        
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        return Inertia::render('Orders/Index', [
            'filters' => Request::all('search','trashed'),
            'orders' => $query->filter(Request::only('search','trashed'))
                ->get()
                ->transform(fn ($order) => [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'deleted_at' => $order->deleted_at,
                ]),
        ]);
    }

    public function create(): Response
    {
        $products = Product::all();
        return Inertia::render('Orders/Create', [
            'products' => $products,
        ]);
    }


    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $order = Order::create([
            'customer_name' => $data['customer_name'],
            'status' => $data['status'],
        ]);

        foreach ($data['items'] as $item) {
            $order->products()->attach($item['product_id'], [
                'quantity' => $item['quantity'],
            ]);
        }

        return Redirect::route('orders')->with('success', 'Order created.');
    }




    public function edit(Order $order): \Inertia\Response
    {
        return Inertia::render('Orders/Edit', [
            'order' => [
                'id' => $order->id,
                'customer_name' => $order->customer_name,
                'status' => $order->status,
                'deleted_at' => $order->deleted_at,
                 
                'items' => $order->products->map(function ($item) {
                    return [
                        'product_id' => $item->id,
                        'name' => $item->name,
                        'quantity' => $item->pivot->quantity,
                    ];
                }),
            ],
             
            'products' => Product::all(['id', 'name', 'quantity']),
        ]);
    }

    public function update(Order $order): RedirectResponse
    {
        $data = Request::validate([
            'customer_name' => ['required', 'max:50'],
            'status' => ['required'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);
    
        $order->update([
            'customer_name' => $data['customer_name'],
            'status' => $data['status'],
        ]);
    
        $syncData = collect($data['items'])
            ->mapWithKeys(fn ($item) => [
                $item['product_id'] => ['quantity' => $item['quantity']],
            ])
            ->toArray();
    
        $order->products()->sync($syncData);
    
        return Redirect::route('orders')->with('success', 'Order updated.');
    }


    public function approve(Order $order)
    {
        if (!Gate::allows('approve-orders')) {
            abort(403);
        }
    
        foreach ($order->products as $item) {
           
            if ($item->pivot->quantity > $item->quantity) {
                return back()->withErrors(['msg' => 'Stock not enough for ' . $item->product->name]);
            }
        }
    
        foreach ($order->products as $item) {
            $product = Product::find($item->id);  
            $product->quantity -= $item->pivot->quantity;
            $product->save();
        }
    
        $order->update(['status' => 'approved']);
    
        return Redirect::route('orders')->with('success', 'Order approved!');
    }
    


    public function ship(Order $order)
    {
        if (!Gate::allows('ship-orders')) {
            abort(403);
        }

        if ($order->status !== 'approved') {
            return back()->withErrors(['msg' => 'Order must be approved first']);
        }

        $order->update(['status' => 'Shipped']);
        return Redirect::route('orders')->with('success', 'Order shipped!');
    }


    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return Redirect::route('orders')->with('success', 'Order Canceled.');
    }

    public function restore(Order $order): RedirectResponse
    {
        $order->restore();

        return Redirect::back()->with('success', 'Order restored.');
    }
}
