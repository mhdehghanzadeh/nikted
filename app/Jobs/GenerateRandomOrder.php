<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateRandomOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {

        Log::info('GenerateRandomOrder job dispatched at '.now());

        $products = Product::all();

        if ($products->isEmpty()) return;

        
        $order = Order::create([
            'customer_name' => 'Customer ' . rand(1, 1000),
            'status' => 'pending',
        ]);

        
        $totalQuantity = 0;
        $selectedProducts = $products->random(rand(1, min(5, $products->count())));

        foreach ($selectedProducts as $product) {
            $qty = rand(1, 10);

            $order->products()->attach($product->id, ['quantity' => $qty]);
            $totalQuantity += $qty;
        }

         
        $order->update(['quantity' => $totalQuantity]);
    }
}
