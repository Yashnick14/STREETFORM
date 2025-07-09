<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>StreetForm - Women's Collection</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-white text-gray-800 font-poppins">

  <!-- Header Section -->
  <?php include '../views/partials/header.php'; ?>

  
<?php if (isset($_GET['login_first']) && $_GET['login_first'] == 1): ?>
  <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 text-center font-semibold rounded">
    ⚠️ Please login to add items to your cart.
  </div>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_wishlist'])): ?>
  <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 text-center font-semibold rounded">
    <?= htmlspecialchars($_SESSION['flash_wishlist']) ?>
  </div>
  <?php unset($_SESSION['flash_wishlist']); ?>
<?php endif; ?>


 <main class="max-w-screen-xl mx-auto py-8 px-4 space-y-8">

    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-2">
      <?=htmlspecialchars($product['CategoryName'])?> /
    </nav>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-8 items-start">
      <!-- Image -->
      <div class="md:col-span-2 p-6 h-[450px] flex items-center justify-center">
        <img
          src="/STREETFORM/public/<?=htmlspecialchars($product['ImageURL'])?>"
          alt="<?=htmlspecialchars($product['ProductName'])?>"
          class="h-full w-auto object-cover"
        />
      </div>

      <!-- Info -->
      <div class="md:col-span-3 space-y-6">
        <h1 class="text-2xl font-bold"><?=htmlspecialchars($product['ProductName'])?></h1>
        <p class="text-2xl font-semibold">$<?=number_format($product['Price'],2)?></p>
        <p class="text-base text-gray-600"><?=htmlspecialchars($product['Color']??'')?></p>

        <!-- Size form -->
        <form method="get" action="/STREETFORM/public/index.php?action=view" class="space-y-4">
          <input type="hidden" name="action" value="view">
          <input type="hidden" name="id"     value="<?=$product['ProductID']?>">
          <div class="grid grid-cols-5 gap-2">
            <?php foreach($allSizes as $sz):
              $avail = in_array($sz, $productSizes, true);
              $sel   = $sz === $selectedSize;
              $cls   = $sel
                ? 'bg-black text-white'
                : ($avail
                    ? 'border border-gray-300 text-gray-700 hover:bg-gray-100'
                    : 'bg-gray-100 text-gray-400 cursor-not-allowed');
            ?>
              <button
                type="submit"
                name="size"
                value="<?=$sz?>"
                class="px-4 py-2 rounded text-sm <?=$cls?>"
                <?=$avail?'':'disabled'?>
                title="<?=$avail?"{$sizeStocks[$sz]} in stock":'Out of stock'?>"
              ><?=$sz?></button>
            <?php endforeach;?>
          </div>
        </form>

        <!-- Stock message -->
        <?php if($selectedSize): ?>
          <p class="text-sm text-gray-700">
            <?=$selectedStock>0
               ? "{$selectedStock} items left"
               : 'Out of stock'?>
          </p>
        <?php else: ?>
          <p class="text-sm text-gray-700">Please select a size</p>
        <?php endif; ?>

       <div class="flex items-center space-x-4">
  <!-- Add to Cart -->
  <form method="POST" action="/STREETFORM/public/index.php?action=add_to_cart">
    <input type="hidden" name="product_id" value="<?= $product['ProductID'] ?>">
    <input type="hidden" name="size" value="<?= htmlspecialchars($selectedSize) ?>">
    <?php if ($isRegistered): ?>
      <button
        type="submit"
        class="bg-black text-white px-6 py-2 rounded font-semibold hover:bg-gray-800 disabled:opacity-50"
        <?= ($selectedStock ?? 0) <= 0 ? 'disabled' : '' ?>
      >Add To Cart</button>
    <?php else: ?>
      <button
        type="button"
        onclick="window.location='index.php?action=login&redirect=<?= $redirectUrl ?>&login_first=1'"
        class="bg-black text-white px-6 py-2 rounded font-semibold hover:bg-gray-800"
      >Add To Cart</button>
    <?php endif; ?>
  </form>

  <!-- Add to Favorites -->
  <form method="POST" action="/STREETFORM/public/index.php?action=add_to_wishlist" class="inline">
    <input type="hidden" name="product_id" value="<?= $product['ProductID'] ?>">
    <?php if ($isRegistered): ?>
      <button
        type="submit"
        title="Add to Favorites"
        class="text-2xl text-gray-400 hover:text-red-600 focus:outline-none"
      >
        <i class="far fa-heart"></i>
      </button>
    <?php else: ?>
      <button
        type="button"
        onclick="window.location='index.php?action=login&redirect=<?= $redirectUrl ?>&login_first=1'"
        title="Add to Favorites"
        class="text-2xl text-gray-400 hover:text-red-600 focus:outline-none"
      >
        <i class="far fa-heart"></i>
      </button>
    <?php endif; ?>
  </form>
</div>

        <!-- Description -->
        <div>
          <h2 class="text-lg font-semibold mb-2">Product Description</h2>
          <p class="text-gray-700"><?=nl2br(htmlspecialchars($product['Description']))?></p>
        </div>
      </div>
    </div>
<br><br><br>
    <!-- Related (unchanged) -->
    <section>
      <h3 class="text-xl font-bold mb-4">YOU MIGHT ALSO LIKE</h3>
      <div class="relative">
        <div class="flex overflow-x-auto no-scrollbar pb-6 -mx-4 px-4">
          <?php foreach($related as $r): ?>
            <div class="min-w-[250px] flex-shrink-0 mx-2 border border-gray-200 rounded-md overflow-hidden transition-shadow hover:shadow-lg">
              <a href="index.php?action=view&id=<?=(int)$r['ProductID']?>" class="block">
                <img
                  src="/STREETFORM/public/uploads/<?=htmlspecialchars(basename($r['ImageURL']))?>"
                  alt="<?=htmlspecialchars($r['ProductName'])?>"
                  class="w-full h-64 object-cover"
                />
                <div class="p-4 space-y-1">
                  <h4 class="text-sm font-semibold text-gray-900"><?=htmlspecialchars($r['ProductName'])?></h4>
                  <p class="text-xs text-gray-500"><?=htmlspecialchars($r['CategoryName'])?></p>
                  <p class="text-sm font-bold text-gray-900">$<?=number_format($r['Price'],2)?></p>
                </div>
              </a>
            </div>
          <?php endforeach;?>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer Section -->
  <?php include '../views/partials/footer.php'; ?>