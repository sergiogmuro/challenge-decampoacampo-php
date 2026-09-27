<?php

namespace App\Controllers;

use App\Enums\Currencies;
use App\Models\Product;
use App\Responses\ApiResponse;
use App\Services\PriceCalculator\PriceCalculatorService;
use Src\Controller;

class ProductController extends Controller
{
    public function __construct(
        private readonly Product                $productModel,
        private readonly PriceCalculatorService $priceCalculatorService
    )
    {
    }

    public function index()
    {
        $this->view->json(ApiResponse::make([
            'message' => 'Welcome to my challenge',
            'who' => 'Sergio Muro',
            'request' => 'A simple CRUD api for products',
            'language' => 'PHP raw'
        ]));
    }

    public function list(): void
    {
        $products = $this->productModel->getAll();
        foreach ($products as $i => $product) {
            $products[$i] = $this->processPriceUSD($product);
        }

        $this->view->json(ApiResponse::make($products));
    }

    public function show(int $id): void
    {
        $product = $this->productModel->find($id);
        $product = $this->processPriceUSD($product);

        $this->view->json(ApiResponse::make($product));
    }

    public function store(array $body): void
    {
        $name = $body['nombre'];
        $description = $body['descripcion'];
        $price = $body['precio'];

        $entries = [
            'nombre' => $name,
            'descripcion' => $description,
            'precio' => $price
        ];
        $productModel = $this->productModel;
        $product = $productModel->insert($entries);

        $this->view->json(ApiResponse::make($product), 201);
    }

    public function update(int $id, array $body): void
    {
        $name = $body['nombre'];
        $description = $body['descripcion'];
        $price = $body['precio'];

        $entries = [
            'nombre' => $name,
            'descripcion' => $description,
            'precio' => $price
        ];
        $productModel = $this->productModel;
        $product = $productModel->update($id, $entries);

        $this->view->json(ApiResponse::make($product));
    }

    public function delete(int $id): void
    {
        $productModel = $this->productModel;
        $product = $productModel->delete($id);

        $this->view->json(ApiResponse::make($product), 204);
    }

    private function processPriceUSD($product)
    {
        $usdPrice = $this->priceCalculatorService->calculate(Currencies::USD, $product['precio']);
        $product['precio_usd'] = $usdPrice;

        return $product;
    }
}
