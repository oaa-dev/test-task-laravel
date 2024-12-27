<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="get list of products",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="search_name",
     *         in="query",
     *         description="Filter products by name",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=10
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get list of products"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            
            $search_name = $request->search_name;
            $perPage = $request->per_page ?? 10;
            $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10;
    
            $products = Product::with('reviews')->where("name", 'LIKE', $search_name)->paginate($perPage);
    
            return response()->json([
                'success' => true,
                'message' => 'Products retrieved',
                'data' => $products,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products/",
     *     summary="store product details",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="product name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="product description",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="product price",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="store product details"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validation_response = $this->validateRequest($request);
        if ($validation_response) {
            return $validation_response;
        }

        try {
            $product = new Product();
            $product->name = trim($request->name);
            $product->description = trim($request->description);
            $product->price = (double) $request->price;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="get single product details",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get single product details"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            
            $product = Product::with('reviews')->find($id);
            if(!$product) throw new \Exception("Product not exist");

            return response()->json([
                'success'   => true,
                'message'   => 'Product display',
                'data'      => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="update product details",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="product name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="product description",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="product price",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="update product details"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validation_response = $this->validateRequest($request);
        if ($validation_response) {
            return $validation_response;
        }

        try {
            $product = Product::find($id);
            if(!$product) throw new \Exception("Product not exist");

            $product->name = trim($request->name);
            $product->description = trim($request->description);
            $product->price = trim($request->price);
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function validateRequest($request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="delete single product details",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete single product details"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            
            $product = Product::find($id);
            if(!$product) throw new \Exception("Product not exist");
            
            //delete record
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
