<?php
require_once __DIR__ . '/../models/Product.php';

class HomeController {
    private $productModel;

    public function __construct($conn) {
        $this->productModel = new Product($conn);
    }

public function homepage() {

    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    $latestItems = $this->productModel->getLatestProducts(5);
    $userEmail = $_SESSION['customer_logged_in'] ?? false ? $_SESSION['email'] : null;

    include __DIR__ . '/../views/home/homepage.php';
}

}