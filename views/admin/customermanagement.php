<?php
// views/admin/customermanagement.php
// Expects: $customers (array), and $_GET['status'] for which tab is active
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>STREETFORM – Customer Management</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>
<body class="bg-gray-100 font-poppins min-h-screen flex">
  <div class="flex-1 flex flex-col">

    <?php include __DIR__ . '/../partials/admin_nav.php'; ?>
    <?php include __DIR__ . '/../partials/guard_admin.php'; ?>

    <!-- MAIN -->
    <main class="flex-1 p-6">
      <section class="bg-white rounded shadow overflow-hidden">

        <!-- FILTER TABS -->
        <?php $s = $_GET['status'] ?? 'all'; ?>
        <div class="flex items-center px-4 py-3 border-b space-x-2">
          <a href="?action=customers&status=all"
             class="px-4 py-1 rounded-full text-sm <?= $s==='all'?'bg-black text-white':'bg-gray-200' ?>">
            All
          </a>
          <a href="?action=customers&status=active"
             class="px-4 py-1 rounded-full text-sm <?= $s==='active'?'bg-black text-white':'bg-gray-200' ?>">
            Active
          </a>
          <a href="?action=customers&status=inactive"
             class="px-4 py-1 rounded-full text-sm <?= $s==='inactive'?'bg-black text-white':'bg-gray-200' ?>">
            Inactive
          </a>
        </div>

        <!-- TABLE -->
        <div class="overflow-auto">
          <table class="w-full table-auto">
            <thead class="bg-gray-50 text-gray-500 text-sm">
              <tr>
                <th class="w-12 px-4 py-3 align-middle"></th>
                <th class="px-4 py-3 align-middle text-left">Customer</th>
                <th class="px-4 py-3 align-middle text-center">Status</th>
                <th class="px-4 py-3 align-middle text-center">Orders</th>
                <th class="px-4 py-3 align-middle text-right">Amount Spent</th>
                <th class="px-4 py-3 align-middle text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="text-sm">
              <?php foreach ($customers as $c): ?>
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-4 py-3 align-middle">
                    <input
                      type="checkbox"
                      name="selected[]"
                      value="<?= (int)$c['CustomerID'] ?>"
                      class="rounded border-gray-300"
                    >
                  </td>
                  <td class="px-4 py-3 align-middle"><?= htmlspecialchars($c['Username']) ?></td>
                  <td class="px-4 py-3 align-middle text-center">
                    <span class="px-2 py-1 rounded text-sm
                         <?= $c['IsActive']
                             ? 'bg-green-200 text-green-800'
                             : 'bg-red-200 text-red-800' ?>">
                      <?= $c['IsActive'] ? 'Active' : 'Inactive' ?>
                    </span>
                  </td>
                  <td class="px-4 py-3 align-middle text-center">
                    <?= (int)$c['OrdersCount'] ?>
                  </td>
                  <td class="px-4 py-3 align-middle text-right font-semibold">
                    $<?= number_format((float)$c['AmountSpent'], 2) ?>
                  </td>
                  <td class="px-4 py-3 align-middle">
                    <div class="flex items-center justify-center space-x-2">
                      <!-- Deactivate / Activate -->
                      <form method="POST" action="index.php?action=toggle_customer_status" class="inline">
                        <input type="hidden" name="id" value="<?= (int)$c['CustomerID'] ?>">
                        <button
                          type="submit"
                          class="px-3 py-1 rounded text-white bg-red-600 hover:bg-red-700 text-xs"
                        >
                          <?= $c['IsActive'] ? 'Deactivate' : 'Activate' ?>
                        </button>
                      </form>
                      <!-- Delete -->
                      <a
                        href="index.php?action=delete_customer&id=<?= (int)$c['CustomerID'] ?>"
                        class="text-red-600 hover:text-red-800 text-xl font-bold"
                        title="Delete"
                      >&times;</a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      </section>
    </main>
  </div>
</body>
</html>
