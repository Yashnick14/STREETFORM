<?php
// models/Order.php

class Order {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    /**
     * Create a new order record.
     *
     * @param string $customerEmail
     * @param float  $totalAmount
     * @return int   Inserted OrderID
     */
    public function createOrder(string $email, float $total): int {
        $stmt = $this->db->prepare(
          "INSERT INTO orders (CustomerEmail, TotalAmount, OrderStatus)
           VALUES (?, ?, 'Pending')"
        );
        $stmt->bind_param("sd", $email, $total);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

public function orders() {
        // fetch all orders
        $orders = $this->orderModel->getAllOrders();

        // did admin click “Change status”?
        $showStatusModal = false;
        $selectedOrders  = [];

        if ($_SERVER['REQUEST_METHOD']==='POST'
         && ($_POST['action'] ?? '')==='show_status_modal')
        {
            $selectedOrders = $_POST['selected'] ?? [];
            if (empty($selectedOrders)) {
                $_SESSION['flash_error'] = 'Please select at least one order.';
            } else {
                $showStatusModal = true;
            }
        }

        require __DIR__.'/../views/admin/ordermanagement.php';
    }

    public function changeOrderStatus(): void {
        if ($_SERVER['REQUEST_METHOD']!=='POST') {
            header('Location: index.php?action=orders'); exit;
        }

        $selected  = $_POST['selected']    ?? [];
        $newStatus = $_POST['new_status']  ?? '';

        if (empty($selected) || $newStatus==='') {
            $_SESSION['flash_error'] = 'Select some orders and pick a status before updating.';
            header('Location: index.php?action=orders');
            exit;
        }

        foreach ($selected as $oid) {
            $this->orderModel->updateStatus((int)$oid, $newStatus);
        }

        header('Location: index.php?action=orders');
        exit;
    }   

public function addItems(int $orderId, array $items): void {
    $stmt = $this->db->prepare(
      "INSERT INTO order_items (OrderID, ProductID, Quantity)
       VALUES (?, ?, ?)"
    );
    foreach ($items as $item) {
        // adjust the keys if you call them differently
        $stmt->bind_param("iii", $orderId, $item['product_id'], $item['quantity']);
        $stmt->execute();
    }
    $stmt->close();
}

public function updateStatus(int $orderId, string $newStatus): bool {
    $stmt = $this->db->prepare(
      "UPDATE orders
         SET OrderStatus = ?
       WHERE OrderID = ?");
    $stmt->bind_param('si', $newStatus, $orderId);
    return $stmt->execute();
}


    /**
     * Fetch all orders (for admin listing).
     *
     * @return array
     */
 public function getAllOrders(): array {
        $sql = "
          SELECT
            o.OrderID,
            o.CustomerEmail,
            o.OrderDate,
            o.TotalAmount,
            o.OrderStatus,
            p.ProductName,
            oi.Quantity
          FROM orders o
          JOIN order_items oi   ON o.OrderID    = oi.OrderID
          JOIN products p       ON oi.ProductID = p.ProductID
          ORDER BY o.OrderDate DESC, o.OrderID, p.ProductName
        ";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Count total number of orders.
     *
     * @return int
     */
    public function getOrderCount(): int {
        $sql = "SELECT COUNT(*) AS cnt FROM orders";
        $res = $this->db->query($sql);
        $row = $res->fetch_assoc();
        return (int)$row['cnt'];
    }

    public function getOrdersByCustomerEmail(string $email): array {
        $sql = "
          SELECT
            o.OrderID,
            p.ProductName,
            oi.Quantity,
            o.OrderStatus,
            o.TotalAmount
          FROM orders o
          JOIN order_items oi   ON o.OrderID    = oi.OrderID
          JOIN products p       ON oi.ProductID = p.ProductID
          WHERE o.CustomerEmail = ?
          ORDER BY o.OrderDate DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Cancel one of the customer’s own orders.
     */
    public function cancelCustomerOrder(int $orderId, string $email): bool {
        $stmt = $this->db->prepare("
          UPDATE orders
             SET OrderStatus = 'Cancelled'
           WHERE OrderID = ?
             AND CustomerEmail = ?
             AND OrderStatus <> 'Cancelled'
        ");
        $stmt->bind_param("is", $orderId, $email);
        return $stmt->execute();
    }
}
