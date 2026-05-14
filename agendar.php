<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Agendar Sessão — Diplomas Raúl</title>
  <meta name="description" content="Reserve sua sessão fotográfica de formatura com Diplomas Raúl. Confirmação por e-mail imediata." />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=IMFellFrenchCanon&family=Great+Vibes&display=swap" rel="stylesheet">
  <style>
    /* ── Página de Agendamento ── */
    body { background: #07101f; }

    .agendar-page-header {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(7,16,31,0.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(224,109,36,0.2);
      padding: 18px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .agendar-page-header .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }
    .agendar-page-header .logo img { height: 40px; }
    .agendar-page-header .logo-text {
      font-family: Georgia, serif;
      font-size: 18px;
      color: #fff;
      letter-spacing: 2px;
      text-transform: uppercase;
    }
    .agendar-page-header .logo-text span { color: #e06d24; }
    .back-link {
      display: flex;
      align-items: center;
      gap: 8px;
      color: rgba(255,255,255,0.6);
      text-decoration: none;
      font-size: 14px;
      transition: color .2s;
    }
    .back-link:hover { color: #e06d24; }

    .agendar-hero {
      text-align: center;
      padding: 70px 20px 20px;
    }
    .agendar-hero .tag {
      display: inline-block;
      background: rgba(224,109,36,0.12);
      border: 1px solid rgba(224,109,36,0.35);
      color: #e06d24;
      font-size: 12px;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 6px 18px;
      border-radius: 20px;
      margin-bottom: 22px;
    }
    .agendar-hero h1 {
      font-family: Georgia, serif;
      font-size: clamp(32px, 5vw, 52px);
      color: #fff;
      margin: 0 0 16px;
      line-height: 1.15;
    }
    .agendar-hero h1 span { color: #e06d24; }
    .agendar-hero p {
      color: rgba(255,255,255,0.55);
      font-size: 16px;
      max-width: 520px;
      margin: 0 auto 50px;
      line-height: 1.7;
    }

    .agendar-page-inner {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 24px 80px;
      display: grid;
      grid-template-columns: 1fr 1.4fr;
      gap: 60px;
      align-items: start;
    }
    @media (max-width: 820px) {
      .agendar-page-inner { grid-template-columns: 1fr; gap: 40px; }
      .agendar-page-header { padding: 14px 20px; }
    }

    /* Info lateral */
    .agendar-side-info { position: sticky; top: 90px; }
    .agendar-side-info h2 {
      font-family: Georgia, serif;
      font-size: 28px;
      color: #fff;
      margin: 0 0 10px;
      line-height: 1.3;
    }
    .agendar-side-info h2 span { color: #e06d24; }
    .agendar-side-info .line {
      width: 50px; height: 2px;
      background: #e06d24;
      margin: 0 0 24px;
    }
    .agendar-side-info p {
      color: rgba(255,255,255,0.6);
      font-size: 15px;
      line-height: 1.75;
      margin: 0 0 36px;
    }

    .benefit-list { display: flex; flex-direction: column; gap: 14px; margin-bottom: 40px; }
    .benefit-item {
      display: flex;
      align-items: center;
      gap: 14px;
      color: rgba(255,255,255,0.8);
      font-size: 14px;
    }
    .benefit-icon {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: rgba(224,109,36,0.15);
      border: 1px solid rgba(224,109,36,0.3);
      display: flex; align-items: center; justify-content: center;
      color: #e06d24;
      flex-shrink: 0;
    }

    .whatsapp-alt {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(37,211,102,0.1);
      border: 1px solid rgba(37,211,102,0.25);
      border-radius: 10px;
      padding: 16px 20px;
      color: #25D366;
      text-decoration: none;
      font-size: 14px;
      transition: background .2s;
    }
    .whatsapp-alt:hover { background: rgba(37,211,102,0.18); }
    .whatsapp-alt svg { flex-shrink: 0; }
    .whatsapp-alt span { color: rgba(255,255,255,0.55); font-size: 12px; display: block; }

    /* Steps */
    .steps-mini {
      display: flex;
      flex-direction: column;
      gap: 0;
      margin-bottom: 36px;
    }
    .step-mini {
      display: flex;
      gap: 16px;
      align-items: flex-start;
      padding-bottom: 20px;
      position: relative;
    }
    .step-mini:not(:last-child)::after {
      content: '';
      position: absolute;
      left: 15px;
      top: 32px;
      bottom: 0;
      width: 2px;
      background: rgba(224,109,36,0.2);
    }
    .step-num {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: #e06d24;
      color: #fff;
      font-size: 13px;
      font-weight: bold;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .step-mini-text h4 {
      color: #fff;
      font-size: 14px;
      margin: 0 0 4px;
    }
    .step-mini-text p {
      color: rgba(255,255,255,0.5);
      font-size: 13px;
      margin: 0;
    }

    /* Formulário */
    .agendar-form-card {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 24px 64px rgba(0,0,0,0.4);
    }
    .agendar-form-card .agend-form-header {
      background: linear-gradient(135deg, #07101f 0%, #0d1f3c 100%);
      border-bottom: 1px solid rgba(224,109,36,0.2);
      padding: 22px 30px;
      display: flex;
      align-items: center;
      gap: 12px;
      color: #fff;
      font-size: 16px;
      font-family: Georgia, serif;
      letter-spacing: 1px;
    }
    .agendar-form-card .agend-form-header svg { color: #e06d24; }
    .agendar-form-card form { padding: 30px; }

    /* SUCCESS STATE */
    .success-screen {
      display: none;
      text-align: center;
      padding: 60px 30px;
    }
    .success-screen.visible { display: block; }
    .success-icon {
      width: 72px; height: 72px;
      border-radius: 50%;
      background: rgba(224,109,36,0.15);
      border: 2px solid #e06d24;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 24px;
      font-size: 32px;
    }
    .success-screen h3 {
      color: #fff;
      font-family: Georgia, serif;
      font-size: 24px;
      margin: 0 0 12px;
    }
    .success-screen p {
      color: rgba(255,255,255,0.6);
      font-size: 15px;
      margin: 0 0 32px;
      line-height: 1.6;
    }
    .success-back {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: rgba(255,255,255,0.5);
      text-decoration: none;
      font-size: 14px;
      border: 1px solid rgba(255,255,255,0.15);
      padding: 10px 22px;
      border-radius: 6px;
      transition: all .2s;
    }
    .success-back:hover { color: #fff; border-color: rgba(255,255,255,0.3); }

    .agendar-page-footer {
      text-align: center;
      padding: 30px;
      border-top: 1px solid rgba(255,255,255,0.06);
      color: rgba(255,255,255,0.25);
      font-size: 13px;
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="agendar-page-header">
    <a href="index.php" class="logo">
      <img src="assets/imagem/logo.png" alt="Diplomas Raúl" />
      <div class="logo-text">DIPLOMAS <span>RAÚL</span></div>
    </a>
    <a href="index.php" class="back-link">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      Volver al sitio
    </a>
  </header>

  <!-- HERO -->
  <div class="agendar-hero">
    <div class="tag">📅 Agendamento Online</div>
    <h1>Reserve sua <span>sessão fotográfica</span></h1>
    <p>Preencha o formulário e o Raúl entrará em contato em até 24h para confirmar todos os detalhes.</p>
  </div>

  <!-- CONTEÚDO PRINCIPAL -->
  <div class="agendar-page-inner">

    <!-- COLUNA ESQUERDA: info -->
    <div class="agendar-side-info">
      <h2>Como <span>funciona</span></h2>
      <div class="line"></div>

      <div class="steps-mini">
        <div class="step-mini">
          <div class="step-num">1</div>
          <div class="step-mini-text">
            <h4>Preencha o formulário</h4>
            <p>Nome, e-mail, data da formatura e curso.</p>
          </div>
        </div>
        <div class="step-mini">
          <div class="step-num">2</div>
          <div class="step-mini-text">
            <h4>Raúl entra em contato</h4>
            <p>Em até 24h para confirmar data e detalhes da sessão.</p>
          </div>
        </div>
        <div class="step-mini">
          <div class="step-num">3</div>
          <div class="step-mini-text">
            <h4>Sessão e entrega</h4>
            <p>Fotos profissionais editadas e entregues em galeria digital.</p>
          </div>
        </div>
      </div>

      <div class="benefit-list">
        <div class="benefit-item">
          <div class="benefit-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
          Confirmação por e-mail imediata
        </div>
        <div class="benefit-item">
          <div class="benefit-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
          Raúl responde em até 24h
        </div>
        <div class="benefit-item">
          <div class="benefit-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
          Sem compromisso inicial
        </div>
        <div class="benefit-item">
          <div class="benefit-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
          100% gratuito para solicitar
        </div>
      </div>

      <p style="color:rgba(255,255,255,0.4); font-size:13px; margin-bottom:16px;">Prefere falar diretamente?</p>
      <a href="https://wa.me/+557587100691?text=Olá!%20Quero%20agendar%20uma%20sessão%20fotográfica." target="_blank" class="whatsapp-alt">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.052 0C5.495 0 .16 5.333.158 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.333 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
        <div>
          <strong>Falar pelo WhatsApp</strong>
          <span>Resposta rápida</span>
        </div>
      </a>
    </div>

    <!-- COLUNA DIREITA: formulário -->
    <div>
      <div class="agendar-form-card">
        <div class="agend-form-header">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          Formulário de Agendamento
        </div>

        <!-- Formulário -->
        <form id="form-agendamento" class="agend-form" novalidate style="padding:30px;">
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

          </div>

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

        <!-- Tela de sucesso -->
        <div id="success-screen" class="success-screen" style="padding:60px 30px;">
          <div class="success-icon">🎓</div>
          <h3>Agendamento enviado!</h3>
          <p>Recebemos sua solicitação. Verifique seu e-mail — o Raúl entrará em contato em até 24h para confirmar os detalhes.</p>
          <a href="index.php" class="success-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Voltar ao site
          </a>
        </div>

      </div>
    </div>
  </div>

  <footer class="agendar-page-footer">
    © 2025 Diplomas Raúl · Todos os direitos reservados
  </footer>

  <script>
    const form = document.getElementById('form-agendamento');
    const feedback = document.getElementById('agend-feedback');
    const btnText = document.querySelector('.agend-btn-text');
    const btnLoading = document.querySelector('.agend-btn-loading');
    const btn = document.getElementById('agend-btn-submit');
    const successScreen = document.getElementById('success-screen');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      feedback.className = 'agend-feedback hidden';

      btnText.classList.add('hidden');
      btnLoading.classList.remove('hidden');
      btn.disabled = true;

      try {
        const res = await fetch('backend/agendar.php', {
          method: 'POST',
          body: new FormData(form)
        });
        const data = await res.json();

        if (data.sucesso) {
          form.classList.add('hidden');
          successScreen.classList.add('visible');
        } else {
          feedback.textContent = '✕ ' + (data.mensagem || 'Erro ao enviar. Tente novamente.');
          feedback.className = 'agend-feedback agend-feedback--erro';
        }
      } catch {
        feedback.textContent = '✕ Erro de conexão. Tente novamente.';
        feedback.className = 'agend-feedback agend-feedback--erro';
      } finally {
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
        btn.disabled = false;
      }
    });
  </script>
</body>
</html>
