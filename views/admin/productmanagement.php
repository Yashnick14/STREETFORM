<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>STREETFORM – Product Management</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>
<body class="bg-gray-100 font-poppins min-h-screen flex">
  <div class="flex-1 flex flex-col">

    <?php include __DIR__ . '/../partials/admin_nav.php'; ?>
    <?php include __DIR__ . '/../partials/guard_admin.php'; ?>

    <main class="flex-1 p-4 overflow-auto">
      <section class="bg-white rounded shadow overflow-hidden">

        <!-- ─── Single Form for ADD / UPDATE / DELETE + Table ─────────────────── -->
        <form method="POST" action="index.php?action=admin_products" class="overflow-x-auto">

          <!-- Action Bar -->
          <div class="flex items-center px-4 py-3 border-b">
            <div class="flex-1">
              <!-- empty left side to push the buttons right -->
            </div>
            <div class="flex space-x-2">
              <button
                name="action" value="add"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition"
              >ADD</button>
              <button
                name="action" value="update"
                class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded transition"
              >UPDATE</button>
              <button
                name="action" value="delete"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
              >DELETE</button>
            </div>
          </div>

          <!-- Table -->
         <table class="w-full">
  <thead class="bg-gray-50 text-gray-500 text-sm">
    <tr>
      <th class="w-12 px-4 py-4"></th>
      <th class="px-4 py-4 text-center align-middle">Product</th>
      <th class="pl-8 pr-4 py-4 text-left align-middle">Name</th>
      <th class="px-4 py-4 text-left">Sizes</th>
      <th class="px-4 py-4 text-left">Price</th>
      <th class="px-4 py-4 text-left">Inventory</th>
      <th class="px-4 py-4 text-left">Category</th>
    </tr>
  </thead>
  <tbody class="text-sm">
    <?php foreach($products as $p): ?>
    <tr class="border-b hover:bg-gray-50">
      <!-- checkbox -->
      <td class="px-4 py-4 align-middle text-center">
        <input
          type="checkbox"
          name="selected[]"
          value="<?= (int)$p['ProductID'] ?>"
          class="rounded border-gray-300"
        >
      </td>

      <!-- image, centered horizontally & vertically -->
      <td class="px-4 py-4 align-middle text-center">
        <img
          src="<?= htmlspecialchars($p['ImageURL']) ?>"
          alt=""
          class="h-16 w-16 object-cover rounded inline-block"
        >
      </td>

      <!-- product name, vertically centered -->
      <td class="pl-8 pr-4 py-4 align-middle"><?= htmlspecialchars($p['ProductName']) ?></td>

      <!-- sizes -->
      <td class="px-4 py-4 align-middle"><?= htmlspecialchars($p['Sizes']) ?></td>

      <!-- price -->
      <td class="px-4 py-4 align-middle">
        $<?= number_format($p['Price'],2) ?>
      </td>

      <!-- inventory -->
      <td class="px-4 py-4 align-middle">
        <span class="font-semibold
          <?= $p['Stock']===0
              ? 'text-red-500'
              : ($p['Stock']<10?'text-yellow-500':'text-green-500')
          ?>"
        >
          <?= (int)$p['Stock'] ?> left
        </span>
      </td>

      <!-- category -->
      <td class="px-4 py-4 align-middle"><?= htmlspecialchars($p['CategoryName']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
        </form>
        <!-- ──────────────────────────────────────────────────────────────────── -->

        <!-- ADD Modal -->
        <?php if ($showAddModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
          <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Add New Product</h2>
            <?php if (!empty($errors)): ?>
              <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm space-y-1">
                  <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
            <form method="POST" action="index.php?action=save" enctype="multipart/form-data">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image Upload -->
                <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6">
                  <label for="product_image" class="cursor-pointer flex flex-col items-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <p class="text-sm text-center">Drag-drop your image or click to browse</p>
                  </label>
                  <input type="file" name="product_image" id="product_image" accept="image/*" class="mt-4 text-gray-400 text-sm text-center block">
                </div>
                <!-- Fields -->
                <div class="space-y-3">
                  <input type="text" name="product_name" placeholder="Product Name" class="w-full border border-gray-300 rounded px-4 py-2">
                  <textarea name="product_description" rows="3" placeholder="Description" class="w-full border border-gray-300 rounded px-4 py-2"></textarea>
                  <div class="flex">
                    <input type="number" name="product_price" placeholder="Price" class="w-full border border-gray-300 rounded-l px-4 py-2">
                    <span class="bg-gray-200 px-3 py-2 border border-gray-300 border-l-0 rounded-r text-gray-600">USD</span>
                  </div>
                  <input type="text" name="product_category" placeholder="Category" class="w-full border border-gray-300 rounded px-4 py-2">
                  <input type="text" name="product_sizes" placeholder="Sizes (e.g. S,M,L)" class="w-full border border-gray-300 rounded px-4 py-2">
                  <input type="number" name="product_quantity" placeholder="Quantity" class="w-full border border-gray-300 rounded px-4 py-2">
                </div>
              </div>
              <div class="flex justify-end mt-6 space-x-2">
                <a href="index.php?action=admin_products" class="px-4 py-2 border border-gray-400 rounded hover:bg-gray-100">CANCEL</a>
                <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">ADD PRODUCT</button>
              </div>
            </form>
          </div>
        </div>
        <?php endif; ?>

        <!-- EDIT Modal (similar structure) -->
        <?php if ($showEditModal && !empty($editProduct)): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
          <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Edit Product</h2>
            <?php if (!empty($errors)): ?>
              <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm space-y-1">
                  <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
            <form method="POST" action="index.php?action=update_save" enctype="multipart/form-data">
              <input type="hidden" name="product_id" value="<?= (int)$editProduct['ProductID'] ?>">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border-2 border-dashed border-gray-300 p-6 rounded-lg text-center">
                  <label class="text-sm mb-2 block">Current Image</label>
                  <img src="<?= htmlspecialchars($editProduct['ImageURL']) ?>" class="mx-auto w-32 h-32 object-cover rounded mb-3">
                  <input type="file" name="product_image" accept="image/*">
                </div>
                <div class="space-y-3">
                  <input type="text" name="product_name" value="<?= htmlspecialchars($editProduct['ProductName']) ?>" class="w-full border border-gray-300 rounded px-4 py-2">
                  <textarea name="product_description" rows="3" class="w-full border border-gray-300 rounded px-4 py-2"><?= htmlspecialchars($editProduct['Description']) ?></textarea>
                  <div class="flex">
                    <input type="number" name="product_price" value="<?= htmlspecialchars($editProduct['Price']) ?>" class="w-full border border-gray-300 rounded-l px-4 py-2">
                    <span class="bg-gray-200 px-3 py-2 border border-gray-300 border-l-0 rounded-r text-gray-600">USD</span>
                  </div>
                  <input type="text" name="product_category" value="<?= htmlspecialchars($editProduct['CategoryName']) ?>" class="w-full border border-gray-300 rounded px-4 py-2">
                  <input type="text" name="product_sizes" value="<?= htmlspecialchars($editProduct['Sizes']) ?>" class="w-full border border-gray-300 rounded px-4 py-2">
                  <input type="number" name="product_quantity" value="<?= (int)$editProduct['Stock'] ?>" class="w-full border border-gray-300 rounded px-4 py-2">
                </div>
              </div>
              <div class="flex justify-end mt-6 space-x-2">
                <button type="button" onclick="window.location='index.php?action=admin_products';" class="px-4 py-2 border border-gray-400 rounded hover:bg-gray-100">CANCEL</button>
                <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">UPDATE PRODUCT</button>
              </div>
            </form>
          </div>
        </div>
        <?php endif; ?>

      </section>
              </section>

        <!-- ─── Success Toasts ─────────────────────────────────── -->
        <?php if (!empty($_GET['added'])): ?>
        <div id="toast-container" class="fixed inset-0 flex items-center justify-center pointer-events-none">
          <div
            class="bg-green-500 text-white px-6 py-3 rounded shadow-lg animate-fade-in-out pointer-events-auto"
          >
            Product added successfully
          </div>
        </div>
        <script>
          setTimeout(() => {
            const t = document.getElementById('toast-container');
            if (t) t.remove();
          }, 3000);
        </script>
        <?php endif; ?>

        <?php if (!empty($_GET['deleted'])): ?>
        <div id="toast-container" class="fixed inset-0 flex items-center justify-center pointer-events-none">
          <div
            class="bg-green-500 text-white px-6 py-3 rounded shadow-lg animate-fade-in-out pointer-events-auto"
          >
            Deleted successfully
          </div>
        </div>
        <script>
          setTimeout(() => {
            const t = document.getElementById('toast-container');
            if (t) t.remove();
          }, 3000);
        </script>
        <?php endif; ?>
        <!-- ─────────────────────────────────────────────────────────── -->

    </main>
  </div>

  <script>
    // Highlight selected row
    document.querySelectorAll('input[name="selected[]"]').forEach(cb => {
      cb.addEventListener('change', () => {
        cb.closest('tr').classList.toggle('bg-gray-100', cb.checked);
      });
    });
  </script>
</body>
</html>
