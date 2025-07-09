<?php
// app/models/Wishlist.php

class Wishlist
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * Add a product to the wishlist for the given customer email.
     * Returns false if it was already in the wishlist.
     */
    public function addItem(string $email, int $productId): bool
    {
        // 1) check for existing
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM wishlist 
             WHERE CustomerEmail = ? 
               AND ProductID     = ?"
        );
        $stmt->bind_param("si", $email, $productId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            return false;
        }

        // 2) insert new row
        $stmt = $this->db->prepare(
            "INSERT INTO wishlist (CustomerEmail, ProductID)
             VALUES (?, ?)"
        );
        $stmt->bind_param("si", $email, $productId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Remove a product from the wishlist for the given email.
     */
    public function removeItem(string $email, int $productId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM wishlist 
             WHERE CustomerEmail = ? 
               AND ProductID     = ?"
        );
        $stmt->bind_param("si", $email, $productId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Fetch all wishlisted products for a given email.
     * Returns an array of product rows.
     */
    public function getItems(string $email): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.* 
               FROM wishlist w
               JOIN products p ON w.ProductID = p.ProductID
              WHERE w.CustomerEmail = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res   = $stmt->get_result();
        $items = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $items;
    }
}
