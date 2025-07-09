<?php
// grab any error flag, or default to empty
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Login - StreetForm</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>
<body class="bg-black text-white min-h-screen font-poppins flex flex-col">


  <!-- Header -->
  <header class="w-full bg-black py-4 shadow-md">
    <div class="flex justify-center">
      <img src="/STREETFORM/public/assets/images/Logo.png" alt="StreetForm Logo" class="h-12">
    </div>
  </header>

  <!-- Login Form Area -->
  <div class="min-h-[80vh] flex items-center justify-center">
    <div class="bg-white bg-opacity-90 text-black rounded-lg p-10 max-w-md w-full space-y-6">
      <h2 class="text-3xl font-semibold text-center">Customer Login</h2>

      <?php if ($error === 'not_registered'): ?>
        <div class="text-red-600 text-center">
          Email not found. <a href="register.php" class="underline">Sign up here</a>.
        </div>
      <?php elseif ($error === 'mismatch'): ?>
        <div class="text-red-600 text-center">
          Password doesn’t match our records.
        </div>
      <?php endif; ?>

      <form method="POST" action="/STREETFORM/public/index.php?action=customerlogin" class="space-y-5">
        <input type="hidden" name="role" value="customer">

        <input 
          type="email" 
          name="email" 
          placeholder="Email" 
          required 
          class="w-full px-4 py-2 border-b rounded text-black"
        >
        <input 
          type="password" 
          name="password" 
          placeholder="Password" 
          required 
          class="w-full px-4 py-2 border-b rounded text-black"
        >

        <button 
          type="submit" 
          class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition"
        >
          Login
        </button>
      </form>

      <p class="text-center">
        Not registered yet?
        <a href="/STREETFORM/views/auth/register.php" class="text-blue-600 underline">Sign up</a>
      </p>
    </div>
  </div>

  <!-- Footer -->
  <footer class="w-full bg-black p-4 text-center text-gray-400">
    &copy; <?= date('Y') ?> StreetForm. All rights reserved.
  </footer>

</body>
</html>
