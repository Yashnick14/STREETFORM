<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - StreetForm</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
</head>
<body class="bg-black text-white font-poppins">

    <!-- Header -->
    <header class="w-full bg-black py-4 shadow-md">
        <div class="flex justify-center">
            <img src="/STREETFORM/public/assets/images/Logo.png" alt="StreetForm Logo" class="h-12">
        </div>
    </header>

    <!-- Login Form Area -->
    <div class="min-h-[80vh] flex items-center justify-center bg-cover bg-center" style="background-image: url('../../public/assets/images/SF-bg.jpg')">
        <div class="bg-white bg-opacity-90 backdrop-blur-md shadow-xl rounded-lg p-10 text-center space-y-6 max-w-md w-full">
            <h2 class="text-3xl font-bold text-black">Admin Login</h2>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
              <div class="text-red-600 font-semibold">Invalid email or password. Please try again.</div>
            <?php endif; ?>

                <form method="POST" action="/STREETFORM/public/index.php?action=adminlogin" class="space-y-5">
                <input type="hidden" name="role" value="admin">

                <input 
                    type="email" 
                    name="email" 
                    placeholder="Admin Email" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 text-black"
                >

                <input 
                    type="password" 
                    name="password" 
                    placeholder="Password" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 text-black"
                >

                <a href="#" class="block text-sm text-blue-600 underline text-left">Forgot your password?</a>

                <button 
                    type="submit" 
                    name="login" 
                    class="w-full bg-black text-white py-3 rounded hover:bg-gray-800 transition"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
    <!-- Footer -->
    <footer class="w-full bg-black p-4 text-center text-gray-400">
        &copy; <?= date('Y') ?> StreetForm. All rights reserved.
    </footer>

</body>
</html>
