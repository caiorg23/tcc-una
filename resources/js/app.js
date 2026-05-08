require('./bootstrap');

const services = window.appServices || [
  { name: 'Higienização Interna', desc: 'Limpeza profunda do interior do veículo', price: 'R$ 80', icon: '✨', bg: '#ede9fe' },
  { name: 'Lavagem Externa', desc: 'Lavagem completa da parte externa', price: 'R$ 40', icon: '💧', bg: '#dbeafe' },
  { name: 'Lavagem Completa', desc: 'Higienização interna e lavagem externa', price: 'R$ 110', icon: '🌀', bg: '#dcfce7' },
  { name: 'Proteção de Pintura', desc: 'Proteção e conservação da pintura', price: 'R$ 150', icon: '🛡️', bg: '#fef3c7' },
  { name: 'Polimento Técnico', desc: 'Polimento profissional da pintura', price: 'R$ 200', icon: '💎', bg: '#fbcfe8' },
  { name: 'Cristalização dos Vidros', desc: 'Tratamento especial para os vidros', price: 'R$ 120', icon: '👁️', bg: '#dbeafe' },
];

window.addEventListener('DOMContentLoaded', () => {
  buildSchedule();
  buildServicesList();

  const selectedTimeValue = document.getElementById('selectedTime')?.value;
  if (selectedTimeValue) {
    const selectedTimeItem = Array.from(document.querySelectorAll('.time-grid .time-item'))
      .find((item) => item.textContent.trim() === selectedTimeValue && !item.classList.contains('busy'));
    if (selectedTimeItem) {
      selectTime(selectedTimeItem);
    } else {
      const firstTime = document.querySelector('.time-grid .time-item:not(.busy)');
      if (firstTime) {
        selectTime(firstTime);
      }
    }
  } else {
    const firstTime = document.querySelector('.time-grid .time-item:not(.busy)');
    if (firstTime) {
      selectTime(firstTime);
    }
  }

  const selectedServiceIdsValue = document.getElementById('selectedServiceIds')?.value;
  if (selectedServiceIdsValue) {
    const serviceIds = selectedServiceIdsValue.split(',').filter(Boolean);
    serviceIds.forEach((id) => {
      const selectedCard = document.querySelector(`#scheduleServiceList .service-card[data-service-id="${id}"]`);
      if (selectedCard) {
        selectedCard.classList.add('selected');
      }
    });
    syncSelectedServiceInputs(serviceIds);
  }

  const navbarToggle = document.getElementById('navbarToggle');
  const navbarMenu = document.getElementById('navbarMenu');
  const navbarOverlay = document.getElementById('navbarOverlay');
  if (navbarToggle && navbarMenu) {
    navbarToggle.addEventListener('click', () => {
      navbarMenu.classList.toggle('open');
      navbarToggle.classList.toggle('open');
      if (navbarOverlay) {
        navbarOverlay.classList.toggle('open');
      }
    });
    navbarMenu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        navbarMenu.classList.remove('open');
        navbarToggle.classList.remove('open');
        if (navbarOverlay) {
          navbarOverlay.classList.remove('open');
        }
      });
    });
    if (navbarOverlay) {
      navbarOverlay.addEventListener('click', () => {
        navbarMenu.classList.remove('open');
        navbarToggle.classList.remove('open');
        navbarOverlay.classList.remove('open');
      });
    }
  }

  const profileToggle = document.getElementById('profileToggle');
  const profileDropdown = document.getElementById('profileDropdown');

  if (profileToggle && profileDropdown) {
    profileToggle.addEventListener('click', (event) => {
      event.stopPropagation();
      profileDropdown.classList.toggle('open');
    });

    window.addEventListener('click', (event) => {
      if (!profileDropdown.contains(event.target) && !profileToggle.contains(event.target)) {
        profileDropdown.classList.remove('open');
      }
    });
  }

  updateCount();
});

function buildSchedule() {
  const grid = document.getElementById('dateGrid');
  const today = new Date();
  const days = ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'];
  const maxDays = 14;
  const selectedDateValue = document.getElementById('selectedDate')?.value;

  if (!grid) {
    return;
  }

  grid.innerHTML = '';

  for (let i = 1; i <= maxDays; i += 1) {
    const d = new Date(today);
    d.setDate(today.getDate() + i);

    const dateValue = d.toISOString().slice(0, 10);
    const isSelected = selectedDateValue ? dateValue === selectedDateValue : i === 1;
    const item = document.createElement('div');
    item.className = `date-item${isSelected ? ' selected' : ''}`;
    item.innerHTML = `<div class="date-day">${days[d.getDay()]}</div><div class="date-num">${d.getDate()}</div>`;
    item.onclick = () => {
      document.querySelectorAll('.date-item').forEach((x) => x.classList.remove('selected'));
      item.classList.add('selected');
      setSelectedDate(dateValue);
    };

    grid.appendChild(item);

    if (isSelected) {
      setSelectedDate(dateValue);
    }
  }

  const svcList = document.getElementById('scheduleServiceList');
  if (svcList && svcList.children.length === 0) {
    services.forEach((service) => {
      const card = document.createElement('div');
      card.className = 'service-card selectable';
      card.dataset.serviceId = service.id || '';
      card.innerHTML = `
        <div class="service-icon-box ${service.bg || 'si-green'}">${service.icon || '??'}</div>
        <div class="service-info">
          <div class="service-name">${service.name}</div>
          <div class="service-desc">${service.description || service.desc || ''}</div>
        </div>
        <div class="service-price">${service.price}</div>
      `;

      card.onclick = () => {
        selectService(card);
      };

      svcList.appendChild(card);
    });
  }

  const selectedServiceId = document.getElementById('selectedServiceId')?.value;
  if (svcList && selectedServiceId) {
    const selectedCard = svcList.querySelector(`.service-card[data-service-id="${selectedServiceId}"]`);
    if (selectedCard) {
      selectedCard.classList.add('selected');
    }
  }
}

function buildServicesList() {
  const container = document.getElementById('servicesListContainer');
  if (!container) {
    return;
  }

  container.innerHTML = '';
  services.forEach((service) => {
    const icon = service.icon || '🔧';
    const isFaIcon = typeof icon === 'string' && icon.startsWith('fa');
    const iconHtml = isFaIcon ? `<i class="${icon}"></i>` : icon;

    const iconBg = service.bg || 'si-green';
    const iconClass = iconBg.startsWith('#') ? '' : ` ${iconBg}`;
    const iconStyle = iconBg.startsWith('#') ? `style="background:${iconBg}"` : '';

    const card = document.createElement('div');
    card.className = 'service-card';
    card.style.cursor = 'default';
    card.innerHTML = `
      <div class="service-icon-box${iconClass}" ${iconStyle}>${iconHtml}</div>
      <div class="service-info">
        <div class="service-name">${service.name}</div>
        <div class="service-desc">${service.description || service.desc || ''}</div>
      </div>
      <div class="service-price">${service.price}</div>
    `;
    container.appendChild(card);
  });
}

function updateCount() {
  const count = document.querySelectorAll('#scheduleServiceList .service-card.selectable.selected').length;
  const label = document.getElementById('svcCountLabel');
  if (label) {
    label.textContent = count;
  }
}

function syncSelectedServiceInputs(serviceIds) {
  const container = document.getElementById('serviceIdsContainer');
  const serviceIdsInput = document.getElementById('selectedServiceIds');

  if (!container || !serviceIdsInput) {
    return;
  }

  container.innerHTML = '';
  serviceIdsInput.value = serviceIds.join(',');

  serviceIds.forEach((id) => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'service_ids[]';
    input.value = id;
    container.appendChild(input);
  });
}

function setSelectedDate(value) {
  const dateInput = document.getElementById('selectedDate');
  if (dateInput) {
    dateInput.value = value;
  }
}

function selectService(el) {
  if (!el.classList.contains('selectable')) {
    return;
  }

  const serviceIdsInput = document.getElementById('selectedServiceIds');
  const serviceIdInput = document.getElementById('selectedServiceId');
  const currentIds = serviceIdsInput?.value.split(',').filter(Boolean) || [];
  const serviceId = el.dataset.serviceId || '';
  const index = currentIds.indexOf(serviceId);

  if (index >= 0) {
    currentIds.splice(index, 1);
    el.classList.remove('selected');
  } else {
    currentIds.push(serviceId);
    el.classList.add('selected');
  }

  const firstId = currentIds[0] || '';
  if (serviceIdsInput) {
    serviceIdsInput.value = currentIds.join(',');
  }
  if (serviceIdInput) {
    serviceIdInput.value = firstId;
  }
  syncSelectedServiceInputs(currentIds);

  updateCount();
}

function selectTime(el) {
  if (el.classList.contains('busy')) {
    return;
  }

  document.querySelectorAll('.time-item').forEach((item) => item.classList.remove('selected'));
  el.classList.add('selected');

  const timeInput = document.getElementById('selectedTime');
  if (timeInput) {
    timeInput.value = el.textContent.trim();
  }
}

window.selectTime = selectTime;
window.selectService = selectService;
window.toggleChat = toggleChat;
window.quickReply = quickReply;
window.sendMsg = sendMsg;

const botReplies = {
  'endereco': 'Estamos na Av. Cristiano Machado, 1395 - Silveira, Belo Horizonte - MG, CEP 31140-505. Até breve! 🚗',
  'horarios': 'Funcionamos de segunda a sexta, das 8h às 18h. Aos sábados, das 8h às 14h. ⏰',
  'servicos': 'Oferecemos: Higienização Interna, Lavagem Externa, Lavagem Completa, Proteção de Pintura, Polimento Técnico e Cristalização de Vidros. 🔧',
  'precos': 'Nossos preços começam em R$ 40 (Lavagem Externa) até R$ 200 (Polimento Técnico). Confira o app para detalhes. 💰',
  'whatsapp': 'Entre em contato pelo nosso WhatsApp: (31) 9 0000-0000 📱',
};

function normalizeText(text) {
  return text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function toggleChat() {
  const panel = document.getElementById('chatPanel');
  if (panel) {
    panel.classList.toggle('open');
  }
}

function quickReply(text) {
  addMsg(text, 'user');
  const key = normalizeText(text.toLowerCase());
  const reply = botReplies[key] || 'Obrigado pela mensagem! Em breve um atendente responderá. 🙂';
  setTimeout(() => addMsg(reply, 'bot'), 600);
}

function sendMsg() {
  const inp = document.getElementById('chatInput');
  if (!inp) {
    return;
  }

  const text = inp.value.trim();
  if (!text) {
    return;
  }

  inp.value = '';
  addMsg(text, 'user');

  const lower = normalizeText(text.toLowerCase());
  let reply = 'Entendido! Para mais informações, use os botões rápidos abaixo ou aguarde nosso atendimento. 🙂';

  Object.keys(botReplies).forEach((key) => {
    if (lower.includes(key)) {
      reply = botReplies[key];
    }
  });

  setTimeout(() => addMsg(reply, 'bot'), 700);
}

function addMsg(text, who) {
  const msgs = document.getElementById('chatMessages');
  if (!msgs) {
    return;
  }

  const message = document.createElement('div');
  message.className = who === 'bot' ? 'msg-bot' : 'msg-user';
  message.innerHTML = text;
  msgs.appendChild(message);
  msgs.scrollTop = msgs.scrollHeight;
}

