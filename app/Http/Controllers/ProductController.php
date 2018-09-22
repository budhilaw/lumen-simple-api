<?php

namespace App\Http\Controllers;

Use App\Product;
Use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $products   = Product::all();
        return response()->json($products);
    }

    public function show($id)
    {
        $products   = Product::find($id);
        return response()->json($products);
    }

    public function create(Request $request)
    {
        // Don't forget to validate all input from users
        $this->validate($request, [
            'name'  => 'required',
            'price' => 'required'
        ]);

        $product    = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function update($id, Request $request)
    {
        $product    = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json($product, 200);
    }

    public function delete($id)
    {
        $product    = Product::findOrFail($id)->delete();
        return response()->json("Product Deleted..", 200);
    }
}
