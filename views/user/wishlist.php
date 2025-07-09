<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>My Account – StreetForm</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 font-poppins text-gray-800 flex flex-col min-h-screen">

  <!-- Header -->
  <?php include __DIR__ . '/../partials/header.php'; ?>
  <?php include __DIR__ . '/../partials/guard_customer.php'; ?>

  <!-- Main content -->
  <main class="flex-grow w-full">
    <div class="max-w-screen-xl mx-auto py-12 px-4 min-h-[calc(100vh-200px)]">

      <!-- Wishlist Section -->
<section class="wishlist-section">
  <h2 class="text-3xl font-bold mb-8 text-gray-900 border-b pb-4">My Wishlist</h2>

  <?php if (empty($items)): ?>
    <div class="h-48 flex items-center justify-center text-center">
      <p class="text-gray-500 text-lg">Your wishlist is currently empty.</p>
    </div>
  <?php else: ?>
    <div class="wishlist-grid">
      <?php foreach ($items as $item): ?>
        <div class="wishlist-card">
          <img
            src="/STREETFORM/public/<?= htmlspecialchars($item['ImageURL']) ?>"
            alt="<?= htmlspecialchars($item['ProductName']) ?>"
            class="wishlist-image"
          >
          <div class="wishlist-info">
            <h3 class="wishlist-title"><?= htmlspecialchars($item['ProductName']) ?></h3>
            <p class="wishlist-price">$<?= number_format($item['Price'], 2) ?></p>
            <form method="post" action="/STREETFORM/public/index.php?action=remove_from_wishlist" class="mt-4">
              <input type="hidden" name="product_id" value="<?= (int)$item['ProductID'] ?>">
              <button type="submit" class="wishlist-remove-btn">
                <i class="fas fa-trash-alt"></i> Remove
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>


    </div>
  </main>

  <!-- Footer -->
  <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
