<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/WishlistController.php';


// 3) Instantiate
$productController = new ProductController($conn);
$cartController    = new CartController($conn);
$authController = new AuthController($conn); 
$adminController = new AdminController($conn);
$homeController = new HomeController($conn);
$orderController   = new OrderController($conn);
$userController = new UserController($conn);
$wishlistController = new WishlistController($conn);


// 4) Dispatch
$action = $_REQUEST['action'] ?? 'index';
$email = $_SESSION['customer_email'] ?? null;
$wishlistCount = 0;

if ($email) {
    require_once __DIR__ . '/../models/Wishlist.php';
    $wm = new Wishlist($conn);
    $wishlistCount = count($wm->getItems($email));
}


switch ($action) {
    // ─── Authentication ──────────────────────
    case 'adminlogin':
        $authController->adminlogin(); 
        break;
    case 'admindashboard':
        $adminController->dashboard();
        break;
    case 'customerlogin':
        $authController->customerlogin(); 
        break;
    case 'register':
        $authController->register();
        break;
    case 'guest':
        $authController->guest();
        break;    
    case 'logout_admin':
        $authController->logoutAdmin();
        break;    
    case 'logout_customer':
        $authController->logoutCustomer();
        break;

    // ─── Cart operations ─────────────────────
    case 'add_to_cart':
        $cartController->addToCart();
        break;
    case 'remove_from_cart':
        $cartController->removeItem();
        break;
    case 'update_cart':
        $cartController->updateQuantity();
        break;
    case 'cart':
        $cartController->cart();
        break;

    // ─── Wishlist ─────────────────────────────
    case 'add_to_wishlist':
        $wishlistController->add();
        break;
    case 'remove_from_wishlist':
        $wishlistController->remove();
        break;
    case 'wishlist':
        $wishlistController->view();
        break;


    // ─── CHECKOUT & ORDER PROCESSING ─────────────────────
    case 'checkout':
        $orderController->checkout();
        break;
    case 'process_payment':           
        $orderController->process();
        break;

    // ─── Admin: Order Management ─────────────────────
    case 'orders':
    case 'show_status_modal':         
        $orderController->orders();
        break;
    case 'change_order_status':
        $orderController->changeOrderStatus();
        break;

    // ─── Admin: Customer Management ─────────────────────    
    case 'customers':
        $adminController->customers();
        break;
    case 'toggle_customer_status':
        $adminController->toggleCustomerStatus();
        break;
    case 'delete_customer':
        $adminController->deleteCustomer();
        break;

    // ─── Public Catalog ──────────────────────
    case 'view':
        $productController->view();
        break;
    case 'homepage':
        $homeController->homepage();
        break;    
    case 'mens':
        $productController->mens();
        break;
    case 'womens':
        $productController->womens();
        break;
    case 'all':
        $productController->all();
        break;
        
    // ─── Customer Account ─────────────────────
    case 'account':
        $userController->account();
        break;
    case 'cancel_order':
        $userController->cancelOrder();
        break;

    // ─── Admin CRUD ───────────────────────────
    case 'admin_products':
        $productController->index();
        break;
    case 'add':
        $productController->index();
        break;
    case 'save':
        $productController->save();
        break;
    case 'update_save':
        $productController->updateSave();
        break;
    case 'delete':
    case 'update':
        $productController->index();
        break;

    // ─── Default ──────────────────────────────
    case 'index':
    default:
        $homeController->homepage();
        break;
}


ob_end_flush();
?>