<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>StreetForm - Homepage</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/STREETFORM/public/assets/css/output.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-white text-gray-800 font-poppins">

  <?php include __DIR__ . '/../partials/header.php';?>

  <!-- Hero -->
  <section class="hero-section">
    <div class="hero-grid">
      <!-- Men -->
      <div class="hero-card group">
        <img
          src="/STREETFORM/public/assets/images/men2.jpg"
          alt="Men's Collection"
          class="hero-image"
        >
        <a href="/STREETFORM/public/index.php?action=mens" class="hero-link">
          <span class="btn-hero-male">MEN</span>
        </a>
      </div>
      <!-- Women -->
      <div class="hero-card group">
        <img
          src="/STREETFORM/public/assets/images/women.jpg"
          alt="Women's Collection"
          class="hero-image"
        >
        <a href="/STREETFORM/public/index.php?action=womens" class="hero-link">
          <span class="btn-hero-female">WOMEN</span>
        </a>
      </div>
    </div>
  </section>
<br><br><br>
<h2 class="collection-title text-center" style="margin-left: 0; text-align: center;">WHY STREETFORM</h2>
<section
  class="relative w-full overflow-hidden"
  style="height: 70vh;"
>
  <video
    id="whyStreetformVideo"
    class="absolute inset-0 w-full h-full object-cover pointer-events-none"
    autoplay
    muted
    loop
    playsinline
    style="object-fit: fill; width: 100%; height: 100%;"
  >
    <source
      src="/STREETFORM/public/assets/images/video.mp4"
      type="video/mp4"
    >
    Your browser doesn’t support HTML-5 video.
  </video>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const video = document.getElementById('whyStreetformVideo');
      video.addEventListener('loadedmetadata', function() {
        const trimEnd = 3;
        video.addEventListener('timeupdate', function() {
          if (video.duration && video.currentTime >= video.duration - trimEnd) {
            video.currentTime = 0;
            video.play();
          }
        });
      });
    });
  </script>
</section>
<br>
  <!-- Latest Collection -->
  <section class="collection-section">
    <div class="collection-container">
      <h2 class="collection-title">LATEST COLLECTION</h2>
      <div class="relative">
        <div class="carousel">
          <?php foreach($latestItems as $p): ?>
            <div class="carousel-item">
              <a
                href="/STREETFORM/public/index.php?action=view&id=<?= (int)$p['ProductID'] ?>"
                class="product-card"
              >
                <img
                  src="/STREETFORM/public/uploads/<?= htmlspecialchars(basename($p['ImageURL'])) ?>"
                  alt="<?= htmlspecialchars($p['ProductName']) ?>"
                  class="product-image"
                />
                <div class="product-info">
                  <h3 class="product-name">
                    <?= htmlspecialchars($p['ProductName']) ?>
                  </h3>
                  <p class="product-category">
                    <?= htmlspecialchars($p['CategoryName']) ?>
                  </p>
                  <p class="product-price">
                    $<?= number_format($p['Price'],2) ?>
                  </p>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <?php include __DIR__ . '/../partials/footer.php';?>
</body>
</html>
