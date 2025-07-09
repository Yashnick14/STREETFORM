<?php
// controllers/UserController.php
require_once __DIR__ . '/../models/Order.php';

class UserController {
    private Order $orderModel;

    public function __construct(mysqli $db) {
        $this->orderModel = new Order($db);
    }

    /**
     * Show the “My Account” / order history page.
     */
    public function account(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // require login
        if (empty($_SESSION['email'])) {
            header('Location: index.php?action=customerlogin');
            exit;
        }

        $email  = $_SESSION['email'];
        $orders = $this->orderModel->getOrdersByCustomerEmail($email);
        require __DIR__ . '/../views/user/account.php';
    }

    /**
     * Handle a customer‐initiated “Cancel” action.
     */
    public function cancelOrder(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['email'])) {
            header('Location: index.php?action=account');
            exit;
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $email   = $_SESSION['email'];
        if ($orderId > 0) {
            $this->orderModel->cancelCustomerOrder($orderId, $email);
        }

        header('Location: index.php?action=account');
        exit;
    }
}
