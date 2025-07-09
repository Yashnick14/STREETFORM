<?php
// controllers/OrderController.php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

class OrderController
{
    private Order   $orderModel;
    private Product $productModel;

    public function __construct(mysqli $db)
    {
        $this->orderModel   = new Order($db);
        $this->productModel = new Product($db);
    }

    /**
     * Show the checkout page (cart summary + form).
     * Exposes in the view: $items, $subtotal, $discount, $finalTotal, $success, $orderId
     */
    public function checkout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1) Pull cart & compute subtotal
        $items    = $_SESSION['cart'] ?? [];
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // 2) Discount & final total
        $discount   = $subtotal * 0.10;
        $finalTotal = $subtotal - $discount;

        // 3) Success banner params
        $success = (isset($_GET['success']) && $_GET['success'] === '1');
        $orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;

        // 4) Hand off to the view
        require __DIR__ . '/../views/cart/checkout.php';
    }

    /**
     * Handle form POST, save order + items, decrement stock, then redirect.
     */
    public function process(): void
    {
        // only accept POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=checkout');
            exit;
        }

        // ensure session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // pull cart & compute totals
        $items    = $_SESSION['cart'] ?? [];
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $finalTotal = $subtotal - ($subtotal * 0.10);

        // grab logged-in customer email (adjust if using user_id instead)
        $customerEmail = $_SESSION['customer_email'] ?? $_SESSION['email'] ?? '';
        if ($customerEmail === '') {
            header('Location: index.php?action=customerlogin');
            exit;
        }

        // 1) Insert into orders, returns new order ID
        $orderId = $this->orderModel->createOrder($customerEmail, $finalTotal);

        // 2) Insert each line item
        $this->orderModel->addItems($orderId, $items);

        // 3) Decrement stock for each purchased item
        foreach ($items as $item) {
            $this->productModel->decrementStock(
                (int)$item['product_id'],
                (int)$item['quantity']
            );
        }

        // 4) Clear the cart
        unset($_SESSION['cart']);

        // 5) Redirect back with success & order_id
        header("Location: index.php?action=checkout&success=1&order_id={$orderId}");
        exit;
    }

    /**
     * Admin: list all orders in the management table.
     */
    public function orders(): void
    {
        $orders = $this->orderModel->getAllOrders();

        $showStatusModal = false;
        $selectedOrders  = [];

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && ($_POST['action'] ?? '') === 'show_status_modal'
        ) {
            $selectedOrders  = $_POST['selected'] ?? [];
            $showStatusModal = !empty($selectedOrders);
        }

        require __DIR__ . '/../views/admin/ordermanagement.php';
    }

    /**
     * Admin: update status for selected orders.
     */
    public function changeOrderStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=orders');
            exit;
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $selected  = $_POST['selected']   ?? [];
        $newStatus = $_POST['new_status'] ?? '';

        if (empty($selected) || $newStatus === '') {
            $_SESSION['flash_error'] = 'Select orders and a status before updating.';
            header('Location: index.php?action=orders');
            exit;
        }

        foreach ($selected as $oid) {
            $this->orderModel->updateStatus((int)$oid, $newStatus);
        }

        header('Location: index.php?action=orders');
        exit;
    }
}
