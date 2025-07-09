<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userEmail = isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] ? $_SESSION['email'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>
<body>
<!-- Header -->
<header class="bg-black text-white font-poppins px-6 py-2">
  <div class="flex items-center justify-between max-w-screen-l mx-auto">
    <a href="/STREETFORM/public/index.php?action=homepage" class="block">
  <img
    src="/STREETFORM/public/assets/images/Logo.png"
    alt="Street Form Logo"
    class="h-[60px] object-contain"
  />
</a>

    <nav class="space-x-8">
      <a href="/STREETFORM/public/index.php?action=mens" class="text-white hover:text-gray-300">Men</a>
      <a href="/STREETFORM/public/index.php?action=womens" class="text-white hover:text-gray-300">Women</a>
      <a href="/STREETFORM/public/index.php?action=all" class="text-white hover:text-gray-300">All</a>
    </nav>
    <div class="flex items-center space-x-8">
      <!-- User or Login display (replacing Search) -->
    <div class="flex items-center space-x-8">
      <?php if ($userEmail): ?>
        <span class="text-sm text-green-400"><?= htmlspecialchars($userEmail) ?></span>
      <?php else: ?>
        <span class="text-sm text-gray-400"></span> <!-- guest: show nothing -->
      <?php endif; ?>
    </div>
    <!-- USER ICON: link to customer account -->
    <a href="index.php?action=account" class="text-white hover:text-gray-300">
      <img src="/STREETFORM/public/assets/images/user.png"
           alt="My Account"
           class="h-4 w-4 object-contain">
    </a>
  <a href="/STREETFORM/public/index.php?action=wishlist"
     class="relative text-white hover:text-gray-300">
    <img src="/STREETFORM/public/assets/images/cart.png"
         alt="Wishlist"
         class="h-5 w-5 object-contain">
  </a>
      <a href="/STREETFORM/public/index.php?action=logout_customer"
           class="text-white text-xl hover:text-gray-300"
           title="Logout"
        >⏻</a>
    </div>
  </div>
</header>
</body>
</html>

