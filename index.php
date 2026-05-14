<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Diplomas Raúl</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=IMFellFrenchCanon&family=Great+Vibes&display=swap" rel="stylesheet">
</head>

<body>
  <div id="scroll-progress"></div>

  <header class="header animate-fade-in">
    <div class="logo">
      <img src="assets/imagem/logo.png" alt="Diplomas Raúl" />
      <div class="logo-text">DIPLOMAS <span>RAÚL</span></div>
    </div>
    <nav class="menu">
      <a href="#inicio">Inicio</a>
      <a href="#servicos">Servicios</a>
      <a href="#portfolio">Portafolio</a>
      <a href="#sobre">Sobre Nosotros</a>
      <a href="#agendar">Agendar</a>
      <a href="#contato">Contacto</a>
    </nav>
    <a href="#contato" class="btn-header btn-agendar">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
        <line x1="16" y1="2" x2="16" y2="6"></line>
        <line x1="8" y1="2" x2="8" y2="6"></line>
        <line x1="3" y1="10" x2="21" y2="10"></line>
      </svg>
      Reservar Sesión
    </a>
    <button class="hamburger" id="hamburger">
      <span></span><span></span><span></span>
    </button>
  </header>

  <main class="hero" id="inicio">
    <h1 class="hero-bg-text animate-fade-in">DIPLOMAS</h1>
    <section class="hero-content">
      <div class="hero-left animate-slide-up">
        <h2>Registra el <br />logro <br />que<span> marca <br />una vida.</span></h2>
        <div class="line"></div>
        <p>Fotografía profesional para diplomas escolares, graduaciones y momentos académicos especiales. Imágenes elegantes, naturales y de alta calidad para inmortalizar cada logro.</p>
        <div class="hero-buttons">
          <a href="#contato" class="btn-primary btn-agendar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            Reservar Sesión
          </a>
          <a href="#portfolio" class="btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <circle cx="8.5" cy="8.5" r="1.5"></circle>
              <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
            Ver Portafolio
          </a>
        </div>
      </div>
      <div class="hero-center animate-fade-in-slow">
        <img src="assets/imagem/raul.1.png" alt="Fotógrafo sosteniendo una cámara" />
      </div>
      <div class="hero-right animate-slide-up-delay">
        <h3>Raúl</h3>
        <div class="quote">“</div>
        <p>Cada logro <br />merece ser celebrado <br />y recordado para <br />siempre.</p>
        <div class="line"></div>
        <!-- <img src="assets/imagem/vi.jpg" alt="Imagen decorativa" class="small-image" /> -->
      </div>
    </section>
  </main>

  <section class="services" id="servicos">
    <div class="service-item reveal">
      <div class="service-icon"><svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
          <circle cx="12" cy="13" r="4"></circle>
        </svg></div>
      <h3>Fotografía <br />Profesional</h3>
      <p>Equipos de alta calidad y excelencia en cada detalle.</p>
    </div>
    <div class="service-item reveal">
      <div class="service-icon"><svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
          <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
        </svg></div>
      <h3>Sesiones <br />Individuales</h3>
      <p>Retratos que destacan tu logro con naturalidad y elegancia.</p>
    </div>
    <div class="service-item reveal">
      <div class="service-icon"><svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg></div>
      <h3>Clases y <br />Escuelas</h3>
      <p>Fotos grupales y cobertura completa de clases y eventos escolares.</p>
    </div>
    <div class="service-item reveal">
      <div class="service-icon"><svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M8 17l4 4 4-4"></path>
          <path d="M12 12v9"></path>
          <path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path>
        </svg></div>
      <h3>Entrega <br />Digital</h3>
      <p>Entrega rápida y segura en alta resolución para imprimir o compartir.</p>
    </div>
  </section>

  <section class="como-funciona" style="padding: 80px 40px; background: #061326; border-top: 1px solid rgba(255,255,255,0.05);">
    <div style="max-width: 900px; margin: 0 auto; text-align: center;">
      <h2 class="reveal" style="font-family: Georgia, serif; color: #4a7ba5; font-size: 32px; margin-bottom: 15px; text-transform: uppercase;">Cómo Funciona</h2>
      <p class="reveal" style="color: rgba(255,255,255,0.6); margin-bottom: 50px;">Tu sesión en 3 pasos simples y transparentes.</p>

      <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px;">
        <div class="reveal" style="flex: 1; min-width: 200px;">
          <div style="width: 60px; height: 60px; background: #e06d24; color: #fff; font-size: 24px; font-weight: bold; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px;">1</div>
          <h4 style="font-size: 18px; margin-bottom: 10px;">Reserva</h4>
          <p style="color: rgba(255,255,255,0.6); font-size: 14px;">Te pones en contacto por WhatsApp o Chatbot y elegimos juntos la mejor fecha y lugar.</p>
        </div>
        <div class="reveal" style="flex: 1; min-width: 200px; transition-delay: 0.2s;">
          <div style="width: 60px; height: 60px; background: #e06d24; color: #fff; font-size: 24px; font-weight: bold; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px;">2</div>
          <h4 style="font-size: 18px; margin-bottom: 10px;">La Sesión</h4>
          <p style="color: rgba(255,255,255,0.6); font-size: 14px;">Un día relajado de fotografías. Llevamos la toga, el birrete y toda la iluminación profesional.</p>
        </div>
        <div class="reveal" style="flex: 1; min-width: 200px; transition-delay: 0.4s;">
          <div style="width: 60px; height: 60px; background: #e06d24; color: #fff; font-size: 24px; font-weight: bold; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px;">3</div>
          <h4 style="font-size: 18px; margin-bottom: 10px;">Entrega</h4>
          <p style="color: rgba(255,255,255,0.6); font-size: 14px;">Las fotos pasan por una edición de color de alto nivel y se entregan en una galería digital privada.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="portfolio" id="portfolio">
    <div class="portfolio-text reveal">
      <h2>Portafolio</h2>
      <div class="line"></div>
      <p>Momentos que se convierten en recuerdos para toda la vida.</p>
      <a href="assets/documents/CATALOGO.pdf" id="pdf-link" class="portfolio-btn" target="_blank"> Ver más fotos → </a>

    </div>
    <div class="portfolio-gallery">
      <div class="portfolio-card reveal"><img src="assets/imagem/menino3.png" alt="Graduada" /></div>
      <div class="portfolio-card reveal"><img src="assets/imagem/menina2.png" alt="Birrete y diploma" /></div>
      <div class="portfolio-card reveal"><img src="assets/imagem/menino2.png" alt="Niño" /></div>
      <div class="portfolio-card reveal"><img src="assets/imagem/menina5.png" alt="Graduado" /></div>
    </div>
  </section>

  <section class="about" id="sobre">
    <div class="about-image reveal">
      <img src="assets/imagem/foto3.jpg" alt="Edificio clásico" />
    </div>
    <div class="about-text reveal">
      <h2>Sobre Nosotros</h2>
      <div class="line"></div>
      <h3>Fotografiar es <br /> eternizar logros.</h3>
      <p>Con una mirada atenta y sensibilidad, registro más que imágenes: registro historias, dedicación y victorias. Mi compromiso es entregar fotografías que transmitan orgullo y emoción en cada detalle.</p>
      <strong class="signature-font">Raúl</strong>
      <span>RAÚL — FOTÓGRAFO</span>
    </div>
    <div class="about-quote reveal">
      <div class="quote">“</div>
      <p>Cada logro merece ser celebrado y recordado para siempre. Eso es lo que hago a través de la fotografía.</p>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════════════
       SEÇÃO: FORMULÁRIO DE AGENDAMENTO PROFISSIONAL
       ═══════════════════════════════════════════════════ -->
  <section class="agendamento-section" id="agendar">
    <div class="agendamento-inner">

      <!-- Coluna esquerda: texto informativo -->
      <div class="agendamento-info reveal">
        <p class="agend-tag">Agendamento Online</p>
        <h2>Reserve <br /><span>sua sessão</span></h2>
        <div class="line"></div>
        <p class="agend-desc">
          Preencha o formulário e nossa equipe entrará em contato para confirmar
          todos os detalhes da sua sessão fotográfica de formatura.
        </p>

        <div class="agend-beneficios">
          <div class="agend-beneficio">
            <div class="agend-beneficio-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <span>Confirmação por e-mail imediata</span>
          </div>
          <div class="agend-beneficio">
            <div class="agend-beneficio-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <span>O Raúl entra em contato em até 24h</span>
          </div>
          <div class="agend-beneficio">
            <div class="agend-beneficio-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <span>Sem compromisso inicial</span>
          </div>
          <div class="agend-beneficio">
            <div class="agend-beneficio-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <span>100% gratuito para solicitar</span>
          </div>
        </div>
      </div>

      <!-- Coluna direita: formulário -->
      <div class="agendamento-form-wrap reveal">
        <div class="agend-form-card">
          <div class="agend-form-header">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Formulário de Agendamento</span>
          </div>

          <form id="form-agendamento" class="agend-form" novalidate>
            <div class="agend-form-grid">

              <div class="agend-field">
                <label for="ag-nome">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                  Nome completo <span class="agend-required">*</span>
                </label>
                <input type="text" id="ag-nome" name="nome" placeholder="Seu nome completo" required autocomplete="name">
              </div>

              <div class="agend-field">
                <label for="ag-email">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                  E-mail <span class="agend-required">*</span>
                </label>
                <input type="email" id="ag-email" name="email" placeholder="seu@email.com" required autocomplete="email">
              </div>

              <div class="agend-field">
                <label for="ag-telefone">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13 19.79 19.79 0 0 1 1.61 4.4 2 2 0 0 1 3.6 2.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.18 6.18l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                  Telefone / WhatsApp
                </label>
                <input type="tel" id="ag-telefone" name="telefone" placeholder="(75) 9 0000-0000" autocomplete="tel">
              </div>

              <div class="agend-field">
                <label for="ag-data">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                  Data da Formatura <span class="agend-required">*</span>
                </label>
                <input type="date" id="ag-data" name="data_formatura" required>
              </div>

              <div class="agend-field agend-field--full">
                <label for="ag-curso">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                  Curso / Instituição <span class="agend-required">*</span>
                </label>
                <input type="text" id="ag-curso" name="curso" placeholder="Ex: Enfermagem — UEFS" required>
              </div>

              <div class="agend-field agend-field--full">
                <label for="ag-mensagem">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                  Mensagem adicional <span class="agend-opcional">(opcional)</span>
                </label>
                <textarea id="ag-mensagem" name="mensagem" rows="3" placeholder="Alguma preferência ou dúvida? Ex: prefiro fotos em ambiente externo..."></textarea>
              </div>

            </div><!-- /grid -->

            <!-- Feedback de envio -->
            <div id="agend-feedback" class="agend-feedback hidden" role="alert" aria-live="polite"></div>

            <button type="submit" class="agend-submit" id="agend-btn-submit">
              <span class="agend-btn-text">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Solicitar Agendamento
              </span>
              <span class="agend-btn-loading hidden">
                <svg class="agend-spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2 a10 10 0 0 1 10 10" stroke-linecap="round"/></svg>
                Enviando...
              </span>
            </button>

            <p class="agend-privacy">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
              Seus dados estão seguros e nunca serão compartilhados.
            </p>
          </form>
        </div>
      </div>

    </div>
  </section>

  <section class="cta-footer" id="contato">
    <div class="cta-title reveal">
      Tu logro merece <br />
      <span>una imagen a su altura.</span>
    </div>
    <p class="reveal">Reserva tu sesión y eterniza este momento único con calidad y profesionalismo.</p>
    <a href="https://wa.me/+557587100691?text=Hola!%20Me%20gustaría%20saber%20más%20sobre%20las%20sesiones%20fotográficas." target="_blank" rel="noopener noreferrer" class="whatsapp-btn reveal pulse-animation">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.052 0C5.495 0 .16 5.333.158 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.333 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z" />
      </svg>
      Hablar por WhatsApp
    </a>
  </section>

  <footer class="footer">
    <div class="footer-logo">
      <img src="assets/imagem/logo.png" alt="Ícono Diplomas Raúl" />
      <div class="footer-logo-text">DIPLOMAS <span>RAÚL</span></div>
    </div>
    <p>© 2024 Diplomas Raúl. Todos los derechos reservados.</p>
    <span>Desarrollado con 🧡</span>
  </footer>

  <button id="chatbot-toggle" class="chatbot-toggle-btn">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
  </button>

  <div id="chatbot-container" class="chatbot-container hidden">
    <div class="chatbot-header">
      <div class="chatbot-header-info">
        <img src="assets/imagem/logo.png" alt="Asistente">
        <div>
          <strong>Asistente Raúl</strong>
          <span>En línea</span>
        </div>
      </div>

      <div class="chatbot-header-actions">
        <button id="chatbot-menu-btn" class="chat-action-btn">⋮</button>
        <button id="chatbot-close" class="chat-action-btn">✕</button>

        <div id="chatbot-dropdown" class="chatbot-dropdown hidden">
          <button id="chatbot-restart-menu">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="23 4 23 10 17 10"></polyline>
              <polyline points="1 20 1 14 7 14"></polyline>
              <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
            Reiniciar Chat
          </button>
          <div class="dropdown-divider"></div>
          <label class="dropdown-label">Atención:</label>
          <select id="mode" class="dropdown-select">
            <option value="ai" selected>Asistente IA</option>
            <option value="manual">Menú Manual</option>
          </select>
        </div>
      </div>
    </div>

    <div id="chatbot-messages" class="chatbot-messages">
      <div class="message bot">¡Hola! Soy el asistente virtual del estudio Diplomas Raúl. ¿Cómo puedo ayudarte con tu sesión hoy?
      </div>
    </div>

    <form id="chat-form" class="chatbot-input-area">
      <input type="hidden" id="visitor_name" value="Visitante">
      <input type="hidden" id="visitor_phone" value="">
      <input type="hidden" id="visitor_email" value="">

      <input type="text" id="message" placeholder="Escribe tu mensaje..." autocomplete="off" required>
      <button type="submit" id="chatbot-send">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="22" y1="2" x2="11" y2="13"></line>
          <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
        </svg>
      </button>
    </form>
  </div>

  <div id="custom-confirm" class="confirm-overlay hidden">
    <div class="confirm-card">
      <h3>¿Reiniciar Chat?</h3>
      <p>Esto borrará todo el historial de la conversación actual.</p>
      <div class="confirm-buttons">
        <button id="confirm-cancel" class="btn-cancel">Cancelar</button>
        <button id="confirm-yes" class="btn-yes">Sí, reiniciar</button>
      </div>
    </div>
  </div>

  <script src="assets/js/script.js"></script>
</body>

</html>