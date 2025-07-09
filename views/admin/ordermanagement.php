<?php
// views/admin/ordermanagement.php
// Expects: $orders (array), $showStatusModal (bool), $selectedOrders (array)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>STREETFORM – Order Management</title>
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

        <!-- FLASH ERROR -->
        <?php if (!empty($_SESSION['flash_error'])): ?>
          <div class="m-4 p-3 bg-red-100 text-red-800 rounded">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
          </div>
          <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <!-- CHANGE STATUS BUTTON + TABLE FORM -->
        <form method="POST" action="index.php?action=orders">
          <div class="p-4 border-b">
            <button
              type="submit"
              name="action"
              value="show_status_modal"
              class="bg-blue-600 text-white px-4 py-2 rounded"
            >
              Change status
            </button>
          </div>

          <div class="overflow-auto">
            <table class="w-full table-auto">
              <thead class="bg-gray-50 text-gray-500 text-sm">
                <tr>
                  <th class="w-12 px-4 py-4 align-middle"></th>
                  <th class="px-4 py-4 align-middle text-center">Order ID</th>
                  <th class="px-4 py-4 align-middle text-left">Customer Email</th>
                  <th class="px-4 py-4 align-middle text-left">Product</th>
                  <th class="px-4 py-4 align-middle text-center">Quantity</th>
                  <th class="px-4 py-4 align-middle text-right">Total ($)</th>
                  <th class="px-4 py-4 align-middle text-center">Status</th>
                  <th class="px-4 py-4 align-middle text-center">Date</th>
                </tr>
              </thead>
              <tbody class="text-sm">
                <?php foreach ($orders as $o): ?>
                  <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 align-middle">
                      <input
                        type="checkbox"
                        name="selected[]"
                        value="<?= (int)$o['OrderID'] ?>"
                        class="rounded border-gray-300"
                      >
                    </td>
                    <td class="px-4 py-5 align-middle text-center">
                      <?= (int)$o['OrderID'] ?>
                    </td>
                    <td class="px-4 py-5 align-middle">
                      <?= htmlspecialchars($o['CustomerEmail']) ?>
                    </td>
                    <td class="px-4 py-5 align-middle">
                      <?= htmlspecialchars($o['ProductName'] ?? '') ?>
                    </td>
                    <td class="px-4 py-5 align-middle text-center">
                      <?= (int)$o['Quantity'] ?>
                    </td>
                    <td class="px-4 py-5 align-middle text-right font-semibold">
                      $<?= number_format($o['TotalAmount'],2) ?>
                    </td>
                    <td class="px-4 py-5 align-middle text-center">
                      <?php 
                        $st = $o['OrderStatus'];
                        $classes = [
                          'Pending'   => 'bg-yellow-200 text-yellow-800',
                          'Confirmed' => 'bg-blue-200 text-blue-800',
                          'Completed' => 'bg-green-200 text-green-800',
                          'Cancelled' => 'bg-red-200 text-red-800',
                        ];
                      ?>
                      <span class="px-2 py-1 rounded text-sm <?= $classes[$st] ?? '' ?>">
                        <?= htmlspecialchars($st) ?>
                      </span>
                    </td>
                    <td class="px-4 py-5 align-middle text-center">
                      <?= date('Y-m-d', strtotime($o['OrderDate'])) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </form>

        <!-- STATUS-CHANGE MODAL -->
        <?php if ($showStatusModal): ?>
        <div
          class="fixed inset-0 z-50 flex items-center justify-center
                 bg-black/20 backdrop-blur-sm"
        >
          <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
            <h2 class="text-lg font-bold mb-4">Change Order Status</h2>
            <form method="POST" action="index.php?action=change_order_status">
              <?php foreach ($selectedOrders as $oid): ?>
                <input type="hidden" name="selected[]" value="<?= (int)$oid ?>">
              <?php endforeach; ?>

              <div class="mb-4">
                <label class="block mb-1 font-medium">New Status:</label>
                <select
                  name="new_status"
                  class="w-full border border-gray-300 rounded px-2 py-1"
                  required
                >
                  <option value="">— Select —</option>
                  <option value="Confirmed">Confirmed</option>
                  <option value="Completed">Completed</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
              </div>

              <div class="flex justify-end space-x-2">
                <a
                  href="index.php?action=orders"
                  class="px-4 py-2 border border-gray-400 rounded hover:bg-gray-100"
                >Cancel</a>
                <button
                  type="submit"
                  class="px-4 py-2 bg-blue-600 text-white rounded"
                >
                  Update
                </button>
              </div>
            </form>
          </div>
        </div>
        <?php endif; ?>

      </section>
    </main>
  </div>
</body>
</html>
