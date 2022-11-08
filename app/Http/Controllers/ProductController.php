<?php

namespace App\Http\Controllers;


use App\Exceptions\CustomException;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('only-admin')->except('index');
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category')->get();
        return successResponse([
            'products' => $products
        ]);
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = filterData($request->validated());
        $data['image'] = handleFile('/products', $data['image']);
        Product::query()->updateOrCreate(['name' => $data['name']], $data);
        return successResponse();
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return successResponse([
            'product' => $product->load('category')
        ]);
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $data = filterData($request->validated());
        if (exists($data['image'])) {
            $data['image'] = handleFile('/products', $data['image']);
        }
        $product->update($data);
        return successResponse();
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return successResponse();
    }
}
