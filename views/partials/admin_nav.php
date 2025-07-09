<!-- views/partials/admin_nav.php -->
<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<header class="h-16 bg-black flex items-center justify-between px-6">
  <img src="/STREETFORM/public/assets/images/Logo.png" alt="Streetform" class="h-8 mx-auto">
<a href="/STREETFORM/public/index.php?action=logout_admin"
           class="text-white text-2xl hover:text-gray-300"
           title="Logout"
        >⏻</a> 
</header>

<div class="flex flex-1">
  <aside class="w-48 bg-gray-100 shadow-md min-h-screen">
    <nav class="mt-6 space-y-2">
      <a href="index.php?action=admindashboard"
         class="block px-6 py-3 <?= ($_REQUEST['action'] ?? '')==='admindashboard' ? 'bg-white font-medium border-l-4 border-black' : 'hover:bg-gray-200' ?>">
        Home
      </a>
      <a href="index.php?action=admin_products"
         class="block px-6 py-3 <?= ($_REQUEST['action'] ?? '')==='admin_products' ? 'bg-white font-medium border-l-4 border-black' : 'hover:bg-gray-200' ?>">
        Products
      </a>
      <a href="index.php?action=orders"
         class="block px-6 py-3 <?= ($_REQUEST['action'] ?? '')==='orders' ? 'bg-white font-medium border-l-4 border-black' : 'hover:bg-gray-200' ?>">
        Orders
      </a>
      <a href="index.php?action=customers"
         class="block px-6 py-3 <?= ($_REQUEST['action'] ?? '')==='customers' ? 'bg-white font-medium border-l-4 border-black' : 'hover:bg-gray-200' ?>">
        Customers
      </a>
    </nav>
  </aside>

