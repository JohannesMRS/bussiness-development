<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        return view('admin.product.index');
    }

    public function store(Request $request){
        $request->validate([
            'title'=>'required',
            'slug'=>'required|unique:products',
            'short_description'=>'required',
            'full_description'=>'required',
            'price'=>'required',
            'image_url'=>'required',
            'status'=>'required',
            'rejection_reason'=>'required',
            'payment_proof'=>'required',
        ]);


        Product::create([
            'title'=>$request->title,
            'slug'=>$request->slug,
            'short_description'=>$request->shortDescription,
            'full_description'=>$request->fullDescription,
            'price'=>$request->price,
            'image_url'=>$request->image_url,
            'status'=>$request->status,
            'rejection_reason'=>$request->rejection_reason,
            'payment_proof'=>$request->payment_proof,
        ]);
        return redirect()->back()->with('success', 'Product created successfully');
    }

    public function show($id){
        $product = Product::findOrFail($id);
        return view('admin.product.show', compact('product'));
    }

    public function edit($id){
        $product = Product::findOrFail($id);

        return view('admin.product.edit', compact('product'));
        
    }

    public function update(Request $request, Product $product){
        $request->validate([
            'title'=>'required',
            'slug'=>'required|unique:products',
            'short_description'=>'required',
            'full_description'=>'required',
            'price'=>'required',
            'image_url'=>'required',
            'status'=>'required',
            'rejection_reason'=>'required',
            'payment_proof'=>'required',
        ]);

        $product->update([
            'title'=>$request->title,
            'slug'=>$request->slug,
            'short_description'=>$request->shortDescription,
            'full_description'=>$request->fullDescription,
            'price'=>$request->price,
            'image_url'=>$request->image_url,
            'status'=>$request->status,
            'rejection_reason'=>$request->rejection_reason,
            'payment_proof'=>$request->payment_proof,
        ]);
        return redirect()->back()->with('success', 'Product updated successfully');
    }


}
