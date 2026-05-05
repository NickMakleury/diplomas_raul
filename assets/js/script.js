  // =========================================================
  // SCRIPT PRINCIPAL - ARQUITETURA MODULAR (CLEAN CODE)
  // =========================================================

  document.addEventListener("DOMContentLoaded", () => {
    
    // ---------------------------------------------------------
    // 0. ASSINATURA DE DEV SÊNIOR NO CONSOLE (EASTER EGG)
    // ---------------------------------------------------------
    console.log(
      "%c 📸 Diplomas Raúl | Desenvolvido com excelência %c\nSeja bem-vindo ao console! Código estruturado com padrões modulares, ES6+ e foco em UX/A11y.", 
      "background: #0a1128; color: #e06d24; font-size: 16px; font-weight: bold; border: 1px solid #e06d24; border-radius: 4px; padding: 10px;",
      "color: #4a7ba5; font-size: 12px; font-style: italic; padding-top: 5px;"
    );

    // ---------------------------------------------------------
    // 1. MÓDULO DE INTERFACE GERAL (Header, Progresso, Ano)
    // ---------------------------------------------------------
    const initUI = () => {
      // 1.1 Atualizar ano do Footer dinamicamente
      const footerYear = document.querySelector('.footer p');
      if (footerYear) {
        const currentYear = new Date().getFullYear();
        footerYear.innerHTML = `© ${currentYear} Diplomas Raúl. Todos os direitos reservados.`;
      }

      // 1.2 Barra de Progresso de Scroll e Header Dinâmico
      const header = document.querySelector('.header');
      const progressBar = document.getElementById('scroll-progress');

      window.addEventListener('scroll', () => {
        // Cálculo da barra de progresso
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrollPercentage = (scrollTop / scrollHeight) * 100;
        
        if (progressBar) progressBar.style.width = scrollPercentage + '%';

        // Efeito do Header
        if (window.scrollY > 50) {
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }
      });

      // 1.3 Smooth Scroll Avançado para links âncora
      document.querySelectorAll('.menu a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          if (targetId === '#') return;
          
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 80,
              behavior: 'smooth'
            });
          }
        });
      });
    };

    // ---------------------------------------------------------
    // 2. MÓDULO DE EFEITOS ESPECIAIS (Scroll Reveal & Parallax)
    // ---------------------------------------------------------
    const initEffects = () => {
      // 2.1 Intersection Observer (Aparição no Scroll)
      const reveals = document.querySelectorAll(".reveal");
      const revealOnScroll = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("active");
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.15 });

      reveals.forEach(reveal => revealOnScroll.observe(reveal));
    };

    // ---------------------------------------------------------
    // 3. MÓDULO DO CHATBOT (INTEGRADO AO BACKEND DO PROFESSOR)
    // ---------------------------------------------------------
    const initChatbot = () => {
      let conversationId = Number(localStorage.getItem('conversation_id') || 0);

      const chatBox = document.getElementById('chatbot-messages');
      const chatForm = document.getElementById('chat-form');
      const messageInput = document.getElementById('message');
      const modeSelect = document.getElementById('mode');
      const closeBtn = document.getElementById('chatbot-close');
      const toggleBtn = document.getElementById('chatbot-toggle');
      const container = document.getElementById('chatbot-container');

      if (!container || !chatBox || !chatForm) return;

      // Função para adicionar mensagem na tela (Adaptada para nosso CSS)
      function addMessage(sender, text) {
        const div = document.createElement('div');
        div.className = `message ${sender === 'user' ? 'user' : 'bot'}`;
        div.textContent = text;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
      }

      // Carrega histórico do Banco de Dados
      async function loadHistory() {
        if (!conversationId) return;
        try {
          const response = await fetch(`backend/get_messages.php?conversation_id=${conversationId}`);
          const data = await response.json();
          if(data.messages && data.messages.length > 0) {
              chatBox.innerHTML = ''; // Limpa saudação se tiver histórico
              data.messages.forEach((msg) => addMessage(msg.sender, msg.message));
          }
        } catch (error) {
          console.error('Erro ao carregar histórico', error);
        }
      }

      // Envio de formulário para o PHP
      chatForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;

        addMessage('user', message);
        messageInput.value = '';

        // Animação de digitação
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot typing-indicator';
        typingDiv.innerHTML = '<span></span><span></span><span></span>';
        chatBox.appendChild(typingDiv);
        chatBox.scrollTop = chatBox.scrollHeight;

        const payload = {
          message,
          mode: modeSelect.value,
          conversation_id: conversationId,
          visitor_name: document.getElementById('visitor_name').value,
          visitor_phone: document.getElementById('visitor_phone').value,
          visitor_email: document.getElementById('visitor_email').value,
        };

        try {
          const response = await fetch('backend/chatbot_response.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
          });
          const data = await response.json();
          
          typingDiv.remove(); // Remove animação

          if (data.error) {
            addMessage('bot', data.error);
            return;
          }

          conversationId = data.conversation_id;
          localStorage.setItem('conversation_id', String(conversationId));
          addMessage('bot', data.reply);
        } catch (error) {
          typingDiv.remove();
          addMessage('bot', 'Erro de conexão com o servidor.');
        }
      });

      // Botão de fechar conversa (No nosso layout, é o X do chat)
      closeBtn.addEventListener('click', async () => {
        container.classList.add('hidden'); // Esconde o chat visualmente
        
        if (!conversationId) return;
        try {
          await fetch('backend/close_conversation.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ conversation_id: conversationId }),
          });
          localStorage.removeItem('conversation_id');
          conversationId = 0;
          chatBox.innerHTML = '<div class="message bot">Sessão encerrada. Quando quiser, envie um "Oi" para recomeçar.</div>';
        } catch (error) {}
      });

      // Abrir/Fechar visual
      toggleBtn.addEventListener('click', () => {
        container.classList.toggle('hidden');
        if (!container.classList.contains('hidden')) {
          setTimeout(() => messageInput.focus(), 300);
        }
      });

      // Inicia histórico
      loadHistory();
    };

    // ---------------------------------------------------------
    // 4. MÓDULO DE INTERAÇÕES E CTAs (Botões e Notificações)
    // ---------------------------------------------------------
    const initInteractions = () => {
      
      // Sistema Avançado de Toast (Substitui o "alert" feio do navegador)
      const showToast = (message) => {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        
        // Ícone SVG + Mensagem
        toast.innerHTML = `
          <span class="toast-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          </span>
          <span>${message}</span>
        `;
        
        document.body.appendChild(toast);

        // Anima a entrada após um breve momento
        setTimeout(() => toast.classList.add('show'), 10);

        // Remove após 3.5 segundos
        setTimeout(() => {
          toast.classList.remove('show');
          setTimeout(() => toast.remove(), 500); // Espera a animação terminar para apagar do HTML
        }, 3500);
      };

      // Ação 1: Botões "Agendar" abrem o Chatbot automaticamente
      const agendarBtns = document.querySelectorAll('.btn-header, .hero-buttons .btn-primary');
      const chatContainer = document.getElementById('chatbot-container');
      const chatToggle = document.getElementById('chatbot-toggle');
      const chatInput = document.getElementById('chatbot-input');

      agendarBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault(); // Impede que a tela pule pro topo (href="#")
          
          // Se o chat estiver fechado, clica no botão de abrir
          if(chatContainer && chatContainer.classList.contains('hidden')) {
            chatToggle.click();
          }
          
          // Dá um pequeno atraso e foca no campo de digitar para o usuário
          setTimeout(() => {
            if(chatInput) chatInput.focus();
          }, 400);

          showToast("Assistente virtual iniciado!");
        });
      });

      // Ação 2: Botão "Ver Portfólio" (no topo) rola até as fotos
      const btnVerPortfolioHero = document.querySelector('.hero-buttons .btn-outline');
      if(btnVerPortfolioHero) {
        btnVerPortfolioHero.addEventListener('click', (e) => {
          e.preventDefault();
          const portfolioSection = document.getElementById('portfolio');
          if(portfolioSection) {
            window.scrollTo({
              top: portfolioSection.offsetTop - 80,
              behavior: 'smooth'
            });
          }
        });
      }

      // Ação 3: Botão "Ver mais fotos" simula carregamento
      const btnMaisFotos = document.querySelector('.portfolio-btn');
      if(btnMaisFotos) {
        btnMaisFotos.addEventListener('click', (e) => {
          e.preventDefault();
          showToast("A galeria completa está sendo preparada. Volte em breve!");
        });
      }
    };

    // ---------------------------------------------------------
    // 5. MÓDULO DO MENU MOBILE (Isto estava faltando!)
    // ---------------------------------------------------------
    const initMobileMenu = () => {
      const hamburger = document.querySelector('.hamburger');
      const menu = document.querySelector('.menu');
      const menuLinks = document.querySelectorAll('.menu a');

      if (hamburger && menu) {
        hamburger.addEventListener('click', () => {
          hamburger.classList.toggle('active');
          menu.classList.toggle('active');
        });

        menuLinks.forEach(link => {
          link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            menu.classList.remove('active');
          });
        });
      }
    };

    // =========================================================
    // BOOTSTRAP - INICIALIZAÇÃO DA APLICAÇÃO
    // =========================================================
    initUI();
    initEffects();
    initChatbot();
    initInteractions();
    initMobileMenu();

  });