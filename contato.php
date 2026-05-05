<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contato - Diplomas Raúl</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=IMFellFrenchCanon&family=Great+Vibes&display=swap" rel="stylesheet">
  </head>
  <body style="padding-top: 150px;">
    <div id="scroll-progress"></div>

    <header class="header scrolled">
      <div class="logo">
        <img src="assets/imagem/logo.png" alt="Diplomas Raúl" />
        <div class="logo-text">DIPLOMAS <span>RAÚL</span></div>
      </div>
      <nav class="menu">
        <a href="index.php">Início</a>
        <a href="servicos.php">Serviços</a>
        <a href="sobre.php">Sobre</a>
        <a href="contato.php">Contato</a>
      </nav>
      <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
    </header>

    <section class="cta-footer" id="contato">
      <div class="cta-title reveal">
        Sua conquista merece <br />
        <span>uma imagem à altura.</span>
      </div>
      <p class="reveal">Agende seu ensaio e eternize este momento único com qualidade e profissionalismo.</p>
      <a href="https://wa.me/5500000000000" class="whatsapp-btn reveal pulse-animation">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.052 0C5.495 0 .16 5.333.158 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.333 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
        Falar no WhatsApp
      </a>
    </section>

    <button id="chatbot-toggle" class="chatbot-toggle-btn">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
    </button>
    <div id="chatbot-container" class="chatbot-container hidden">
      <div class="chatbot-header">
        <div class="chatbot-header-info">
          <img src="assets/imagem/logo.png" alt="Assistente">
          <div>
            <strong>Assistente Raúl</strong>
            <span>Online</span>
          </div>
        </div>
        <button id="chatbot-close">✕</button>
      </div>
      <div id="chatbot-messages" class="chatbot-messages">
        <div class="message bot">
          Olá! Sou o assistente virtual do estúdio Diplomas Raúl. Como posso ajudar com seu ensaio hoje?
        </div>
      </div>
      <form id="chat-form" class="chatbot-input-area" style="flex-wrap: wrap;">
        <input type="hidden" id="visitor_name" value="Visitante">
        <input type="hidden" id="visitor_phone" value="">
        <input type="hidden" id="visitor_email" value="">
        
        <select id="mode" style="width: 100%; margin-bottom: 5px; background: #0a1128; color: #4a7ba5; border: 1px solid rgba(255,255,255,0.1); border-radius: 5px; padding: 5px;">
          <option value="manual">Modo Manual (Menu)</option>
          <option value="ai">Inteligência Artificial</option>
        </select>

        <div style="display: flex; width: 100%; gap: 10px;">
          <input type="text" id="message" placeholder="Digite sua mensagem..." autocomplete="off" required>
          <button type="submit" id="chatbot-send">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
          </button>
        </div>
      </form>
    </div>

    <footer class="footer" style="margin-top: 50px;">
      <div class="footer-logo">
        <img src="assets/imagem/logo.png" alt="Ícone Diplomas Raúl" />
        <div class="footer-logo-text">DIPLOMAS <span>RAÚL</span></div>
      </div>
      <p>© 2024 Diplomas Raúl. Todos os direitos reservados.</p>
      <span>Desenvolvido com 🧡</span>
    </footer>

    <script src="assets/js/script.js"></script>
  </body>
</html>