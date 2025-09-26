<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
 

class ProductsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Products/Index', [
            'filters' => Request::all('search', 'trashed'),
            'products' => Product::filter(Request::only('search', 'trashed'))
                ->get()
                ->transform(fn ($product) => [
                    'id' =>  $product->id,
                    'name' =>  $product->name,
                    'SKU' =>  $product->SKU,
                    'quantity' =>  $product->quantity,
                    'created_at' =>  $product->created_at,
                    'deleted_at' =>  $product->deleted_at,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Products/Create');
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'SKU' => ['required', 'max:50'],
            'quantity' => ['required', 'integer'],
        ]);

        Product::create([
            'name' => Request::get('name'),
            'SKU' => Request::get('SKU'),
            'quantity' => Request::get('quantity'),
        ]);

        return Redirect::route('products')->with('success', 'Products created.');
    }

    public function edit(Product  $product): Response
    {
        return Inertia::render('Products/Edit', [
            'product' => [
                'id' =>  $product->id,
                'name' =>  $product->name,
                'SKU' =>  $product->SKU,
                'quantity' =>  $product->quantity,
                'deleted_at' =>  $product->deleted_at,
            ],
        ]);
    }

    public function update(Product $product): RedirectResponse
    {
        
        Request::validate([
            'name' => ['required', 'max:50'],
            'SKU' => ['required', 'max:50'],
            'quantity' => ['required', 'integer'],
        ]);

        $product->update(Request::only('name', 'SKU', 'quantity'));
 
        return Redirect::back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
         $products->delete();

        return Redirect::back()->with('success', 'Product deleted.');
    }

    public function restore(Product $product): RedirectResponse
    {
         $products->restore();

        return Redirect::back()->with('success', 'Product restored.');
    }
}
