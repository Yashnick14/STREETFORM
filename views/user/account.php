<?php
// views/customer/account.php
// expects $_SESSION['email'] and $orders
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>My Account – StreetForm</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>
<body class="bg-white font-poppins text-gray-800">

  <?php include __DIR__ . '/../partials/header.php'; ?>
  <?php include __DIR__ . '/../partials/guard_customer.php'; ?>
  
  <main class="max-w-screen-xl mx-auto py-12 px-4 grid grid-cols-1 lg:grid-cols-12 gap-8">

<!-- SIDEBAR CARDS -->
<aside class="lg:col-span-4 -ml-4 space-y-6">
  
  <!-- FAVOURITES CARD -->
  <div class="relative rounded-lg overflow-hidden h-48">
    <img
      src="/STREETFORM/public/assets/images/favorite2.jpg"
      alt="My Favourites"
      class="w-full h-full object-cover"
    />
    <div class="absolute inset-0 flex items-center justify-center">
      <span class="text-white text-xl font-bold">FAVOURITES</span>
    </div>
  </div>

  <!-- POINTS CARD -->
  <div class="relative rounded-lg overflow-hidden h-48">
    <img
      src="/STREETFORM/public/assets/images/points.jpg"
      alt="StreetForm Points"
      class="w-full h-full object-cover"
    />
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white font-bold">
      <span>STREETFORM Points</span>
      <span class="text-4xl">50,000</span>
    </div>
  </div>

</aside>


    <!-- ORDER HISTORY -->
    <section class="lg:col-span-8 bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-semibold mb-6">Order History</h2>
           <table class="w-full text-left table-auto">
        <thead>
          <tr class="bg-gray-100 text-gray-700 text-sm">
            <th class="px-4 py-3">Item</th>
            <th class="px-4 py-3">Qty</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Action</th>
            <th class="px-4 py-3">Total</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          <?php if (empty($orders)): ?>
            <tr>
              <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                You have no past orders.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($orders as $o): ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-4"><?= htmlspecialchars($o['ProductName']) ?></td>
              <td class="px-4 py-4"><?= htmlspecialchars($o['Quantity']) ?></td>
              <td class="px-4 py-4">
                <span class="px-2 py-1 rounded-full text-sm
                  <?= $o['OrderStatus']==='Pending'   ? 'bg-yellow-100 text-yellow-800' : '' ?>
                  <?= $o['OrderStatus']==='Confirmed' ? 'bg-blue-100   text-blue-800'   : '' ?>
                  <?= $o['OrderStatus']==='Completed' ? 'bg-green-100  text-green-800' : '' ?>
                  <?= $o['OrderStatus']==='Cancelled' ? 'bg-red-100    text-red-800'   : '' ?>">
                  <?= htmlspecialchars($o['OrderStatus']) ?>
                </span>
              </td>
              <td class="px-4 py-4">
                <?php if ($o['OrderStatus'] !== 'Cancelled'): ?>
                  <form method="POST" action="index.php?action=cancel_order" class="inline">
                    <input type="hidden" name="order_id" value="<?= (int)$o['OrderID'] ?>">
                    <button type="submit"
                            class="bg-black text-white px-3 py-1 rounded text-sm hover:bg-gray-800">
                      Cancel
                    </button>
                  </form>
                <?php else: ?>
                  <span class="text-gray-500 text-sm">—</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-4 font-semibold">
                $<?= number_format($o['TotalAmount'],2) ?>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

  </main>

  <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
