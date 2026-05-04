// =========================================================
// SCRIPT PRINCIPAL - ARQUITETURA MODULAR (CLEAN CODE)
// =========================================================

document.addEventListener("DOMContentLoaded", () => {

  // ---------------------------------------------------------
  // 0. ASSINATURA NO CONSOLE
  // ---------------------------------------------------------
  console.log(
    "%c 📸Diplomas Raúl | Desenvolvido com excelência %c\nSeja bem-vindo ao console! Código estruturado com padrões modulares, ES6+ e foco em UX/A11y.", 
    "background: #0a1128; color: #e06d24; font-size: 16px; font-weight: bold; border: 1px solid #e06d24; border-radius: 4px; padding: 10px;",
    "color: #4a7ba5; font-size: 12px; font-style: italic; padding-top: 5px;"
  );

  // ---------------------------------------------------------
  // 1. UI
  // ---------------------------------------------------------
  const initUI = () => {
    const footerYear = document.querySelector('.footer p');
    if (footerYear) {
      footerYear.innerHTML = `© ${new Date().getFullYear()} Diplomas Raúl.`;
    }

    const header = document.querySelector('.header');
    const progressBar = document.getElementById('scroll-progress');

    window.addEventListener('scroll', () => {
      const scrollTop = document.documentElement.scrollTop;
      const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const scrollPercentage = (scrollTop / scrollHeight) * 100;

      if (progressBar) progressBar.style.width = scrollPercentage + '%';

      if (header) {
        header.classList.toggle('scrolled', window.scrollY > 50);
      }
    });

    document.querySelectorAll('.menu a[href^="#"]').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          window.scrollTo({
            top: target.offsetTop - 80,
            behavior: 'smooth'
          });
        }
      });
    });
  };

  // ---------------------------------------------------------
  // 2. EFEITOS
  // ---------------------------------------------------------
  const initEffects = () => {
    const reveals = document.querySelectorAll(".reveal");

    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("active");
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    reveals.forEach(el => observer.observe(el));
  };

  // ---------------------------------------------------------
  // 3. CHATBOT
  // ---------------------------------------------------------
  const initChatbot = () => {
    const DOM = {
      toggleBtn: document.getElementById('chatbot-toggle'),
      closeBtn: document.getElementById('chatbot-close'),
      container: document.getElementById('chatbot-container'),
      sendBtn: document.getElementById('chatbot-send'),
      input: document.getElementById('chatbot-input'),
      messages: document.getElementById('chatbot-messages')
    };

    if (!DOM.container) return;

    let isTyping = false;

    const addMessage = (text, sender) => {
      const div = document.createElement('div');
      div.className = `message ${sender}`;
      div.textContent = text;
      DOM.messages.appendChild(div);
      DOM.messages.scrollTop = DOM.messages.scrollHeight;
    };

    const knowledgeBase = [
      { keywords: /preço|valor/i, response: "Chama no WhatsApp para orçamento 😉" },
      { keywords: /agendar/i, response: "Vamos agendar! Clique no botão do WhatsApp 👍" }
    ];

    const process = (text) => {
      const match = knowledgeBase.find(k => k.keywords.test(text));
      return match ? match.response : "Fala comigo no WhatsApp que te ajudo melhor!";
    };

    const send = () => {
      const text = DOM.input.value.trim();
      if (!text || isTyping) return;

      addMessage(text, 'user');
      DOM.input.value = '';
      isTyping = true;

      setTimeout(() => {
        addMessage(process(text), 'bot');
        isTyping = false;
      }, 1000);
    };

    DOM.sendBtn.addEventListener('click', send);
    DOM.input.addEventListener('keypress', e => {
      if (e.key === 'Enter') send();
    });

    DOM.toggleBtn.addEventListener('click', () => {
      DOM.container.classList.toggle('hidden');
    });

    DOM.closeBtn.addEventListener('click', () => {
      DOM.container.classList.add('hidden');
    });
  };

  // ---------------------------------------------------------
  // 4. INTERAÇÕES
  // ---------------------------------------------------------
  const initInteractions = () => {

    const showToast = (msg) => {
      const toast = document.createElement('div');
      toast.className = 'toast-notification';
      toast.textContent = msg;
      document.body.appendChild(toast);

      setTimeout(() => toast.classList.add('show'), 10);
      setTimeout(() => toast.remove(), 3000);
    };

    document.querySelectorAll('.btn-agendar').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        showToast("Abrindo assistente...");
      });
    });
  };

  // ---------------------------------------------------------
  // INIT
  // ---------------------------------------------------------
  initUI();
  initEffects();
  initChatbot();
  initInteractions();

});
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
      });""
    }, { threshold: 0.15 });

};
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

    const hero = document.querySelector('.hero');
    const heroImg = document.querySelector('.hero-center img');
    const heroText = document.querySelector('.hero-bg-text');

    if (hero && heroImg && heroText) {
      
      hero.addEventListener('mouseleave', () => {
        heroImg.style.transition = 'transform 0.6s ease-out';
        heroText.style.transition = 'transform 0.6s ease-out';
        heroImg.style.transform = `scale(1.07) translate(0px, 0px)`;
        heroText.style.transform = `translate(0px, 0px)`;
      });

      hero.addEventListener('mouseenter', () => {
        heroImg.style.transition = 'none';
        heroText.style.transition = 'none';
      });
    }
  };

  // ---------------------------------------------------------
  // 3. MÓDULO DO CHATBOT INTELIGENTE (Contexto e RegEx)
  // ---------------------------------------------------------
  const initChatbot = () => {
    const DOM = {
      toggleBtn: document.getElementById('chatbot-toggle'),
      closeBtn: document.getElementById('chatbot-close'),
      container: document.getElementById('chatbot-container'),
      sendBtn: document.getElementById('chatbot-send'),
      input: document.getElementById('chatbot-input'),
      messages: document.getElementById('chatbot-messages')
    };

    if (!DOM.container) return; 
    let isTyping = false;

    const getGreeting = () => {
      const hour = new Date().getHours();
      if (hour >= 5 && hour < 12) return 'Bom dia';
      if (hour >= 12 && hour < 18) return 'Boa tarde';
      return 'Boa noite';
    };

    const firstBotMessage = DOM.messages.querySelector('.message.bot');
    if (firstBotMessage) {
      firstBotMessage.textContent = `${getGreeting()}! Sou o assistente virtual do estúdio Diplomas Raúl. Como posso ajudar com seu ensaio hoje?`;
    }

    const knowledgeBase = [
      { keywords: /preço|valor|custa|orçamento|pagar/i, response: "Nossos pacotes variam de acordo com o estilo e tempo de ensaio. Para te passar um orçamento exato e justo, me chame no WhatsApp clicando no botão abaixo da seção Sobre!" },
      { keywords: /agendar|marcar|data|horário|dia/i, response: "Excelente escolha! Para agendarmos a melhor data, por favor, me chame no WhatsApp. Lá nossa equipe verifica a agenda em tempo real." },
      { keywords: /onde|local|estúdio|cidade|endereço/i, response: "Temos nosso estúdio climatizado próprio, mas também adoramos realizar ensaios externos (universidades, parques). Onde você gostaria de fotografar?" },
      { keywords: /foto|portfolio|trabalho|ver/i, response: "Você pode conferir muito mais do nosso trabalho na seção 'Portfólio' rolando a página, ou visitar nosso perfil no Instagram!" }
    ];

    const defaultResponse = "Compreendo! Para te dar um atendimento mais personalizado e tirar todas as suas dúvidas, que tal conversarmos direto no WhatsApp?";

    const scrollToBottom = () => DOM.messages.scrollTop = DOM.messages.scrollHeight;

    const addMessage = (text, sender) => {
      const msgDiv = document.createElement('div');
      msgDiv.classList.add('message', sender);
      msgDiv.textContent = text;
      DOM.messages.appendChild(msgDiv);
      scrollToBottom();
    };

    const addTypingIndicator = () => {
      const indicator = document.createElement('div');
      indicator.classList.add('message', 'bot', 'typing-indicator');
      indicator.innerHTML = '<span></span><span></span><span></span>';
      indicator.id = 'typing-indicator';
      DOM.messages.appendChild(indicator);
      scrollToBottom();
    };

    const removeTypingIndicator = () => {
      const indicator = document.getElementById('typing-indicator');
      if (indicator) indicator.remove();
    };

    const processUserMessage = (text) => {
      const match = knowledgeBase.find(intent => intent.keywords.test(text));
      return match ? match.response : defaultResponse;
    };

    const handleSend = () => {
      const text = DOM.input.value.trim();
      if (text === '' || isTyping) return;

      addMessage(text, 'user');
      DOM.input.value = '';
      isTyping = true;

      setTimeout(() => {
        addTypingIndicator();
        setTimeout(() => {
          removeTypingIndicator();
          addMessage(processUserMessage(text), 'bot');
          isTyping = false;
        }, 1500 + Math.random() * 1000); 
      }, 500);
    };

    const toggleChat = (forceClose = false) => {
      if (forceClose) {
        DOM.container.classList.add('hidden');
      } else {
        DOM.container.classList.toggle('hidden');
        if (!DOM.container.classList.contains('hidden')) DOM.input.focus();
      }
    };

    DOM.toggleBtn.addEventListener('click', () => toggleChat());
    DOM.closeBtn.addEventListener('click', () => toggleChat(true));
    DOM.sendBtn.addEventListener('click', handleSend);
    DOM.input.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') handleSend();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !DOM.container.classList.contains('hidden')) toggleChat(true);
    });
  };

  // ---------------------------------------------------------
  // 4. MÓDULO DE INTERAÇÕES E CTAs (Botões e Notificações)
  // ---------------------------------------------------------
  const initInteractions = () => {
    
    // Sistema Avançado de Toast 
    const showToast = (message) => {
      const existingToast = document.querySelector('.toast-notification');
      if (existingToast) existingToast.remove();

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

    // Smooth Scroll para Menu e Botão "Ver Portfólio"
    const scrollLinks = document.querySelectorAll('.menu a, a[href="#portfolio"]');
    
    scrollLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        
        if (targetId === '#inicio') {
          window.scrollTo({ top: 0, behavior: 'smooth' });
          return;
        }

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          const headerOffset = 95; 
          const elementPosition = targetElement.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
    
          window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
          });
        }
      });
    });

    // Ação dos Botões "Agendar" (Abre o Chatbot)
    const agendarBtns = document.querySelectorAll('.btn-agendar');
    const chatContainer = document.getElementById('chatbot-container');
    const chatToggle = document.getElementById('chatbot-toggle');
    const chatInput = document.getElementById('chatbot-input');

    agendarBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault(); 
        if(chatContainer && chatContainer.classList.contains('hidden')) {
          chatToggle.click();
        }
        setTimeout(() => {
          if(chatInput) chatInput.focus();
        }, 400);

        showToast("Assistente virtual iniciado!");
      });
    });

    // Ação do Botão "Ver mais fotos"
    const btnMaisFotos = document.querySelector('.portfolio-btn');
    if(btnMaisFotos) {
      btnMaisFotos.addEventListener('click', (e) => {
        e.preventDefault();
        showToast("A galeria completa está sendo preparada. Volte em breve!");
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

});