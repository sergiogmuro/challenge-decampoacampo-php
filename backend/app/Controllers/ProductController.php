<?php

namespace App\Controllers;

use App\Enums\Currencies;
use App\Helpers\MoneyFormat;
use App\Models\Product;
use App\Responses\ApiResponse;
use App\Services\PriceCalculator\PriceCalculatorService;
use Exception;
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
        try {
            $products = $this->productModel->getAll();

            foreach ($products as $i => $product) {
                $products[$i] = $this->processPriceUSD($product);
            }

            $this->view->json(ApiResponse::make($products));
        } catch (\PDOException $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), 500), 500);
        } catch (\Exception $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), $e->getCode()), $e->getCode());
        }
    }

    public function show(int $id): void
    {
        try {
            $product = $this->productModel->find($id);
            if (!$product) {
                throw new Exception("Product not found", 404);
            }

            $product = $this->processPriceUSD($product);

            $this->view->json(ApiResponse::make($product));
        } catch (\PDOException $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), 500), 500);
        } catch (\Exception $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), $e->getCode()), $e->getCode());
        }
    }

    public function store(array $body): void
    {
        try {
            $product = $this->updateOrCreate(
                body: $body
            );

            $this->view->json(ApiResponse::make($product), 201);
        } catch (\PDOException $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), 500), 500);
        } catch (\Exception $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), $e->getCode()), $e->getCode());
        }
    }

    public function update(int $id, array $body): void
    {
        try {
            $product = $this->updateOrCreate(
                id: $id,
                body: $body
            );
            $this->view->json(ApiResponse::make($product));
        } catch (\PDOException $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), 500), 500);
        } catch (\Exception $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), $e->getCode()), $e->getCode());
        }

    }

    public function delete(int $id): void
    {
        try {
            $productModel = $this->productModel;
            $product = $productModel->find($id);
            if (!$product) {
                throw new Exception("Product not found", 404);
            }

            $product = $productModel->delete($id);

            $this->view->json(ApiResponse::make($product), 204);
        } catch (\PDOException $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), 500), 500);
        } catch (\Exception $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), $e->getCode()), $e->getCode());
        }
    }

    private function updateOrCreate(?int $id = null, array $body = [])
    {
        try {
            $name = $body['nombre'] ?? null;
            $description = $body['descripcion'] ?? null;
            $price = $body['precio'] ?? null;

            if ($name === null || $description === null || $price === null) {
                throw new Exception('Keys `nombre`, `descripcion` and `precio` are required', 400);
            }

            $entries = [
                'nombre' => $name,
                'descripcion' => $description,
                'precio' => $price
            ];
            if ($id !== null) {
                $productModel = $this->productModel;
                $product = $productModel->find($id);
                if (!$product) {
                    throw new Exception("Product not found", 404);
                }

                $product = $productModel->update($id, $entries);
            } else {
                $product = $this->productModel->insert($entries);
            }

            return $product;
        } catch (\PDOException $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), 500), 500);
        } catch (\Exception $e) {
            $this->view->json(ApiResponse::make($e->getMessage(), $e->getCode()), $e->getCode());
        }

    }

    private function processPriceUSD(array $product, int $decimals = 2): array
    {
        $usdPrice = $this->priceCalculatorService->calculate(Currencies::USD, $product['precio']);

        if ($usdPrice < 0.009) {
            $decimals = 4;
        }

        $product['precio_usd'] = MoneyFormat::format($usdPrice, $decimals);

        return $product;
    }
}
