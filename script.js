// =========================================================
// SCRIPT PRINCIPAL - ARQUITETURA MODULAR (CLEAN CODE)
// =========================================================

document.addEventListener("DOMContentLoaded", () => {

  // ---------------------------------------------------------
  // 0. ASSINATURA NO CONSOLE
  // ---------------------------------------------------------
  console.log(
    "%c 📸 Diplomas Raúl | Desenvolvido com excelência %c\nSeja bem-vindo ao console!", 
    "background: #0a1128; color: #e06d24; font-size: 16px; font-weight: bold; padding: 10px;",
    "color: #4a7ba5; font-size: 12px;"
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