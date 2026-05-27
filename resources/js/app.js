require('./bootstrap');

const services = window.appServices || [
  { name: 'Higienização Interna', description: 'Serviço completo de limpeza interna focado na remoção de sujeiras, odores, poeira, manchas leves e bactérias presentes no veículo. Inclui aspiração detalhada, limpeza de bancos, painéis, portas, carpetes e cantos difíceis. Deixa o interior mais limpo, cheiroso e agradável, trazendo sensação de carro novo e maior conforto no dia a dia.', price: 'R$ 80', icon: 'bi bi-stars', bg: '#ede9fe' },
  { name: 'Lavagem Externa', description: 'Limpeza rápida e eficiente da parte externa do veículo, removendo poeira, barro, manchas e sujeiras acumuladas no dia a dia. Inclui lavagem da lataria, rodas, pneus e vidros externos, deixando o carro com brilho renovado e visual muito mais bonito.', price: 'R$ 40', icon: 'bi bi-droplet', bg: '#dbeafe' },
  { name: 'Lavagem Completa', description: 'Lavagem detalhada da parte externa e interna do veículo, utilizando produtos automotivos específicos para preservar a pintura e os acabamentos. Inclui limpeza da lataria, rodas, pneus, vidros e aspiração interna. Um serviço ideal pra manter o carro sempre bonito, conservado e com aparência impecável.', price: 'R$ 110', icon: 'bi bi-bucket', bg: '' },
  { name: 'Proteção de Pintura', description: 'Aplicação de produtos protetores de alta qualidade que criam uma camada protetora sobre a pintura do veículo. Ajuda a preservar o brilho, reduz danos causados pelo sol, chuva, poluição e sujeiras do dia a dia. Além de aumentar a durabilidade da pintura, facilita futuras lavagens e mantém o carro com aspecto de recém-polido por mais tempo.', price: 'R$ 150', icon: 'bi bi-shield-lock', bg: '#fef3c7' },
  { name: 'Polimento Técnico', description: 'Processo especializado para revitalização da pintura automotiva, removendo riscos superficiais, marcas de lavagem, hologramas e queimaduras leves do verniz. O polimento devolve brilho intenso e profundidade à pintura, deixando o carro com aparência muito mais nova e sofisticada. Ideal pra quem quer recuperar o visual premium do veículo.', price: 'R$ 200', icon: 'bi bi-gem', bg: '#fbcfe8' },
  { name: 'Cristalização dos Vidros', description: 'A cristalização dos vidros cria uma camada protetora hidrofóbica que repele água, poeira e sujeiras, melhorando drasticamente a visibilidade em dias de chuva. Além de deixar os vidros com aparência mais limpa e brilhante, ajuda a evitar manchas causadas pelo tempo e reduz o acúmulo de resíduos. Ideal pra quem busca mais segurança, conforto ao dirigir e um acabamento premium no veículo.', price: 'R$ 120', icon: 'bi bi-eye', bg: '#dbeafe' },
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
    updateScheduleSummary();
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

  const pickerBlock = grid.querySelector('.date-picker-block');
  const pickerInput = pickerBlock?.querySelector('#datePicker');
  const preservedPicker = pickerBlock && pickerInput;
  const todayDate = new Date().toISOString().slice(0,10);

  grid.innerHTML = '';

  if (preservedPicker) {
    pickerInput.min = todayDate;
    const block = document.createElement('div');
    block.className = 'date-picker-block';
    block.appendChild(pickerInput);
    grid.appendChild(block);
  } else {
    const block = document.createElement('div');
    block.className = 'date-picker-block';
    const input = document.createElement('input');
    input.type = 'date';
    input.id = 'datePicker';
    input.className = 'date-picker';
    input.min = todayDate;
    input.onchange = () => syncDateInput(input);
    block.appendChild(input);
    grid.appendChild(block);
  }

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
  // If server-side already rendered service cards, do not overwrite them — only attach handlers
  if (container.querySelector('.service-card')) {
    // ensure the cards created server-side will open the modal when clicked
    const cards = container.querySelectorAll('.service-card');
    cards.forEach((card) => {
      card.addEventListener('click', () => {
        const id = card.dataset.serviceId;
        const svc = (window.appServices || []).find((x) => String(x.id) === String(id));
        if (svc) openServiceModal(svc);
      });
    });
    return;
  }

  container.innerHTML = '';
  services.forEach((service) => {
const iconMap = {
    '✨': 'bi bi-stars',
    '💧': 'bi bi-droplet',
    '🌀': 'bi bi-bucket',
    '🛡️': 'bi bi-shield-lock',
    '💎': 'bi bi-gem',
    const iconMap = {
      '✨': 'bi bi-stars',
      '💧': 'bi bi-droplet',
      '🌀': 'bi bi-bucket',
      '🛡️': 'bi bi-shield-lock',
      '💎': 'bi bi-gem',
      '👁️': 'bi bi-eye',
      '🔧': 'bi bi-tools',
    };

    const iconBg = service.bg || 'si-green';
    const iconClass = iconBg.startsWith('#') ? '' : ` ${iconBg}`;
    const iconStyle = iconBg.startsWith('#') ? `style="background:${iconBg}"` : '';

    const card = document.createElement('div');
    card.className = 'service-card selectable';
    card.style.cursor = 'pointer';
    card.setAttribute('data-service-id', service.id || '');
    card.innerHTML = `
      <div class="service-icon-box${iconClass}" ${iconStyle}>${iconHtml}</div>
      <div class="service-info">
        <div class="service-name">${service.name}</div>
        <div class="service-desc">${service.description || service.desc || ''}</div>
      </div>
      <div class="service-price">${service.price}</div>
    `;
    card.addEventListener('click', () => {
      openServiceModal(service);
    });
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
  updateScheduleSummary();
}

function getServiceName(id) {
  const service = services.find((item) => String(item.id) === String(id));
  return service ? service.name : 'Serviço';
}

function formatDatePtBR(value) {
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) {
    return value;
  }
  return date.toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
}

function updateScheduleSummary() {
  const dateValue = document.getElementById('selectedDate')?.value || '';
  const timeValue = document.getElementById('selectedTime')?.value || '';
  const serviceIds = document.getElementById('selectedServiceIds')?.value.split(',').filter(Boolean) || [];
  const summaryDate = document.getElementById('summaryDate');
  const summaryTime = document.getElementById('summaryTime');
  const summaryCount = document.getElementById('summaryCount');
  const summaryServices = document.getElementById('summaryServices');

  if (summaryDate) {
    summaryDate.textContent = dateValue ? formatDatePtBR(dateValue) : 'Selecione';
  }
  if (summaryTime) {
    summaryTime.textContent = timeValue || 'Selecione';
  }
  if (summaryCount) {
    summaryCount.textContent = serviceIds.length;
  }
  if (summaryServices) {
    summaryServices.textContent = serviceIds.length > 0 ? serviceIds.map(getServiceName).join(', ') : 'Nenhum serviço selecionado';
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
  updateScheduleSummary();
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
  updateScheduleSummary();
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

