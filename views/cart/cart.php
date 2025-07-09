<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>StreetForm – Your Cart</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
</head>
<body class="bg-white text-gray-800 font-poppins">

  <!-- Header -->
  <?php include __DIR__ . '/../partials/header.php'; ?>
  <?php include __DIR__ . '/../partials/guard_customer.php'; ?>

  <main class="max-w-screen-xl mx-auto py-12 px-4 space-y-12">
    <h1 class="text-2xl font-bold">YOUR CART</h1>

    <?php if (empty($items)): ?>
      <p class="text-center text-gray-600">Your cart is currently empty.</p>
    <?php else: ?>

      <!-- Each cart item -->
      <?php foreach ($items as $item): ?>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 border border-gray-200 rounded-lg overflow-hidden">

          <!-- Image -->
          <div class="md:col-span-3 p-4 h-[350px] flex items-center justify-center">
            <img
              src="/STREETFORM/public/<?= htmlspecialchars($item['image']) ?>"
              alt="<?= htmlspecialchars($item['name']) ?>"
              class="h-full w-full object-contain object-left-top"
            />
          </div>

          <!-- Details + quantity controls -->
          <div class="md:col-span-9 p-4 relative space-y-3">
            <!-- Remove button -->
            <form
              method="post"
              action="index.php?action=remove_from_cart"
              class="absolute top-4 right-4"
            >
              <input type="hidden" name="product_id" value="<?= (int)$item['product_id'] ?>">
              <input type="hidden" name="size" value="<?= htmlspecialchars($item['size']) ?>">
              <button type="submit" class="text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
              </button>
            </form>

            <h2 class="text-xl font-semibold"><?= htmlspecialchars($item['name']) ?></h2>
            <p class="text-xl font-bold">$<?= number_format($item['price'], 2) ?></p>
            <p>Size: <?= htmlspecialchars($item['size']) ?></p>

            <!-- Quantity -->
            <form
              method="post"
              action="index.php?action=update_cart"
              class="mt-4 inline-flex items-center border border-gray-300 rounded-lg overflow-hidden"
            >
              <input type="hidden" name="product_id" value="<?= (int)$item['product_id'] ?>">
              <input type="hidden" name="size" value="<?= htmlspecialchars($item['size']) ?>">

              <!-- minus -->
              <button
                type="submit"
                name="quantity"
                value="<?= max(0, $item['quantity'] - 1) ?>"
                class="px-4 py-2 hover:bg-gray-100"
                <?= $item['quantity'] <= 0 ? 'disabled' : '' ?>
              >&minus;</button>

              <!-- current qty -->
              <span class="px-6 py-2"><?= (int)$item['quantity'] ?></span>

              <!-- plus -->
              <button
                type="submit"
                name="quantity"
                value="<?= $item['quantity'] + 1 ?>"
                class="px-4 py-2 hover:bg-gray-100 <?= $item['quantity'] >= $item['stock'] ? 'opacity-50 cursor-not-allowed' : '' ?>"
                <?= $item['quantity'] >= $item['stock'] ? 'disabled' : '' ?>
              >&plus;</button>
            </form>

            <!-- stock info -->
            <p class="text-xs text-gray-500">
              In stock: <?= (int)$item['stock'] ?>
            </p>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Order Summary & Checkout -->
      <div class="md:flex md:space-x-8">
        <div class="md:flex-1"></div>
        <div class="md:w-1/3 border border-gray-200 rounded-lg p-6 space-y-4">
          <h3 class="text-lg font-bold">ORDER SUMMARY</h3>
          <div class="flex justify-between">
            <span>SUB TOTAL</span>
            <span>$<?= number_format($subtotal, 2) ?></span>
          </div>
          <div class="flex justify-between font-bold text-lg">
            <span>TOTAL</span>
            <span>$<?= number_format($subtotal, 2) ?></span>
          </div>
          <a
            href="index.php?action=checkout"
            class="block text-center w-full bg-black text-white py-3 rounded-full font-semibold hover:bg-gray-800 transition"
          >
            CHECKOUT
          </a>
          <p class="text-xs text-gray-500 text-center">Accepted payment methods</p>
          <div class="flex justify-center space-x-3 mt-1">
            <img src="/STREETFORM/public/assets/images/PMethods.png"   alt="Payment Methods"   class="h-6">
          </div>
        </div>
      </div>

    <?php endif; ?>
  </main>

  <!-- Footer -->
  <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
