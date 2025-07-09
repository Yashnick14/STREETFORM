<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private User $userModel; 


    public function __construct(mysqli $conn) {
        $this->userModel = new User($conn);
    }
    
     //---------- ADMIN LOGIN ----------//

    public function adminlogin(): void {
        // If not POST, display the admin login form
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../views/auth/adminlogin.php';
            return;
        }

        // Collect and sanitize inputs
        $email    = trim($_POST['email']    ?? '');
        $password =            $_POST['password'] ?? '';

        // Start session if none exists
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Validate credentials via User model
        if ($this->userModel->login($email, $password, 'admin')) {
            // On success, mark admin as logged in
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['email']           = $email;

            // Redirect to dashboard
            header('Location: index.php?action=admindashboard');
            exit;
        } else {
            // On failure, redirect back with error flag
            header('Location: index.php?action=adminlogin&error=invalid_credentials');
            exit;
        }
    }

    //------------ CUSTOMER LOGIN ------------//

    public function customerlogin(): void {
        // If not POST, display the customer login form
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../views/auth/customerlogin.php';
            return;
        }

        // Collect and sanitize inputs
        $email    = trim($_POST['email']    ?? '');
        $password =            $_POST['password'] ?? '';

        // Start session if none exists
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lookup customer by email
        $customer = $this->userModel->getByEmail($email);

        // Verify password hash
        if ($customer && password_verify($password, $customer['PasswordHash'])) {
            // On success, set session flags and IDs
            $_SESSION['customer_logged_in'] = true;
            $_SESSION['customer_id']        = $customer['CustomerID'];
            $_SESSION['email']              = $customer['Email'];
            $_SESSION['customer_email']     = $customer['Email'];

            // Redirect to homepage
            header('Location: index.php?action=homepage');
            exit;
        } else {
            // On failure, redirect back with mismatch error
            header('Location: index.php?action=customerlogin&error=mismatch');
            exit;
        }
    }
  
    //------------ CUSTOMER REGISTRATION ------------//

    public function register(): void {
        // 1. If not POST, display the registration form
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        // 2. Collect and sanitize inputs
        $username = trim($_POST['username']         ?? '');
        $email    = trim($_POST['email']            ?? '');
        $password =            $_POST['password']   ?? '';
        $confirm  =            $_POST['confirm_password'] ?? '';
        $error    = ''; // To track validation errors

        // 3. Basic validation checks
        if (empty($username) || empty($email) || empty($password)) {
            $error = 'empty';                // Missing fields
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'invalid_email';        // Bad email format
        } elseif ($password !== $confirm) {
            $error = 'mismatch';             // Passwords don’t match
        } else {
            // 4. Check for existing email
            $existing = $this->userModel->getByEmail($email);
            if ($existing) {
                $error = 'exists';           // Email already registered
            } else {
                // 5. Hash password and register user
                $hash    = password_hash($password, PASSWORD_DEFAULT);
                $created = $this->userModel->register($username, $email, $hash);

                if ($created) {
                    // On success, redirect to customer login
                    header('Location: index.php?action=customerlogin&registered=1');
                    return;
                } else {
                    $error = 'db';// Database insertion failure
                }
            }
        }

        // 6. If any error, redirect back with error type
        header("Location: index.php?action=register&error=$error");
    }


    //=---------- GUEST ACCESS ------------//

    public function guest(): void {
        // 1. Start session if none exists
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Clear customer-related session data
        unset($_SESSION['customer_logged_in']);
        unset($_SESSION['customer_id']);
        unset($_SESSION['email']);

        // 3. Redirect to homepage
        header('Location: index.php?action=homepage');
        exit;
    }

    public function logoutAdmin(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: index.php?action=adminlogin');
        exit;
    }

        public function logoutCustomer(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: index.php?action=customerlogin');
        exit;
    }
}
