<?php
// app/controllers/WishlistController.php

require_once __DIR__ . '/../models/Wishlist.php';

class WishlistController
{
    private Wishlist $model;

    public function __construct(mysqli $db)
    {
        $this->model = new Wishlist($db);
    }

    /**
     * POST /?action=add_to_wishlist
     */
public function add(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // must be logged in
    $email = $_SESSION['customer_email'] ?? null;
    if (!$email || $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $redirect = urlencode($_SERVER['HTTP_REFERER'] ?? 'index.php');
        header("Location: index.php?action=customerlogin&redirect={$redirect}&login_first=1");
        exit;
    }

    $productId = (int)($_POST['product_id'] ?? 0);
    if ($productId > 0) {
        $this->model->addItem($email, $productId);
        $_SESSION['flash_wishlist'] = 'Product added to favourites';
    }

    $back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: {$back}");
    exit;
}

    /**
     * POST /?action=remove_from_wishlist
     */
    public function remove(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['customer_email'] 
               ?? $_SESSION['email'] 
               ?? null;

        if (!$email || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $redirect = urlencode('index.php?action=wishlist');
            header("Location: index.php?action=customerlogin&redirect={$redirect}&login_first=1");
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $this->model->removeItem($email, $productId);
        }

        header('Location: index.php?action=wishlist');
        exit;
    }

    /**
     * GET /?action=wishlist
     */
    public function view(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['customer_email'] 
               ?? $_SESSION['email'] 
               ?? null;

        if (!$email) {
            $redirect = urlencode('index.php?action=wishlist');
            header("Location: index.php?action=customerlogin&redirect={$redirect}&login_first=1");
            exit;
        }

        $items = $this->model->getItems($email);
        require __DIR__ . '/../views/user/wishlist.php';
    }
}
