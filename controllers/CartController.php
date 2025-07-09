<?php
// controllers/CartController.php

require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

class CartController
{
    private mysqli    $db;
    private CartModel $cartModel;
    private Product   $productModel;

    public function __construct(mysqli $db)
    {
        // store the DB connection
        $this->db           = $db;
        // session-based cart
        $this->cartModel    = new CartModel();
        // for stock lookups
        $this->productModel = new Product($db);
    }

    /**
     * Display the cart, with real-time stock and subtotal.
     */
    public function cart(): void
    {
        // 1) Pull items out of session
        $rawItems = $this->cartModel->getItems();

        // 2) Inject stock levels
        $items = [];
        foreach ($rawItems as $item) {
            $item['stock'] = $this->productModel->getStock((int)$item['product_id']);
            $items[]       = $item;
        }

        // 3) Compute subtotal
        $subtotal = $this->cartModel->getSubtotal();

        // 4) Render view
        require __DIR__ . '/../views/cart/cart.php';
    }

    /**
     * Add a product to the cart (qty = 1).
     */
public function addToCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=all');
            exit;
        }

        $productId  = (int)($_POST['product_id'] ?? 0);
        $size       = trim($_POST['size'] ?? '');
        // fallback to cart
        $redirectTo = $_POST['redirect_to'] ?: 'index.php?action=cart';

        // require login
        if (empty($_SESSION['customer_id'])) {
            header("Location: index.php?action=view&id={$productId}&login_first=1");
            exit;
        }

        if ($productId && $size) {
            $p = $this->productModel->getProductById($productId);
            $this->cartModel->addItem(
                $p['ProductID'],
                $size,
                (float)$p['Price'],
                $p['ProductName'],
                $p['ImageURL'],
                1
            );
        }

        header("Location: {$redirectTo}");
        exit;
    }

    /**
     * Remove an item entirely.
     */
    public function removeItem(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)($_POST['product_id'] ?? 0);
            $size      = trim($_POST['size'] ?? '');
            if ($productId && $size) {
                $this->cartModel->removeItem($productId, $size);
            }
        }

        header('Location: index.php?action=cart');
        exit;
    }

    /**
     * Update quantity, clamped to [0..stock].
     */
    public function updateQuantity(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=cart');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $size      = trim($_POST['size'] ?? '');
        $newQty    = (int)($_POST['quantity'] ?? 0);

        if ($productId && $size) {
            // clamp to available stock
            $maxStock = $this->productModel->getStock($productId);
            $qty      = max(0, min($newQty, $maxStock));
            $this->cartModel->updateQuantity($productId, $size, $qty);
        }

        header('Location: index.php?action=cart');
        exit;
    }
}