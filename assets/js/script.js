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
      const footerYear = document.querySelector('.footer p');
      if (footerYear) {
        const currentYear = new Date().getFullYear();
        footerYear.innerHTML = `© ${currentYear} Diplomas Raúl. Todos os direitos reservados.`;
      }
  
      const header = document.querySelector('.header');
      const progressBar = document.getElementById('scroll-progress');
  
      window.addEventListener('scroll', () => {
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrollPercentage = (scrollTop / scrollHeight) * 100;
        
        if (progressBar) progressBar.style.width = scrollPercentage + '%';
  
        if (window.scrollY > 50) {
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }
      });
  
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
    // 3. MÓDULO DO CHATBOT (BLINDADO E COM ANIMAÇÃO)
    // ---------------------------------------------------------
    const initChatbot = () => {
      let conversationId = Number(localStorage.getItem('conversation_id') || 0);
  
      const chatBox = document.getElementById('chatbot-messages');
      const chatForm = document.getElementById('chat-form');
      const messageInput = document.getElementById('message') || document.getElementById('chatbot-input'); 
      const closeBtn = document.getElementById('chatbot-close');
      const toggleBtn = document.getElementById('chatbot-toggle');
      const container = document.getElementById('chatbot-container');
      const botSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');

      // Elementos do novo Menu de 3 pontinhos
      const menuBtn = document.getElementById('chatbot-menu-btn');
      const dropdown = document.getElementById('chatbot-dropdown');
      const restartMenuBtn = document.getElementById('chatbot-restart-menu');
      const confirmModal = document.getElementById('custom-confirm');
      const confirmYes = document.getElementById('confirm-yes');
      const confirmCancel = document.getElementById('confirm-cancel');

      // 1. Abre e fecha o menu de 3 pontinhos
      if (menuBtn && dropdown) {
        menuBtn.addEventListener('click', (e) => {
          e.stopPropagation(); // Evita que clique feche imediatamente
          dropdown.classList.toggle('hidden');
        });

        // Fecha o menu se o usuário clicar em qualquer outro lugar da tela
        document.addEventListener('click', (e) => {
          if (!dropdown.contains(e.target) && e.target !== menuBtn) {
            dropdown.classList.add('hidden');
          }
        });
      }

      if (restartMenuBtn && confirmModal) {
        // 2. Abre a confirmação quando clica em Reiniciar no Menu
        restartMenuBtn.addEventListener('click', () => {
          dropdown.classList.add('hidden'); // Fecha o menuzinho
          confirmModal.classList.remove('hidden'); // Abre a confirmação
        });

        confirmCancel.addEventListener('click', () => {
          confirmModal.classList.add('hidden');
        });

        confirmYes.addEventListener('click', () => {
          localStorage.removeItem('conversation_id');
          conversationId = 0;
          chatBox.innerHTML = '';
          addMessage('bot', 'Histórico limpo! 👋 Como posso ajudar a eternizar o seu momento hoje?');
          confirmModal.classList.add('hidden');
        });
      }
  
      if (!container || !chatBox || !chatForm || !messageInput) return;
  
      function addMessage(sender, text) {
        const div = document.createElement('div');
        div.className = `message ${sender === 'user' ? 'user' : 'bot'}`;
        div.textContent = text;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
      }
  
      async function loadHistory() {
        if (!conversationId) return;
        try {
          const response = await fetch(`backend/get_messages.php?conversation_id=${conversationId}`);
          const data = await response.json();
          if(data.messages && data.messages.length > 0) {
              chatBox.innerHTML = ''; 
              data.messages.forEach((msg) => addMessage(msg.sender, msg.message));
          }
        } catch (error) {
          console.error('Erro ao carregar histórico', error);
        }
      } catch (error) {
        console.error("Erro ao carregar histórico", error);
      }
  
      chatForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;
  
        addMessage('user', message);
        messageInput.value = '';
  
        // 👇 AQUI ENTRA A MÁGICA VISUAL DO "DIGITANDO..." 👇
        const typingId = 'typing-' + Date.now();
        const typingDiv = document.createElement('div');
        typingDiv.id = typingId;
        typingDiv.className = 'typing-indicator-container';
        typingDiv.innerHTML = `
            <div class="typing-bubble">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        chatBox.appendChild(typingDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
  
        // Blindagem Sênior: Protege contra campos ausentes no HTML
        const modeSelect = document.getElementById('mode');
        const visitorName = document.getElementById('visitor_name');
        const visitorPhone = document.getElementById('visitor_phone');
        const visitorEmail = document.getElementById('visitor_email');
  
        const payload = {
          message,
          mode: modeSelect ? modeSelect.value : 'ai',
          conversation_id: conversationId,
          visitor_name: visitorName ? visitorName.value : 'Visitante',
          visitor_phone: visitorPhone ? visitorPhone.value : '',
          visitor_email: visitorEmail ? visitorEmail.value : '',
        };
  
        try {
          const response = await fetch('backend/chatbot_response.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
          });
          
          const data = await response.json();
          
          // Apaga as bolinhas quando a resposta chega
          document.getElementById(typingId)?.remove();
  
          if (data.error) {
            addMessage('bot', data.error);
            return;
          }
          // Quando a resposta chegar com sucesso...
          conversationId = data.conversation_id;
          localStorage.setItem('conversation_id', String(conversationId));
          addMessage('bot', data.reply);
          
          // Toca o som (com catch para evitar erros se o navegador bloquear o áudio)
          botSound.play().catch(e => console.log("Áudio bloqueado temporariamente"));
        } catch (error) {
          // Se der erro de internet, apaga as bolinhas também
          document.getElementById(typingId)?.remove();
          addMessage('bot', 'Erro de conexão. Tente novamente.');
        }
      });
  
      // -----------------------------------------------------
      // BOTÃO DE FECHAR (X) - ESCONDE O CHAT E VOLTA A BOLINHA
      // -----------------------------------------------------
      closeBtn.addEventListener('click', async () => {
        container.classList.add('hidden'); 
        toggleBtn.style.display = ''; // <-- MÁGICA: A bolinha volta a aparecer!
        
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
  
      // -----------------------------------------------------
      // BOTÃO DA BOLINHA - ABRE O CHAT E ESCONDE A BOLINHA
      // -----------------------------------------------------
      toggleBtn.addEventListener('click', () => {
        container.classList.remove('hidden'); // Garante que o chat abra
        toggleBtn.style.display = 'none'; // <-- MÁGICA: A bolinha some!
        
        // Foca no campo de digitar após a animação
        setTimeout(() => messageInput.focus(), 300);
      });
  
      loadHistory();
    };
  
    // ---------------------------------------------------------
    // 4. MÓDULO DE INTERAÇÕES E CTAs (Botões e Notificações)
    // ---------------------------------------------------------
    const initInteractions = () => {
      const showToast = (message) => {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        
        toast.innerHTML = `
          <span class="toast-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          </span>
          <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
          toast.classList.remove('show');
          setTimeout(() => toast.remove(), 500); 
        }, 3500);
      };
  
      const agendarBtns = document.querySelectorAll('.btn-header, .hero-buttons .btn-primary');
      const chatContainer = document.getElementById('chatbot-container');
      const chatToggle = document.getElementById('chatbot-toggle');
      const chatInput = document.getElementById('chatbot-input') || document.getElementById('message');
  
      agendarBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault(); 
          if(chatContainer && chatContainer.classList.contains('hidden')) {
            chatToggle.click(); // Já vai acionar a nova lógica de esconder a bolinha
          }
          setTimeout(() => {
            if(chatInput) chatInput.focus();
          }, 400);
          showToast("Assistente virtual iniciado!");
        });
      });
  
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
    };
  
    // ---------------------------------------------------------
    // 5. MÓDULO DO MENU MOBILE
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
