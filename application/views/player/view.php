<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>📺 Player: <?= $signage['name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; overflow: hidden; background: #000; color: white; }
        .container { padding: 20px; height: 100vh; display: flex; flex-direction: column; }
        .layout-single { text-align: center; }
        .layout-dual { display: flex; height: 100%; }
        .layout-dual .left { flex: 1; display: flex; align-items: center; justify-content: center; }
        .layout-dual .right { flex: 1; display: flex; align-items: center; justify-content: center; background: #111; }
        .layout-video_text { display: flex; flex-direction: column; height: 100%; }
        .video-wrapper { flex: 8; background: #000; display: flex; align-items: center; justify-content: center; }
        .text-wrapper { flex: 2; padding: 20px; background: rgba(0,0,0,0.7); text-align: center; font-size: 2rem; }
        img, video { max-width: 95%; max-height: 95%; object-fit: contain; }
        h1 { font-size: 3rem; margin: 0; }
        p { font-size: 2rem; }
    </style>
</head>
<body>

<div class="container layout-<?= $signage['layout'] ?>">
    <?php $content = $signage['content']; ?>

    <?php if ($signage['layout'] === 'single'): ?>
        <div class="layout-single">
            <?php if (!empty($content['image'])): ?>
                <img src="<?= base_url('assets/uploads/'.$content['image']) ?>" alt="Gambar">
            <?php endif; ?>
            <?php if (!empty($content['text'])): ?>
                <p><?= htmlspecialchars($content['text']) ?></p>
            <?php endif; ?>
        </div>

    <?php elseif ($signage['layout'] === 'dual'): ?>
        <div class="layout-dual">
            <div class="left">
                <?php if (!empty($content['image'])): ?>
                    <img src="<?= base_url('assets/uploads/'.$content['image']) ?>" alt="Gambar">
                <?php endif; ?>
            </div>
            <div class="right">
                <?php if (!empty($content['text'])): ?>
                    <p><?= htmlspecialchars($content['text']) ?></p>
                <?php endif; ?>
            </div>
        </div>

    <?php elseif ($signage['layout'] === 'video_text'): ?>
        <div class="layout-video_text">
            <div class="video-wrapper">
                <?php if (!empty($content['video'])): ?>
                    <video autoplay muted loop playsinline>
                        <source src="<?= base_url('assets/uploads/'.$content['video']) ?>" type="video/mp4">
                        Browser tidak support video.
                    </video>
                <?php else: ?>
                    <p>Video belum diunggah</p>
                <?php endif; ?>
            </div>
            <div class="text-wrapper">
                <h2><?= !empty($content['text']) ? htmlspecialchars($content['text']) : 'Teks belum diatur' ?></h2>
            </div>
        </div>

    <?php elseif ($signage['layout'] === 'carousel'): ?>
        <div class="layout-single">
            <p>🔁 Carousel mode (simulasi: ganti konten tiap 5 detik)</p>
            <p id="carousel-text"><?= !empty($content['text']) ? htmlspecialchars($content['text']) : '...' ?></p>
        </div>
        <script>
            // Simulasi carousel sederhana
            const texts = [
                "Selamat datang di lobby utama 🏢",
                "Acara hari ini: Workshop Digital Signage 🎯",
                "Jangan lupa scan QR Code untuk feedback! 📱"
            ];
            let i = 0;
            setInterval(() => {
                document.getElementById('carousel-text').textContent = texts[i++ % texts.length];
            }, 5000);
        </script>
    <?php endif; ?>
</div>

<!-- Opsional: Auto refresh tiap 30 detik untuk update konten -->
<script>
setTimeout(() => location.reload(), 30000);
</script>

</body>
</html>