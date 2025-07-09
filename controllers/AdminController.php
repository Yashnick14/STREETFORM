<?php
require_once __DIR__ . '/../models/Product.php'; 
require_once __DIR__ . '/../models/Order.php';   
require_once __DIR__ . '/../models/User.php';    

class AdminController {
    private mysqli $db;        
    private User   $userModel; 

    public function __construct(mysqli $db) {
        $this->db        = $db;
        $this->userModel = new User($this->db);
    }


    public function dashboard(): void { 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirect to admin login if not authenticated
        if (empty($_SESSION['admin_logged_in'])) {
            header('Location: index.php?action=adminlogin');
            exit;
        }

        // Instantiate other models as needed
        $productModel  = new Product($this->db);
        $orderModel    = new Order($this->db);

        // Fetch statistics
        $totalProducts  = count($productModel->getAllProducts());
        $totalOrders    = $orderModel->getOrderCount();
        $totalCustomers = $this->userModel->getCustomerCount();

        // Load the dashboard view
        require __DIR__ . '/../views/admin/admindashboard.php';
    }


    public function customers(): void { 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_logged_in'])) {
            header('Location: index.php?action=adminlogin');
            exit;
        }

        // Determine which customers to fetch: all, active, or inactive
        $filter    = $_GET['status'] ?? 'all';
        $customers = $this->userModel->getAllCustomers($filter);

        // Load the customer management view
        require __DIR__ . '/../views/admin/customermanagement.php';
    }


    public function toggleCustomerStatus(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        // Only allow POST from a logged-in admin
        if (empty($_SESSION['admin_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=customers');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->userModel->toggleStatus($id); 
        }

        // Redirect back to the customer list
        header('Location: index.php?action=customers');
        exit;
    }


    public function deleteCustomer(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        // Only allow GET from a logged-in admin
        if (empty($_SESSION['admin_logged_in']) || $_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Location: index.php?action=customers');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->userModel->deleteCustomer($id); 
        }

        // Redirect back to the customer list
        header('Location: index.php?action=customers');
        exit;
    }
}
