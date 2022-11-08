<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\AddProductToCartRequest;
use App\Models\CartProduct;
use Illuminate\Http\JsonResponse;

class CartProductController extends Controller
{
    /**
     * @return JsonResponse
     * @throws CustomException
     */
    public function cartProducts(): JsonResponse
    {
        $user = currentUser();
        $cartProducts = $user->cartProducts()->with('product')->get();
        return successResponse([
            'cart_products' => $cartProducts
        ]);
    }

    /**
     * @param AddProductToCartRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function addProductToCart(AddProductToCartRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = currentUser();
        $user->cartProducts()->create($data);
        return successResponse();
    }

    /**
     * @param CartProduct $cartProduct
     * @return JsonResponse
     */
    public function destroy(CartProduct $cartProduct): JsonResponse
    {
        $cartProduct->delete();
        return successResponse();
    }

    /**
     * @param AddProductToCartRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function changeProductQuantity(AddProductToCartRequest $request): JsonResponse
    {
        $data = $request->validated();
        CartProduct::query()->updateOrCreate([
            'user_id' => currentUser()->getId(),
            'product_id'=> $data['product_id']
        ], [
            'user_id' => currentUser()->getId(),
            'product_id'=> $data['product_id'],
            'quantity' => $data['quantity']
        ]);
        return successResponse();
    }
}
