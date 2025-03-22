<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    //
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'price' => 'required|numeric',
            'brand' => 'required|string|max:100',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        Product::create([
            'name'=>$request->get('name'),
            'price'=>$request->get('price'),
            'brand'=>$request->get('brand')
        ]);        
        return response()->json(['message' => 'Producto agregado de manera correcta'], 201);
    }

    public function getProducts(){
        $products = Product::all();
        if($products->isEmpty()){
            return response()->json(['message' => 'No hay productos en la base de datos'], 404);
        }
        return response()->json($products, 200);
    }

    public function getProduct($id){
        $product = Product::find($id);
        if($product == null){
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return response()->json($product, 200);
    }

    public function updateProduct(Request $request, $id){
        $product = Product::find($id);
        if($product == null){
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'price' => 'sometimes|numeric|max:999',
            'brand' => 'sometimes|string|max:100',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        if($request->has('name')){
            $product->name = $request->name;
        }
        if($request->has('price')){
            $product->price = $request->price;
        }
        if($request->has('brand')){
            $product->brand = $request->brand;
        }
        $product->update();
        return response()->json(['message' => 'Producto actualizado de manera correcta'], 200); 
    }

    public function deleteProductById($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        $product->delete();
    }
}
