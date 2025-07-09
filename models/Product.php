<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Adds a new product to the database
    public function addProduct($data, $imagePath) {
        $categoryId = $this->getCategoryIdByName($data['category']);
        if (!$categoryId) {
            throw new Exception("Invalid category name: " . $data['category']);
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO products (ProductName, Description, Price, Stock, ImageURL, CategoryID, Sizes)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssdisis",
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $imagePath,
            $categoryId,
            $data['sizes']
        );
        return $stmt->execute();
    }

    // Retrieves all products with category name (for admin table)
    public function getAllProducts() {
        $query = "
            SELECT 
                products.*, 
                categories.CategoryName 
            FROM products 
            JOIN categories ON products.CategoryID = categories.CategoryID
        ";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Retrieves a single product by its ID (with category)
    public function getProductById(int $id): ?array {
        $sql = "
            SELECT 
              p.*,
              c.CategoryName
            FROM products AS p
            JOIN categories AS c
              ON p.CategoryID = c.CategoryID
            WHERE p.ProductID = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    // Returns CategoryID for a given category name
    public function getCategoryIdByName($categoryName) {
        $stmt = $this->conn->prepare("SELECT CategoryID FROM categories WHERE CategoryName = ?");
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['CategoryID'] : null;
    }

    // Updates an existing product (with or without image)
    public function updateProduct(
        int $id,
        string $name,
        string $description,
        float $price,
        string $categoryName,
        string $sizes,
        int $stock,
        string $imageURL = null
    ): bool {
        // look up CategoryID
        $categoryId = $this->getCategoryIdByName($categoryName);
        if (!$categoryId) {
            throw new Exception("Invalid category name: {$categoryName}");
        }

        // build the SQL and bind params depending on image
        if ($imageURL !== null) {
            $sql = "
                UPDATE products
                   SET ProductName  = ?,
                       Description  = ?,
                       Price        = ?,
                       Stock        = ?,
                       CategoryID   = ?,
                       Sizes        = ?,
                       ImageURL     = ?
                 WHERE ProductID   = ?
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssdiissi",
                 $name, $description, $price, $stock, $categoryId, $sizes, $imageURL, $id
            );
        } else {
            $sql = "
                UPDATE products
                   SET ProductName  = ?,
                       Description  = ?,
                       Price        = ?,
                       Stock        = ?,
                       CategoryID   = ?,
                       Sizes        = ?
                 WHERE ProductID   = ?
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssdiisi",
                 $name, $description, $price, $stock, $categoryId, $sizes, $id
            );
        }

        return $stmt->execute();
    }

    // Deletes a product and also removes from any wishlists (FK safe)
    public function deleteProduct(int $id): bool {
        // 1) Remove from wishlist first (to satisfy FK constraints)
        $stmt = $this->conn->prepare("DELETE FROM wishlist WHERE ProductID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // 2) Now delete the product itself
        $stmt = $this->conn->prepare("DELETE FROM products WHERE ProductID = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    // Returns latest N products (for homepage / related items)
    public function getLatestProducts(int $limit = 5): array {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.CategoryName
               FROM products p
               JOIN categories c ON p.CategoryID = c.CategoryID
              ORDER BY p.ProductID DESC
              LIMIT ?"
        );
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // Retrieves products of a given category with pagination (limit+offset)
    public function getProductsByCategory(string $categoryName, int $limit, int $offset): array {
        $sql = "
          SELECT p.*, c.CategoryName
            FROM products AS p
       LEFT JOIN categories AS c ON p.CategoryID = c.CategoryID
           WHERE c.CategoryName = ?
        LIMIT ?, ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $categoryName, $offset, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // Returns total count of products in a given category
    public function getTotalProductsByCategory(string $categoryName): int {
        $sql = "
          SELECT COUNT(*) AS cnt
            FROM products AS p
       LEFT JOIN categories AS c ON p.CategoryID = c.CategoryID
           WHERE c.CategoryName = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)$row['cnt'];
    }

    // Retrieves a product by name (used for duplicate name/size check)
    public function getProductByName(string $name): ?array {
        $stmt = $this->conn->prepare("
            SELECT p.*, c.CategoryName
              FROM products AS p
         LEFT JOIN categories AS c ON p.CategoryID = c.CategoryID
             WHERE p.ProductName = ?
             LIMIT 1
        ");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?: null;
    }

    // Returns per-size stock levels for a product (if using size-based stock table)
    public function getStockBySize(int $productId): array {
        $stmt = $this->conn->prepare("
          SELECT Size, Stock 
            FROM product_sizes
           WHERE ProductID = ?
        ");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $res = $stmt->get_result();
        $out = [];
        while ($row = $res->fetch_assoc()) {
            $out[$row['Size']] = (int)$row['Stock'];
        }
        return $out;
    }

    // Returns total stock for a product
    public function getStock(int $productId): int {
        $stmt = $this->conn->prepare("SELECT Stock FROM products WHERE ProductID = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($stock);
        $stmt->fetch();
        return (int)$stock;
    }

    // Decreases product stock by qty (used when customer orders)
    public function decrementStock(int $productId, int $qty): bool {
        $stmt = $this->conn->prepare(
            "UPDATE products
               SET Stock = GREATEST(Stock - ?, 0)
             WHERE ProductID = ?"
        );
        $stmt->bind_param("ii", $qty, $productId);
        return $stmt->execute();
    }
    
}
