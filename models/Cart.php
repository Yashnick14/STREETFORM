<?php
// models/CartModel.php
class CartModel
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function addItem(int $productId, string $size, float $price, string $name, string $image, int $qty = 1): void
    {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] === $productId && $item['size'] === $size) {
                $item['quantity'] += $qty;
                return;
            }
        }
        $_SESSION['cart'][] = [
            'product_id' => $productId,
            'size'       => $size,
            'price'      => $price,
            'name'       => $name,
            'image'      => $image,
            'quantity'   => $qty,
        ];
    }

    public function removeItem(int $productId, string $size): void
    {
        foreach ($_SESSION['cart'] as $idx => $item) {
            if ($item['product_id'] === $productId && $item['size'] === $size) {
                array_splice($_SESSION['cart'], $idx, 1);
                return;
            }
        }
    }

    public function updateQuantity(int $productId, string $size, int $quantity): void
    {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] === $productId && $item['size'] === $size) {
                if ($quantity > 0) {
                    $item['quantity'] = $quantity;
                } else {
                    $this->removeItem($productId, $size);
                }
                return;
            }
        }
    }

    public function getItems(): array
    {
        return $_SESSION['cart'];
    }

    public function getSubtotal(): float
    {
        $sum = 0;
        foreach ($_SESSION['cart'] as $item) {
            $sum += $item['price'] * $item['quantity'];
        }
        return $sum;
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }
}
