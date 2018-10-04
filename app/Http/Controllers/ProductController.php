<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $amount = $request->input('amount');
        $price = $request->input('price');

        if($amount === null)
        {
            $amount = 100;
        }
        if($price === null || $price === 0)
        {
            $product = Product::all(['name','description','price','amount']);
            return response()->json([
                    "status" => ["products" => $product]
            ], 200);
        }//end if


        $product = Product::where('price', '<=', $price)->
            where('amount','<=',$amount)->
        get(['name','description','price','amount']);


        if(count($product) >= 1)
        {
            return response()->json([
                "status" => ["product" => $product]
            ], 200);
        } else {
            return response()->json([
                "status" => ["error" => "product not found"]
            ]);
        }


    }//end class


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $price = $request->input('price');
        $price = str_replace(",",".", $price);
        $amount = $request->input('amount');


        $products = [
            "name"        =>$name,
            "description" =>$description,
            "price"       =>$price,
            "amount"      =>$amount
        ];


        if(empty($name) or empty($description) or empty($price) or empty($amount))
        {
            return response()->json([
                "status" => ["error" => "complete all fields"]
            ], 400);
        }//end if check empty this fields

        $product = Product::create($products);

        if($product)
        {
            return response()->json([
                "status" => ["sucess" => "product successfully registered"]
            ], 201);
        } else{
            return response()->json([
                "status" => ["error" => "Could not register the product"]
            ], 400);
        }



    }//end store



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id.
     * @return \Illuminate\Http\Response.
     */
    public function show($id)
    {
        $product = Product::find($id);

        if($product)
        {
            return response()->json([
                "status" => ["product" => $product]
            ], 200);
        } else {
            return response()->json([
                "status" => ["error" => "product not found"]
            ], 404);
        }

    }
}//end class
