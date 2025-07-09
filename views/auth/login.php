<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Login Role</title>
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>

<body class="bg-black font-poppins text-white">


    <!-- Header -->
    <header class="w-full bg-black py-4 shadow-md">
        <div class="flex justify-center">
            <img src="../../public/assets/images/Logo.png" alt="StreetForm Logo" class="h-12">
        </div>
    </header>

    <!-- Role Selection Section -->
    <div class="flex flex-col items-center justify-center min-h-[80vh] text-center space-y-8 bg-cover bg-center" style="background-image: url('../public/images/streetform-bg.jpg')">
        <h1 class="text-4xl font-bold">Welcome to Street Form</h1>
        <p class="text-gray-300">Please select your login role:</p>
        <div class="flex gap-6">
            <a href="../../views/auth/userlogin.php" class="px-8 py-3 bg-white text-black font-semibold rounded hover:bg-gray-200 transition">Login as Customer</a>
            <a href="../../views/auth/adminlogin.php" class="px-8 py-3 bg-gray-800 text-white font-semibold rounded hover:bg-gray-700 transition">Login as Admin</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full bg-black p-4 text-center text-gray-400">
        &copy; <?= date('Y') ?> StreetForm. All rights reserved.
    </footer>

</body>
</html>
