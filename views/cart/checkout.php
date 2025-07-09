
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Checkout – StreetForm</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>
<body class="bg-white font-poppins text-gray-800">
  <?php include __DIR__ . '/../partials/header.php'; ?>
  <?php include __DIR__ . '/../partials/guard_customer.php'; ?>
  
  <div class="max-w-4xl mx-auto space-y-6">

    <!-- success banner -->
<?php if (!empty($success) && !empty($orderId)): ?>
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    <strong>Thank you!</strong>
    Your order has been placed successfully.
  </div>
<?php endif; ?>




    <!-- wrap everything in one form -->
    <form method="POST" action="index.php?action=process_payment" class="space-y-10">

      <!-- ORDER SUMMARY -->
      <div class="pb-4 mb-4">
        <div class="flex justify-between items-center font-semibold border-b px-4 py-3">
          <span class="w-2/5">ORDER SUMMARY</span>
          <div class="flex w-3/5 justify-between text-right">
            <span class="w-1/3">Quantity</span>
            <span class="w-1/3">Price</span>
            <span class="w-1/3">Total</span>
          </div>
        </div>

        <?php foreach ($items as $item): ?>
        <div class="flex justify-between items-center border-b px-4 py-3 gap-4">
          <div class="flex items-center gap-3 w-2/5">
            <img src="/STREETFORM/public/<?= htmlspecialchars($item['image']) ?>"
                 class="w-12 h-12 object-cover rounded border" />
            <div>
              <p class="font-semibold"><?= htmlspecialchars($item['name']) ?></p>
              <p class="text-xs text-gray-500">Size: <?= htmlspecialchars($item['size']) ?></p>
            </div>
          </div>
          <div class="flex justify-between text-right w-3/5">
            <span class="w-1/3"><?= (int)$item['quantity'] ?></span>
            <span class="w-1/3">$<?= number_format($item['price'],2) ?></span>
            <span class="w-1/3 font-semibold">
              $<?= number_format($item['price'] * $item['quantity'],2) ?>
            </span>
          </div>
        </div>
        <?php endforeach; ?>

      <!-- Discount & Final Total -->
      <div class="flex justify-between px-4 py-2 text-sm text-green-700">
        <span>Membership Discount (10%)</span>
        <span>- $<?= number_format($discount,2) ?></span>
      </div>
      <div class="flex justify-between font-bold px-4 pt-1 pb-3 text-lg">
        <span>Total</span>
        <span>$<?= number_format($finalTotal,2) ?></span>
      </div>
      <br><br>

      <!-- PERSONAL & DELIVERY DETAILS -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-sm">
        <div class="space-y-6">
          <h3 class="font-semibold border-b pb-1 mb-3 text-sm">PERSONAL DETAILS</h3>
          <input type="text"  name="first_name" placeholder="First Name" class="w-full border p-2 rounded" required>
          <input type="text"  name="last_name"  placeholder="Last Name"  class="w-full border p-2 rounded" required>
          <input type="email" name="email"
                 value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>"
                 placeholder="Email" class="w-full border p-2 rounded" required>

          <h3 class="font-semibold border-b pb-1 mt-6 mb-3 text-sm">DELIVERY DETAILS</h3>
          <select name="country" class="w-full border p-2 mb-2 rounded" required>
            <option value="">Country</option>
            <option value="Sri Lanka" selected>Sri Lanka</option>
          </select>
          <input type="text" name="address" placeholder="Address" class="w-full border p-2 rounded" required>
          <input type="text" name="city"    placeholder="City"    class="w-full border p-2 rounded" required>
          <input type="text" name="zipcode" placeholder="Zip Code" class="w-full border p-2 rounded" required>
        </div>

        <!-- CARD DETAILS & SUBMIT -->
        <div class="space-y-4">
          <h3 class="font-semibold border-b pb-1 mb-3 text-sm">CARD DETAILS</h3>
          <input type="text" name="card_number" placeholder="Card Number"
                 class="w-full border p-2 rounded" required>

          <div class="flex gap-2 mb-2">
            <select name="expiry_month" class="w-1/2 border p-2 rounded" required>
              <option value="">Month</option>
              <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>"><?= str_pad($m,2,'0',STR_PAD_LEFT) ?></option>
              <?php endfor; ?>
            </select>
            <select name="expiry_year" class="w-1/2 border p-2 rounded" required>
              <option value="">Year</option>
              <?php for ($y = date('Y'); $y <= date('Y')+10; $y++): ?>
                <option value="<?= $y ?>"><?= $y ?></option>
              <?php endfor; ?>
            </select>
          </div>

          <input type="text" name="cvv" placeholder="CVV"
                 class="w-full border p-2 rounded" required>

          <button type="submit"
                  class="w-full bg-black text-white py-3 rounded font-semibold hover:bg-gray-800 transition">
            PAY NOW
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
  <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
