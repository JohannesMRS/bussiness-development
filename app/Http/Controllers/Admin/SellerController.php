<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SellerController extends Controller
{
    public function index(){
        return view('admin.seller.index');
    }

     public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required | unique:users',
            'password' =>'required',
            'role' => 'required',
            'whatsapp_number' => 'required',
            'major' => 'required',
            'bussiness_name' => 'required',
            'bussiness_description' => 'required',
            'avatar_url' => 'required',
        ]);


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,
            'role' => $request->role,
            'whatsapp_number' => $request->whatsapp_number,
            'major' => $request->major,
            'bussiness_name' => $request->bussiness_name,
            'bussiness_description' => $request->bussiness_description,
            'avatar_url' => $request->avatar_url,
        ]);
        return redirect()->back()->with('success', 'Product created successfully');
    }

    public function show($id){
        $product = User::findOrFail($id);
        return view('admin.seller.show', compact('product'));
    }

    public function edit($id){
        $product = User::findOrFail($id);

        return view('admin.seller.edit', compact('product'));
        
    }

    public function update(Request $request, User $user){
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

        $user->update([
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

    public function destroy($id){
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back()->with('success', 'Product deleted successfully');
    }

}
