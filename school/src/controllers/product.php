<?php
require_once __DIR__ . "/../data/repositories/product-repository.php";
require_once __DIR__ . "/../../../common/utils/field_checker.php";


class ProductController
{
    private $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }


    public function getProducts($requestData)
    {
        $products = $this->productRepository->findAll();
        echo json_encode($products);
    }


    public function createProduct($requestData)
    {
        $errors = checkFields($requestData, array("name", "image", "price"));
        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }
        extract($requestData);
        $product = new Product();
        $product->name = $name;
        $product->image = $image;
        $product->price = $price;
        $this->productRepository->save($product);
    }
}
