<?php
$error      = $_GET['error']      ?? '';
$registered = isset($_GET['registered']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Registration – StreetForm</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>
<body class="bg-black text-white min-h-screen font-poppins flex flex-col">


  <!-- Header -->
  <header class="w-full bg-black py-4 shadow-md">
    <div class="flex justify-center">
      <img src="../../public/assets/images/Logo.png" alt="StreetForm Logo" class="h-12">
    </div>
  </header>

  <!-- Centered Form Container -->
  <div class="flex-1 flex items-center justify-center">
    <div class="bg-white bg-opacity-90 backdrop-blur-sm text-black rounded-lg p-8 w-full max-w-md space-y-6">
      <h2 class="text-2xl font-bold text-center">Customer Registration</h2>

      <?php if ($registered): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded text-center">
          Registration successful! <a href="../../views/auth/customerlogin.php" class="underline">Log in</a>.
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded text-center">
          <?php if ($error==='empty'): ?>All fields are required.
          <?php elseif ($error==='invalid_email'): ?>Please enter a valid email.
          <?php elseif ($error==='mismatch'): ?>Passwords do not match.
          <?php elseif ($error==='exists'): ?>Username or email already taken.
          <?php else: ?>Registration failed. Please try again.
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <form method="POST"
            action="/STREETFORM/public/index.php?action=register"
            class="space-y-4">
        <div>
          <label class="block mb-1">Username</label>
          <input type="text"
                 name="username"
                 required
                 class="w-full px-3 py-2 border rounded text-black focus:outline-none focus:ring-2 focus:ring-gray-800">
        </div>
        <div>
          <label class="block mb-1">Email</label>
          <input type="email"
                 name="email"
                 required
                 class="w-full px-3 py-2 border rounded text-black focus:outline-none focus:ring-2 focus:ring-gray-800">
        </div>
        <div>
          <label class="block mb-1">Password</label>
          <input type="password"
                 name="password"
                 required
                 class="w-full px-3 py-2 border rounded text-black focus:outline-none focus:ring-2 focus:ring-gray-800">
        </div>
        <div>
          <label class="block mb-1">Confirm Password</label>
          <input type="password"
                 name="confirm_password"
                 required
                 class="w-full px-3 py-2 border rounded text-black focus:outline-none focus:ring-2 focus:ring-gray-800">
        </div>
        <button type="submit"
                class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition">
          Register
        </button>
      </form>

      <p class="text-sm text-center">
        Already have an account?
        <a href="customerlogin.php" class="text-blue-600 underline">Login here</a>
      </p>
    </div>
  </div>

</body>
</html>
