<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portafolio Completo | Diplomas Raúl</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=IMFellFrenchCanon&family=Great+Vibes&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* Estilos extras específicos para o portfólio completo */
    main {
      background: radial-gradient(circle at top, rgba(10, 17, 40, 0.8), #07101f 70%);
      min-height: 100vh;
      padding-bottom: 60px;
    }
    .portfolio-page-header {
      padding-top: 160px;
      padding-bottom: 60px;
      text-align: center;
    }
    .portfolio-page-header h1 {
      font-family: 'IMFellFrenchCanon', serif;
      font-size: 4.5rem;
      color: #fff;
      margin-bottom: 20px;
      text-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .portfolio-page-header p {
      color: rgba(255, 255, 255, 0.6);
      font-size: 1.15rem;
      max-width: 650px;
      margin: 0 auto;
      line-height: 1.6;
    }
    .portfolio-back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #e06d24;
      text-decoration: none;
      font-weight: 500;
      margin-top: 30px;
      transition: color 0.3s;
      padding: 10px 20px;
      border: 1px solid rgba(224, 109, 36, 0.5);
      border-radius: 6px;
    }
    .portfolio-back-btn:hover {
      color: #fff;
      background: #e06d24;
    }

    /* Estilos do Lightbox (Modal) */
    .lightbox-modal {
      display: none;
      position: fixed;
      z-index: 9999;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(6, 19, 38, 0.95);
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease;
      backdrop-filter: blur(5px);
    }
    .lightbox-modal.active {
      display: flex;
      opacity: 1;
    }
    .lightbox-content {
      position: relative;
      max-width: 90%;
      max-height: 90%;
    }
    .lightbox-content img {
      max-width: 100%;
      max-height: 90vh;
      border-radius: 8px;
      box-shadow: 0 15px 50px rgba(0,0,0,0.5);
      object-fit: contain;
    }
    .lightbox-close {
      position: absolute;
      top: -40px;
      right: 0;
      color: #fff;
      font-size: 40px;
      cursor: pointer;
      background: none;
      border: none;
      transition: color 0.3s;
      line-height: 1;
    }
    .lightbox-close:hover {
      color: #e06d24;
    }
    .portfolio-item {
      cursor: pointer;
    }
  </style>
</head>

<body>
  <!-- Custom Cursor -->
  <div class="cursor-dot" data-cursor-dot></div>
  <div class="cursor-outline" data-cursor-outline></div>

  <!-- Page Transition -->
  <div class="page-transition active"></div>

  <header class="header">
    <div class="logo">
      <a href="index.php" style="text-decoration:none; display:flex; align-items:center;">
        <img src="assets/imagem/logo.png" alt="Diplomas Raúl" />
        <div class="logo-text" style="color:#fff;">DIPLOMAS <span>RAÚL</span></div>
      </a>
    </div>
    <nav class="menu">
      <a href="index.php">Volver al Inicio</a>
    </nav>
    <a href="agendar.php" class="btn-header btn-agendar">Reservar Sesión</a>
  </header>

  <main>
    <div class="portfolio-page-header animate-fade-in">
      <h1>Nuestro Portafolio</h1>
      <p>Explora más ejemplos de nuestro trabajo y descubre cómo eternizamos cada momento especial con elegancia y profesionalismo.</p>
      <a href="index.php" class="portfolio-back-btn">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Volver al Inicio
      </a>
    </div>

    <div class="portfolio-full-grid animate-slide-up">
      <?php
      $images = [
        'menina1.png', 'menina2.png', 'menina3.png', 'menina4.png', 'menina5.png', 'menina6.png',
        'menino1.png', 'menino2.png', 'menino3.png', 'foto1.jpg', 'foto2.jpg', 'foto3.jpg'
      ];
      foreach ($images as $img): ?>
        <div class="portfolio-item">
          <img src="assets/imagem/<?= $img ?>" alt="Muestra de portafolio" loading="lazy" />
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <footer class="footer">
    <div class="footer-logo">
      <img src="assets/imagem/logo.png" alt="Ícono Diplomas Raúl" />
      <div class="footer-logo-text">DIPLOMAS <span>RAÚL</span></div>
    </div>
    <p>© <?= date('Y') ?> Diplomas Raúl. Todos los derechos reservados.</p>
    <span>Desarrollado con 🧡</span>
  </footer>

  <!-- Lightbox Modal -->
  <div class="lightbox-modal" id="lightbox">
    <div class="lightbox-content">
      <button class="lightbox-close" id="lightbox-close">&times;</button>
      <img src="" alt="Imagem ampliada" id="lightbox-img" />
    </div>
  </div>

  <script src="assets/js/script.js"></script>
</body>
</html>
