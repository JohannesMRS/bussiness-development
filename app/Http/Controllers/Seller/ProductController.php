<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        $products = Product::latest()->paginate(10);
        return view('seller.product.index', compact('products'));
    }

    public function store(Request $request){
        $request->validate([
            'title'=>'required',
            'slug'=>'required|unique:products',
            'short_description'=>'required',
            'full_description'=>'required',
            'price'=>'required',
            'image_url'=>'required',
            'payment_proof'=>'required',
        ]);

        Product::create([
            'title'=>$request->title,
            'slug'=>$request->slug,
            'short_description'=>$request->shortDescription,
            'full_description'=>$request->fullDescription,
            'price'=>$request->price,
            'image_url'=>$request->image_url,
            'payment_proof'=>$request->payment_proof,
        ]);

        return redirect()->back()->with('success', 'Product created successfully');
    }

    public function show($id){
        $product = Product::findOrFail($id);
        return view('seller.product.show', compact('product'));
    }

    public function edit($id){
        $product = Product::findOrFail($id);

        return view('seller.product.edit', compact('product'));
        
    }

    public function update(Request $request, Product $product){
        $request->validate([
            'title'=>'required',
            'slug'=>'required|unique:products',
            'short_description'=>'required',
            'full_description'=>'required',
            'price'=>'required',
            'image_url'=>'required',
            'payment_proof'=>'required',
        ]);

        $product->update([
            'title'=>$request->title,
            'slug'=>$request->slug,
            'short_description'=>$request->shortDescription,
            'full_description'=>$request->fullDescription,
            'price'=>$request->price,
            'image_url'=>$request->image_url,
            'payment_proof'=>$request->payment_proof,
        ]);
        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function destroy($id){
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully');
    }

}
