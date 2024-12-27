<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Validator;

class ReviewController extends Controller
{
    
    /**
     * @OA\Post(
     *     path="/api/products/{id}/reviews",
     *     summary="store product reviews details",
     *     tags={"Reviews"},
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
     *         name="user_name",
     *         in="query",
     *         description="review user_name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="rating",
     *         in="query",
     *         description="review rating",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="comment",
     *         in="query",
     *         description="review comment",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="store product review details"
     *     )
     * )
     */
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'rating' => 'required|integer|min:0|max:5',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            
            $product = Product::find($id);
            if(!$product) throw new \Exception("Product not exist");

            $review = new Review();
            $review->product_id = $id;
            $review->user_name = trim($request->user_name);
            $review->comment = trim($request->comment);
            $review->rating = trim($request->rating);
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Product review created successfully',
                'data' => $review,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
