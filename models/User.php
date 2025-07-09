<?php
// models/User.php

class User {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    /**
     * Login check for admin or customer.
     */
    public function login(string $email, string $password, string $role): bool {
        // ── Admin is hardcoded ─────────────────────────────────────────
        if ($role === 'admin') {
            return $email === 'admin@streetform.com'
                && $password === 'AdMin@14';
        }

        // ── Customer login ────────────────────────────────────────────
        $stmt = $this->db->prepare(
            "SELECT PasswordHash 
               FROM customers 
              WHERE Email = ? 
                AND IsActive = 1
              LIMIT 1"
        );
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res  = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['PasswordHash'])) {
            return true;
        }
        return false;
    }

    /**
     * Fetch a single customer row by email.
     */
    public function getByEmail(string $email): ?array {
        $stmt = $this->db->prepare(
            "SELECT * 
               FROM customers 
              WHERE Email = ? 
              LIMIT 1"
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc() ?: null;
        $stmt->close();
        return $row;
    }

    /**
     * Register a new customer.
     */
    public function register(string $username, string $email, string $passwordHash): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO customers 
                (Username, Email, PasswordHash, IsActive) 
             VALUES (?, ?, ?, 1)"
        );
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("sss", $username, $email, $passwordHash);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Lookup by numeric CustomerID.
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT * 
               FROM customers 
              WHERE CustomerID = ? 
              LIMIT 1"
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc() ?: null;
        $stmt->close();
        return $row;
    }

    /**
     * How many customers total.
     */
    public function getCustomerCount(): int {
        $res = $this->db->query("SELECT COUNT(*) AS cnt FROM customers");
        $row = $res->fetch_assoc();
        return (int)$row['cnt'];
    }

    /**
     * Fetch all customers, plus their order‐count and sum of spend.
     * $filter may be 'all', 'active', or 'inactive'.
     */
    public function getAllCustomers(string $filter = 'all'): array {
        $where = '';
        if ($filter === 'active')   $where = 'WHERE c.IsActive = 1';
        if ($filter === 'inactive') $where = 'WHERE c.IsActive = 0';

        // We join on Email with COLLATE to avoid charset conflicts
        $sql = "
          SELECT
            c.CustomerID,
            c.Username     AS Username,
            c.Email        AS Email,
            c.IsActive     AS IsActive,
            COUNT(o.OrderID)               AS OrdersCount,
            COALESCE(SUM(o.TotalAmount),0) AS AmountSpent
          FROM customers c
          LEFT JOIN orders o
            ON c.Email COLLATE utf8mb4_unicode_ci
             = o.CustomerEmail COLLATE utf8mb4_unicode_ci
          $where
          GROUP BY c.CustomerID
          ORDER BY c.Username
        ";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Toggle active/inactive.
     */
    public function toggleStatus(int $id): bool {
        $stmt = $this->db->prepare(
          "UPDATE customers
              SET IsActive = NOT IsActive
            WHERE CustomerID = ?"
        );
        if (!$stmt) return false;
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Delete a customer row.
     */
    public function deleteCustomer(int $id): bool {
        $stmt = $this->db->prepare(
          "DELETE FROM customers
            WHERE CustomerID = ?"
        );
        if (!$stmt) return false;
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
