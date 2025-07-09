<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer or Guest</title>
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

<div
  class="flex flex-1 bg-cover bg-center items-center justify-center"
  style="background-image: url('../../public/assets/images/SF-bg.jpg')"
>
  <!-- transparent container -->
  <div class="bg-black bg-opacity-50 backdrop-blur-sm p-8 rounded-lg max-w-md w-full text-center space-y-8">
    <h1 class="text-4xl font-semibold">Continue as...</h1>
    <p class="text-gray-300">Are you a registered customer or just browsing?</p>
    <div class="flex gap-6 justify-center">
      <a
        href="../../views/auth/customerlogin.php"
        class="px-8 py-3 bg-green-500 text-white font-semibold rounded hover:bg-green-600 transition"
      >
        Registered Customer
      </a>
            <a
        href="/STREETFORM/public/index.php?action=guest"
        class="px-8 py-3 bg-white text-black font-semibold rounded hover:bg-gray-200 transition"
      >
        Continue as Guest
      </a>

    </div>
  </div>
</div>


  <!-- Footer -->
  <footer class="w-full bg-black p-4 text-center text-gray-400">
    &copy; <?= date('Y') ?> StreetForm. All rights reserved.
  </footer>
</body>
</html>
