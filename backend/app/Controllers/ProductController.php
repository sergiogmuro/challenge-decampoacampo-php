<?php

namespace App\Controllers;

use App\Models\Product;
use Src\Controller;

class ProductController extends Controller
{
    public function __construct(private Product $productModel)
    {}

    public function index()
    {
        echo "Welcome to my challenge";
    }

    public function list()
    {
        $products = $this->productModel->getAll();

        $this->view->json($products);
    }
}
