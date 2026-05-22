<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($signage['name'] ?? 'Digital Signage') ?> - Player</title>
  <link rel="stylesheet" href="<?= base_url('assets/plugins/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Google Fonts Modern -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <style>
    /* ========================================================= */
    /* MODERN GLASSMORPHISM + CYBERPUNK ACCENTS (2025 STYLE) */
    /* ========================================================= */
    :root {
      --bg-dark: #0d0d0d;
      --bg-darker: #080808;
      --surface: rgba(20, 20, 25, 0.75);
      --surface-border: rgba(80, 80, 100, 0.2);
      --text-primary: #f0f0f5;
      --text-secondary: #a0a0b0;
      --accent: #00d1ff; /* cyan-neon */
      --accent-glow: rgba(0, 209, 255, 0.3);
      --success: #00f5a8;
      --warning: #ffcc00;
      --error: #ff4d94;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: 
        radial-gradient(circle at 20% 30%, rgba(0, 30, 60, 0.2) 0%, transparent 40%),
        radial-gradient(circle at 80% 70%, rgba(0, 60, 100, 0.15) 0%, transparent 50%),
        var(--bg-darker);
      color: var(--text-primary);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      height: 100vh;
      width: 100vw;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
    }

    /* Subtle animation background */
    body::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: 
        radial-gradient(circle at 30% 20%, var(--accent-glow) 0%, transparent 30%),
        radial-gradient(circle at 70% 80%, rgba(0, 230, 200, 0.1) 0%, transparent 40%);
      animation: rotate 45s linear infinite;
      z-index: -1;
      opacity: 0.15;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .container {
      width: 98%;
      height: 96%;
      max-width: 1920px;
      max-height: 1080px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      padding: 1.2rem;
      gap: 1rem;
    }

    /* ========================================================= */
    /* MEDIA WRAPPER — GLASSMORPHIC CARD */
    /* ========================================================= */
    .media-wrapper {
      width: 100%;
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    .media-box,
    .dual > div,
    .media-grid > div {
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 16px;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.4),
        inset 0 0 0 1px rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Hover glow effect */
    .media-box:hover,
    .dual > div:hover,
    .media-grid > div:hover {
      box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.5),
        inset 0 0 0 1px rgba(255, 255, 255, 0.08),
        0 0 20px var(--accent-glow);
      transform: translateY(-2px);
    }

    .media-box::after {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: linear-gradient(45deg, 
        transparent, 
        var(--accent), 
        #00f5a8, 
        #ffcc00, 
        transparent);
      z-index: -1;
      border-radius: 18px;
      animation: shimmer 3s infinite linear;
      opacity: 0;
      transition: opacity 0.5s;
    }

    .media-box:hover::after {
      opacity: 0.6;
    }

    @keyframes shimmer {
      0% { background-position: -500%; }
      100% { background-position: 500%; }
    }

    /* Media styling */
    video, img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* ✅ Tampilkan SEMUA bagian, tanpa potong */
        object-position: center; /* Pastikan terpusat */
        background-color: #000; /* Opsional: hindari latar putih transparan */
        transition: opacity 0.5s ease;
    }
    /* Media yang HARUS utuh (default untuk signage) */
    .media-essential {
        object-fit: contain !important;
        object-position: center;
        }

        /* Media yang Boleh dipotong (untuk background/vibe) */
        .media-background {
        object-fit: cover !important;
        object-position: center;
        }

    /* Loading placeholder */
    .media-placeholder {
      background: linear-gradient(90deg, 
        rgba(30,30,40,0.8) 0%, 
        rgba(40,40,50,0.8) 50%, 
        rgba(30,30,40,0.8) 100%);
      animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 0.7; }
      50% { opacity: 1; }
    }

    /* ========================================================= */
    /* LAYOUTS */
    /* ========================================================= */
    .dual {
      display: flex;
      gap: 1rem;
      width: 100%;
      height: 100%;
    }

    .media-grid {
      display: grid;
      gap: 1rem;
      width: 100%;
      height: 100%;
    }

    .triple-vertical { grid-template-columns: repeat(3, 1fr); }
    .quad-grid { grid-template-columns: repeat(2, 1fr); grid-template-rows: repeat(2, 1fr); }

    /* Carousel */
    .carousel {
      width: 100%;
      height: 100%;
      position: relative;
    }

    .carousel-item {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 1s ease-in-out;
    }

    .carousel-item.active {
      opacity: 1;
      z-index: 2;
    }

    /* ========================================================= */
    /* TEXT MARQUEE — MODERN & INTERACTIVE */
    /* ========================================================= */
    .text-area {
      width: 100%;
      height: 8%;
      min-height: 60px;
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 14px;
      overflow: hidden;
      display: flex;
      align-items: center;
      padding: 0 1.5rem;
      box-shadow: 
        0 4px 20px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .text-marquee {
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: clamp(1.4rem, 2.8vw, 2.6rem);
      color: var(--accent);
      white-space: nowrap;
      position: relative;
      display: inline-block;
    }

    .text-marquee::before {
      content: "📢 ";
    }

    .marquee-container {
      display: flex;
      animation: scroll 28s linear infinite;
    }

    .marquee-container:hover {
      animation-play-state: paused;
    }

    @keyframes scroll {
      from { transform: translateX(100%); }
      to { transform: translateX(-100%); }
    }

    /* Indicator for carousel */
    .carousel-indicators {
      position: absolute;
      bottom: 16px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 6px;
      z-index: 10;
    }

    .indicator {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .indicator.active {
      background: var(--accent);
      transform: scale(1.3);
    }

    /* ========================================================= */
    /* RESPONSIVENESS — MOBILE-FIRST */
    /* ========================================================= */
    @media (max-width: 768px) {
      .container { padding: 0.8rem; }
      
      .dual { flex-direction: column; }
      
      .triple-vertical,
      .quad-grid {
        grid-template-columns: 1fr;
        grid-template-rows: auto;
      }

      .text-area { height: 10%; min-height: 50px; }
      .text-marquee { font-size: clamp(1.2rem, 4vw, 1.8rem); }
    }

    @media (max-width: 480px) {
      .media-box, .dual > div, .media-grid > div {
        border-radius: 12px;
      }
      
      .text-area { padding: 0 1rem; }
      .text-marquee::before { content: "▶ "; }
    }

    /* Ultra-wide support */
    @media (min-width: 1920px) {
      .container { height: 98%; }
    }

    /* Accessibility: reduce motion */
    @media (prefers-reduced-motion: reduce) {
      * { animation-duration: 0.01ms !important; }
      .media-box:hover::after { opacity: 0 !important; }
    }
  </style>
</head>
<body>

<?php
// PHP LOGIC (lebih aman & scalable)
$content = [];
if (!empty($signage['content'])) {
    $raw = $signage['content'];
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $content = is_array($decoded) ? $decoded : [];
    } elseif (is_array($raw)) {
        $content = $raw;
    }
}
$layout = $signage['layout'] ?? 'single';
$media_items = $content['media'] ?? [];
?>

<div class="container">

  <!-- MEDIA SECTION -->
  <div class="media-wrapper">
    <?php if ($layout === 'single' || $layout === 'video_text'): ?>
      <div class="media-box">
        <?php if (!empty($content['video'])): ?>
          <video 
            autoplay 
            loop 
            muted 
            playsinline
            poster="<?= !empty($content['image']) ? base_url($content['image']) : '' ?>"
            onloadeddata="this.classList.remove('media-placeholder')"
            class="media-placeholder"
          >
            <source src="<?= base_url($content['video']) ?>" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        <?php elseif (!empty($content['image'])): ?>
          <img 
            src="<?= base_url($content['image']) ?>" 
            alt="Signage Content"
            loading="eager"
            onerror="this.style.backgroundColor='#1a1a2e'; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2264%22 height=%2264%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%234a5568%22 stroke-width=%221.5%22><path d=%22M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z%22/></svg>';"
          >
        <?php else: ?>
          <div class="media-placeholder" style="display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--text-secondary);">
            <i class="fas fa-image mr-2"></i> No Media
          </div>
        <?php endif; ?>
      </div>

    <?php elseif ($layout === 'dual'): ?>
      <div class="dual">
        <div class="media-box">
          <?php if (!empty($content['image'])): ?>
            <img src="<?= base_url($content['image']) ?>" alt="Left Media">
          <?php else: ?>
            <div class="media-placeholder"></div>
          <?php endif; ?>
        </div>
        <div class="media-box">
          <?php if (!empty($content['video'])): ?>
            <video autoplay loop muted playsinline>
              <source src="<?= base_url($content['video']) ?>" type="video/mp4">
            </video>
          <?php else: ?>
            <div class="media-placeholder"></div>
          <?php endif; ?>
        </div>
      </div>

    <?php elseif ($layout === 'triple_vertical' || $layout === 'quad_grid'): ?>
      <div class="media-grid <?= $layout ?>">
        <?php 
        $count = ($layout === 'triple_vertical') ? 3 : 4;
        for ($i = 0; $i < $count; $i++): 
          $item = $media_items[$i] ?? null;
        ?>
          <div class="media-box">
            <?php if ($item): ?>
              <?php if ($item['type'] === 'video'): ?>
                <video autoplay loop muted playsinline>
                  <source src="<?= base_url($item['src']) ?>" type="video/mp4">
                </video>
              <?php else: ?>
                <img src="<?= base_url($item['src']) ?>" alt="Media <?= $i+1 ?>">
              <?php endif; ?>
            <?php else: ?>
              <div class="media-placeholder"></div>
            <?php endif; ?>
          </div>
        <?php endfor; ?>
      </div>

    <?php elseif ($layout === 'carousel'): ?>
      <div class="media-box">
        <div class="carousel">
          <?php 
          $carousel_items = [];
          if (!empty($content['image'])) $carousel_items[] = ['type' => 'image', 'src' => $content['image']];
          if (!empty($content['video'])) $carousel_items[] = ['type' => 'video', 'src' => $content['video']];
          // Add from media array if needed
          $carousel_items = array_merge($carousel_items, $media_items);
          ?>
          
          <?php foreach ($carousel_items as $idx => $item): ?>
            <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
              <?php if ($item['type'] === 'video'): ?>
                <video 
                  muted 
                  playsinline
                  <?= $idx === 0 ? 'autoplay' : '' ?>
                >
                  <source src="<?= base_url($item['src']) ?>" type="video/mp4">
                </video>
              <?php else: ?>
                <img src="<?= base_url($item['src']) ?>" alt="Slide <?= $idx+1 ?>">
              <?php endif; ?>
            </div>
          <?php endforeach; ?>

          <?php if (count($carousel_items) > 1): ?>
            <div class="carousel-indicators">
              <?php foreach ($carousel_items as $idx => $item): ?>
                <span class="indicator <?= $idx === 0 ? 'active' : '' ?>" data-index="<?= $idx ?>"></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- TEXT MARQUEE -->
  <?php if (!empty($content['text'])): ?>
    <div class="text-area">
      <div class="marquee-container">
        <?php 
        $text = htmlspecialchars($content['text']);
        // Duplikasi 3x untuk efek seamless
        for ($i = 0; $i < 3; $i++): ?>
          <span class="text-marquee"><?= $text ?></span>
          <?php if ($i < 2): ?><span style="margin:0 2rem;">•</span><?php endif; ?>
        <?php endfor; ?>
      </div>
    </div>
  <?php endif; ?>

</div>

<?php if ($layout === 'carousel' && !empty($carousel_items)): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.carousel-item');
  const indicators = document.querySelectorAll('.indicator');
  let currentIndex = 0;
  const total = items.length;
  let autoPlay;

  const showSlide = (index) => {
    // Reset semua
    items.forEach(item => item.classList.remove('active'));
    indicators.forEach(ind => ind.classList.remove('active'));
    
    // Aktifkan yang dipilih
    items[index].classList.add('active');
    indicators[index].classList.add('active');
    
    // Mainkan video jika ada
    const video = items[index].querySelector('video');
    if (video) {
      // Hentikan semua video dulu
      document.querySelectorAll('.carousel-item video').forEach(v => {
        if (v !== video) v.pause();
      });
      video.currentTime = 0;
      video.play().catch(e => console.log("Autoplay blocked:", e));
    }
  };

  // Klik indikator
  indicators.forEach((ind, idx) => {
    ind.addEventListener('click', () => {
      clearInterval(autoPlay);
      currentIndex = idx;
      showSlide(currentIndex);
      startAutoPlay();
    });
  });

  // Auto-play
  const startAutoPlay = () => {
    autoPlay = setInterval(() => {
      currentIndex = (currentIndex + 1) % total;
      showSlide(currentIndex);
    }, 8000);
  };

  startAutoPlay();
});
</script>
<?php endif; ?>

</body>
</html>