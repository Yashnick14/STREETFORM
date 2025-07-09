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

<main class="max-w-screen-xl mx-auto py-12 px-4">
  <!-- Page title -->
  <h1 class="text-3xl font-semibold text-center mb-8">
    <?= htmlspecialchars($title) ?>
  </h1>

  <!-- Product grid -->
  <?php if (!empty($products)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($products as $p): ?>
        <div class="border border-gray-200 rounded-md overflow-hidden transition-shadow duration-200 hover:shadow-lg">
          <a href="index.php?action=view&id=<?= (int)$p['ProductID'] ?>">
            <img
              src="/STREETFORM/public/uploads/<?= htmlspecialchars(basename($p['ImageURL'])) ?>"
              alt="<?= htmlspecialchars($p['ProductName']) ?>"
              class="w-full h-72 object-cover"
            >
          </a>
          <div class="px-4 py-3">
            <h3 class="text-sm font-semibold text-gray-900 leading-tight">
              <?= htmlspecialchars($p['ProductName']) ?>
            </h3>
            <p class="mt-1 text-xs text-gray-500">
              <?= htmlspecialchars($p['CategoryName']) ?>
            </p>
            <p class="mt-2 text-sm font-bold text-gray-900">
              $<?= number_format($p['Price'], 2) ?>
            </p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-center text-gray-600">No products found in the Men’s category.</p>
  <?php endif; ?>

  <!-- Pagination -->
  <div class="mt-12 flex justify-center items-center space-x-4 text-gray-600">
    <a
      href="index.php?action=<?= urlencode($_GET['action']) ?>&page=<?= max(1, $page - 1) ?>"
      class="hover:text-gray-800"
    >← Previous</a>

    <?php for ($i = 1; $i <= $pages; $i++): ?>
      <?php if ($i === $page): ?>
        <span class="px-3 py-1 rounded-full bg-gray-800 text-white"><?= $i ?></span>
      <?php else: ?>
        <a
          href="index.php?action=<?= urlencode($_GET['action']) ?>&page=<?= $i ?>"
          class="px-3 py-1 rounded-full hover:bg-gray-200"
        ><?= $i ?></a>
      <?php endif; ?>
    <?php endfor; ?>

    <a
      href="index.php?action=<?= urlencode($_GET['action']) ?>&page=<?= min($pages, $page + 1) ?>"
      class="hover:text-gray-800"
    >Next →</a>
  </div>
</main>


  <!-- CTA Banner Inside Hero Section -->
  <div class=" text-black py-6 text-center w-full bg-yellow-300">
    <div class="container mx-auto px-4">
      <p class="text-xl font-bold mb-4">BECOME A MEMBER & GET 10% OFF</p>
      <a href="/STREETFORM/views/auth/register.php" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800">SIGN UP FOR FREE</a>
    </div>
  </div>
</section>


  <!-- Footer Section -->
  <?php include '../views/partials/footer.php'; ?>