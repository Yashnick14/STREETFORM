<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - StreetForm</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">

</head>
<body
  class="font-poppins min-h-screen"
  style="background:
    url('/STREETFORM/public/assets/images/newbg7.jpg') no-repeat center/cover;">

<?php include __DIR__ . '/../partials/admin_nav.php'; ?>
<?php include __DIR__ . '/../partials/guard_admin.php'; ?>


        <!-- Main Content -->
        <main class="flex-1 p-6">
            
            <!-- Spacer for Body Content -->
            <div class="mb-6 mt-16"></div>

            <!-- Dashboard Cards -->
            <div class="dashboard-grid grid grid-cols-3 gap-40 mb-10 ml-8 rounded-[16px] max-w-5xl mx-auto">

                <!-- Sales Card -->
                <div class="w-[350px] bg-pink-500 bg-opacity-80 p-4  text-white font-bold relative h-48">
                    <img src="/STREETFORM/public/assets/images/newdenimjacket2.png" alt="Sales" class="w-full h-full object-cover absolute inset-0">
                    <div class="relative z-10">
                        <h2 class="text-xl mb-9 text-left">Sales</h2>
                        <p class="text-3xl text-center"> Pieces</p>
                    </div>
                </div>

                <!-- Earnings Card -->
                <div class="w-[350px] bg-blue-500 bg-opacity-80 p-6 rounded-xl text-white font-bold relative h-48">
                    <img src="/STREETFORM/public/assets/images/newsale.png" alt="Earnings" class="w-full h-full object-cover absolute inset-0">
                    <div class="relative z-10">
                        <h2 class="text-xl mb-9 text-left">Earnings</h2>
                        <p class="text-3xl text-center">$5,000</p>
                    </div>
                </div>

                <!-- Registered Users Card -->
                <div class="w-[350px] bg-gray-800 bg-opacity-80 p-6 rounded-xl text-white font-bold relative h-48">
                    <img src="/STREETFORM/public/assets/images/newbg3.png" alt="Registered Users" class="w-full h-full object-cover absolute inset-0">
                    <div class="relative z-10">
                        <h2 class="text-xl mb-9 text-left">Registered Users</h2>
                        <p class="text-3xl text-center">20</p>
                    </div>
                </div>
            </div>

<!-- ─── Stylish “Orders” Bar Chart (Enhanced) ─────────────────────────────────────────────── -->
<div class="max-w-7xl mx-auto p-10 rounded-3xl shadow-2xl relative overflow-hidden border-4 border-gradient-to-r from-pink-400 via-blue-400 to-purple-400"
  style="background: linear-gradient(135deg, rgba(24,24,27,0.75) 80%, rgba(236,72,153,0.08) 100%); box-shadow: 0 10px 40px 0 rgba(59,130,246,0.18);">
  <!-- Decorative Glow & Sparkles -->
  <!-- Removed the top-right and bottom-left burst divs -->
  <div class="absolute top-10 left-1/2 transform -translate-x-1/2 z-0 pointer-events-none">
    <svg width="120" height="40">
   <circle cx="20" cy="20" r="2" fill="#fff" opacity="0.7"/>
   <circle cx="60" cy="10" r="1.5" fill="#ec4899" opacity="0.8"/>
   <circle cx="100" cy="30" r="2.5" fill="#3b82f6" opacity="0.6"/>
    </svg>
  </div>
  <div class="flex justify-between items-center mb-8 relative z-10">
    <div>
   <h3 class="text-white text-3xl font-extrabold tracking-wide drop-shadow-lg flex items-center gap-2">
     <span class="animate-bounce">📊</span> Orders Overview
   </h3>
   <p class="text-gray-300 text-base mt-2">Your sales and referral earnings over the last 12 months.</p>
    </div>
    <div class="space-x-2">
   <button class="px-5 py-2 text-gray-300 bg-gray-700 rounded-full text-base font-semibold shadow hover:bg-gray-600 transition">Year</button>
   <button class="px-5 py-2 text-white bg-gradient-to-r from-pink-500 via-purple-500 to-blue-500 rounded-full text-base font-semibold shadow-lg hover:from-pink-600 hover:to-blue-600 transition scale-105">Month</button>
    </div>
  </div>
  <div class="relative z-10">
    <svg viewBox="0 0 1600 260" class="w-full h-72 select-none">
   <!-- X-axis -->
   <line x1="60" y1="210" x2="1540" y2="210" stroke="#6B7280" stroke-width="2"/>
   <!-- Y-axis grid lines -->
   <?php for($i=0;$i<=4;$i++): $y=50+$i*40; ?>
     <line x1="60" y1="<?= $y ?>" x2="1540" y2="<?= $y ?>" stroke="#374151" stroke-width="1" stroke-dasharray="6,6"/>
     <text x="40" y="<?= $y+5 ?>" fill="#c7d2fe" font-size="15" text-anchor="end" font-family="Poppins" font-weight="bold" opacity="0.8"><?= 200-($i*40) ?></text>
   <?php endfor; ?>
   <!-- Bars with gradient, shadow, and animated highlight -->
   <defs>
     <linearGradient id="barGradient" x1="0" y1="0" x2="0" y2="1">
    <stop offset="0%" stop-color="#f472b6"/>
    <stop offset="60%" stop-color="#6366f1"/>
    <stop offset="100%" stop-color="#3b82f6"/>
     </linearGradient>
     <filter id="barShadow" x="-20%" y="-20%" width="140%" height="140%">
    <feDropShadow dx="0" dy="12" stdDeviation="10" flood-color="#6366f1" flood-opacity="0.22"/>
     </filter>
     <linearGradient id="barHighlight" x1="0" y1="0" x2="0" y2="1">
    <stop offset="0%" stop-color="#fff" stop-opacity="0.45"/>
    <stop offset="100%" stop-color="#fff" stop-opacity="0"/>
     </linearGradient>
   </defs>
   <?php
     $heights = [90, 150, 110, 170, 210, 130, 90, 190, 160, 180, 140, 120];
     foreach($heights as $i => $h):
    $x = 90 + $i * 120;
    $y = 210 - $h;
   ?>
     <g>
    <rect x="<?= $x ?>" y="<?= $y ?>" width="60" height="<?= $h ?>" fill="url(#barGradient)" rx="16" filter="url(#barShadow)">
      <animate attributeName="height" from="0" to="<?= $h ?>" dur="0.8s" fill="freeze" begin="<?= $i*0.08 ?>s"/>
      <animate attributeName="y" from="210" to="<?= $y ?>" dur="0.8s" fill="freeze" begin="<?= $i*0.08 ?>s"/>
    </rect>
    <!-- Animated highlight overlay -->
    <rect x="<?= $x ?>" y="<?= $y ?>" width="60" height="<?= $h/2 ?>" fill="url(#barHighlight)" rx="16">
      <animate attributeName="height" from="0" to="<?= $h/2 ?>" dur="0.8s" fill="freeze" begin="<?= $i*0.08+0.2 ?>s"/>
      <animate attributeName="y" from="210" to="<?= $y ?>" dur="0.8s" fill="freeze" begin="<?= $i*0.08+0.2 ?>s"/>
    </rect>
    <!-- Value label on top of bar with drop shadow -->
    <text x="<?= $x+30 ?>" y="<?= $y-14 ?>" fill="#fff" font-size="18" text-anchor="middle" font-family="Poppins" font-weight="bold" opacity="0.92" style="text-shadow:0 2px 8px #3b82f6;"><?= $h ?></text>
     </g>
   <?php endforeach; ?>
   <!-- Month labels with highlight for current month -->
   <?php 
     $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
     $currentMonth = (int)date('n')-1;
     foreach($labels as $i => $m): 
    $x = 90 + $i * 120 + 30;
    $isCurrent = $i === $currentMonth;
   ?>
     <text x="<?= $x ?>" y="240" fill="<?= $isCurrent ? '#f472b6' : '#c7d2fe' ?>" font-size="16" text-anchor="middle" font-family="Poppins" font-weight="700" opacity="<?= $isCurrent ? '1' : '0.85' ?>">
    <?= $m ?>
    <?php if($isCurrent): ?>
      <tspan dx="0" dy="-8" font-size="12" fill="#f472b6">★</tspan>
    <?php endif; ?>
     </text>
   <?php endforeach; ?>
    </svg>
  </div>
  <!-- Subtle animated gradient bar below chart -->
  <div class="absolute left-0 right-0 bottom-0 h-2 rounded-b-3xl bg-gradient-to-r from-pink-400 via-blue-400 to-purple-400 animate-pulse"></div>
</div>
<!-- ──────────────────────────────────────────────────────────────────────────────── -->

    <script>
        function logout() {
            window.location.href = "../controllers/logout.php";
        }
    </script>
</body>
</html>



































































































































       