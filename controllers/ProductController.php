<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class ProductController {
    private $db;
    private $model;
    private $userModel;    

    public function __construct($conn) {
        $this->db = $conn;

        $this->model = new Product($this->db);
        $this->userModel = new User($this->db);    
    }

    

public function index() {
    // 1. Get all products to display in the table
    $products = $this->model->getAllProducts();

    // 2. Prepare modal states and data
    $showAddModal  = false;
    $showEditModal = false;
    $editProduct   = null;
    $deleteSuccess = (isset($_GET['deleted']) && $_GET['deleted'] === '1');

    // 3. Check if there's any form action submitted
    $action = $_POST['action'] ?? '';

    // 4. Show ADD modal if user clicked the ADD button
    if ($action === 'add') {
        $showAddModal = true;
    }

    // 5. Handle POST actions for UPDATE and DELETE
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($action === 'update' && !empty($_POST['selected'][0])) {
            $id            = (int) $_POST['selected'][0];
            $editProduct   = $this->model->getProductById($id);
            $showEditModal = (bool) $editProduct;
        }

        if ($action === 'delete') {
            if (!empty($_POST['selected'])) {
                foreach ($_POST['selected'] as $rawId) {
                    $id = (int) $rawId;
                    $this->model->deleteProduct($id);
                }
            }
            header("Location: /STREETFORM/public/index.php?action=admin_products&deleted=1");
            exit;
        }
    }

    // 6. Render the view
    include __DIR__ . '/../views/admin/productmanagement.php';
}


public function save(): void
{
    // 1) Collect & sanitize inputs
    $name        = trim($_POST['product_name']        ?? '');
    $description = trim($_POST['product_description'] ?? '');
    $price       = trim($_POST['product_price']       ?? '');
    $stock       = trim($_POST['product_quantity']    ?? '');
    $category    = trim($_POST['product_category']    ?? '');
    $sizes       = trim($_POST['product_sizes']       ?? '');

    // Turn sizes into an array of codes: ["XS","M","L"]
    $inputSizes = array_filter(array_map('trim', explode(',', $sizes)));

    $errors = [];

    // 2) Field-level validations (as before) …
    if ($name === '') {
        $errors[] = 'Product name is required.';
    } elseif (strlen($name) > 255) {
        $errors[] = 'Product name must be under 255 characters.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (!is_numeric($price) || (float)$price <= 0) {
        $errors[] = 'Price must be a positive number.';
    }

    if (!ctype_digit($stock) || (int)$stock <= 0) {
        $errors[] = 'Quantity must be a above 0.';
    }

    // Category lookup
    $categoryId = $this->model->getCategoryIdByName($category);
    if (!$categoryId) {
        $errors[] = 'Invalid category.';
    }

    // Sizes must be one or more of: XS, S, M, L, XL
    $allowedSizes = ['XS','S','M','L','XL'];
    if (empty($inputSizes)) {
        $errors[] = 'Sizes field is required.';
    } else {
        foreach ($inputSizes as $sz) {
            if (!in_array($sz, $allowedSizes, true)) {
                $errors[] = 'Sizes must be comma-separated from: XS, S, M, L, XL (uppercase).';
                break;
            }
        }
    }

    // 3) Duplicate + Size‐overlap check
    // Fetch any existing product with that name
    $existing = $this->model->getProductByName($name);
    if ($existing) {
        // explode its sizes
        $existingSizes = array_map('trim', explode(',', $existing['Sizes']));
        // see if any new size already exists
        foreach ($inputSizes as $sz) {
            if (in_array($sz, $existingSizes, true)) {
                $errors[] = "Product “{$name}” with size “{$sz}” already exists (current stock: {$existing['Stock']}).";
                break;
            }
        }
    }

    // Image validations (type only)
    if (empty($_FILES['product_image']['name'])) {
        $errors[] = 'Product image is required.';
    } else {
        $tmp      = $_FILES['product_image']['tmp_name'];
        $fileType = mime_content_type($tmp);
        $allowed  = ['image/jpeg','image/png','image/gif'];
        if (!in_array($fileType, $allowed, true)) {
            $errors[] = 'Image must be JPG, PNG, or GIF.';
        }
    }

    // 4) If errors, re‐show the Add modal
    if (!empty($errors)) {
        $products      = $this->model->getAllProducts();
        $showAddModal  = true;
        $showEditModal = false;
        $editProduct   = null;
        $deleteSuccess = false;
        include __DIR__ . '/../views/admin/productmanagement.php';
        return;
    }

    // 5) No errors → handle image upload
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $filename   = time() . '_' . basename($_FILES['product_image']['name']);
    $targetFile = $uploadDir . $filename;
    $imagePath  = '';
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile;
    }

    // 6) Insert
    $data = [
        'name'        => $name,
        'description' => $description,
        'price'       => (float)$price,
        'stock'       => (int)$stock,
        'category'    => $category,
        'sizes'       => implode(',', $inputSizes),
    ];
    $this->model->addProduct($data, $imagePath);

    // 7) Redirect
    header("Location: /STREETFORM/public/index.php?action=admin_products&added=1");
    exit;
}

       public function updateSave(): void {
    // gather data
    $id          = (int)$_POST['product_id'];
    $name        = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['product_description'] ?? '');
    $price       = trim($_POST['product_price'] ?? '');
    $stock       = trim($_POST['product_quantity'] ?? '');
    $category    = trim($_POST['product_category'] ?? '');
    $sizesInput  = trim($_POST['product_sizes'] ?? '');

    $inputSizes = array_filter(array_map('trim', explode(',', $sizesInput)));
    $errors = [];

    // same validations as save()

    if ($name === '') {
        $errors[] = 'Product name is required.';
    } elseif (strlen($name) > 255) {
        $errors[] = 'Product name must be under 255 characters.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (!is_numeric($price) || (float)$price <= 0) {
        $errors[] = 'Price must be a positive number.';
    }

    if (!ctype_digit($stock) || (int)$stock <= 0) {
        $errors[] = 'Quantity must be above 0.';
    }

    $categoryId = $this->model->getCategoryIdByName($category);
    if (!$categoryId) {
        $errors[] = 'Invalid category.';
    }

    $allowedSizes = ['XS','S','M','L','XL'];
    if (empty($inputSizes)) {
        $errors[] = 'Sizes field is required.';
    } else {
        foreach ($inputSizes as $sz) {
            if (!in_array($sz, $allowedSizes, true)) {
                $errors[] = 'Sizes must be comma-separated from: XS, S, M, L, XL (uppercase).';
                break;
            }
        }
    }

    // Duplicate check (skip self)
    $existing = $this->model->getProductByName($name);
    if ($existing && (int)$existing['ProductID'] !== $id) {
        $existingSizes = array_map('trim', explode(',', $existing['Sizes']));
        foreach ($inputSizes as $sz) {
            if (in_array($sz, $existingSizes, true)) {
                $errors[] = "Product “{$name}” with size “{$sz}” already exists (current stock: {$existing['Stock']}).";
                break;
            }
        }
    }

    // Image validation (optional)
    if (!empty($_FILES['product_image']['name'])) {
        $tmp      = $_FILES['product_image']['tmp_name'];
        $fileType = mime_content_type($tmp);
        $allowed  = ['image/jpeg','image/png','image/gif'];
        if (!in_array($fileType, $allowed, true)) {
            $errors[] = 'Image must be JPG, PNG, or GIF.';
        }
    }

    // If errors → re-show edit modal
    if (!empty($errors)) {
        $products      = $this->model->getAllProducts();
        $showAddModal  = false;
        $showEditModal = true;
        $editProduct   = $this->model->getProductById($id);
        $deleteSuccess = false;
        include __DIR__ . '/../views/admin/productmanagement.php';
        return;
    }

    // Handle image upload if valid
    if (!empty($_FILES['product_image']['name'])) {
        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fn = time() . '_' . basename($_FILES['product_image']['name']);
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadDir . $fn)) {
            $imagePath = 'uploads/' . $fn;
        }
    }

    // Save update
    $this->model->updateProduct(
        $id,
        $name,
        $description,
        (float)$price,
        $category,
        implode(',', $inputSizes),
        (int)$stock,
        $imagePath ?? null
    );

    // Redirect
    header("Location: /STREETFORM/public/index.php?action=admin_products");
    exit;
}


public function womens(): void
{
    // pagination
    $page       = max(1, (int)($_GET['page'] ?? 1));
    $limit      = 6;
    $offset     = ($page - 1) * $limit;

    // fetch data
    $products   = $this->model->getProductsByCategory('Women', $limit, $offset);
    $totalCount = $this->model->getTotalProductsByCategory('Women');
    $totalPages = (int)ceil($totalCount / $limit);

    // define the variables your view expects:
    $title = "Women's Collection";
    $pages = $totalPages;

    // render the view (note the corrected path)
    include __DIR__ . '/../views/product/women.php';
}

public function all(): void
{
    // 1) pull every product
    $products = $this->model->getAllProducts();

    // 2) page title
    $title = "All Products";

    // 3) render the view
    include __DIR__ . '/../views/product/all.php';
}

public function mens(): void
{
    // pagination
    $page       = max(1, (int)($_GET['page'] ?? 1));
    $limit      = 6;
    $offset     = ($page - 1) * $limit;

    // fetch data
    $products   = $this->model->getProductsByCategory('Men', $limit, $offset);
    $totalCount = $this->model->getTotalProductsByCategory('Men');
    $totalPages = (int)ceil($totalCount / $limit);

    // define the variables your view expects:
    $title = "Men's Collection";
    $pages = $totalPages;


    // 3) render the view
    include __DIR__ . '/../views/product/men.php';
}

public function view(): void {
        // 1) Ensure session (for login state & banner flag)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2) Validate & fetch product
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id < 1) {
            header('Location: index.php?action=all');
            exit;
        }
        $product = $this->model->getProductById($id);
        if (!$product) {
            header('Location: index.php?action=all');
            exit;
        }

        // 3) “You might also like” → latest 4
        $related = $this->model->getLatestProducts(4);

        // 4) Size logic
        $sizes        = array_filter(array_map('trim', explode(',', $product['Sizes'])));
        $productSizes = $sizes;
        // All those share the same stock per your current schema:
        $sizeStocks   = array_fill_keys($sizes, (int)$product['Stock']);
        $allSizes     = ['XS','S','M','L','XL'];

        // 5) Which size did the user click?
        $selectedSize  = $_GET['size'] ?? '';
        $selectedStock = null;
        if ($selectedSize !== '' && isset($sizeStocks[$selectedSize])) {
            $selectedStock = $sizeStocks[$selectedSize];
        }

        // 6) Login‐first banner?
        $loginFirst  = isset($_GET['login_first']) && $_GET['login_first'] === '1';

        // 7) URL to return to after login
        $redirectUrl = $_SERVER['REQUEST_URI'];

        // 8) Is customer registered (logged in)?
        // Adjust this to whatever flag you set on login
        $isRegistered = !empty($_SESSION['customer_email']);

        // 9) Hand off to the view
        require __DIR__ . '/../views/product/view.php';
    }


    public function homepage(): void {
    $latestItems = $this->model->getLatestProducts(5);
    include __DIR__ . '/../views/home/homepage.php';
}

}
