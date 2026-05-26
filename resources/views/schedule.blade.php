@extends('layouts.site')

@section('title', 'Agendar - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content bottom-space">
    <div class="container schedule-grid">
      <main class="schedule-main">
        <div class="header">
          <div class="header-top">
            <div class="back-btn" onclick="window.location='{{ route('home') }}'">‹</div>
            <div class="header-title-block">
              <h2>{{ isset($appointment) ? 'Editar Agendamento' : 'Agendamento' }}</h2>
              <p>{{ isset($appointment) ? 'Atualize data, hora ou serviço' : 'Escolha data, hora e serviços' }}</p>
            </div>
          </div>
          @if(isset($appointment))
            <div class="edit-note">Você está editando o agendamento de {{ date('d/m/Y', strtotime($appointment->date)) }} às {{ $appointment->time }}.</div>
          @endif
        </div>

        <div class="progress-steps schedule-progress">
          <div class="progress-track">
            <div class="progress-track-fill" id="progressLineFill"></div>
          </div>
          <div class="progress-step active" data-step="1">
            <span class="step-circle">1</span>
            <span class="step-label">Seleção</span>
          </div>
          <div class="step-divider"></div>
          <div class="progress-step" data-step="2">
            <span class="step-circle">2</span>
            <span class="step-label">Serviços</span>
          </div>
          <div class="step-divider"></div>
          <div class="progress-step" data-step="3">
            <span class="step-circle">3</span>
            <span class="step-label">Pagamento</span>
          </div>
        </div>

        <div class="schedule-panel">
          @if ($errors->any())
            <div class="alert-box">
              <strong>Por favor corrija os campos abaixo:</strong>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @php
            $appointment = $appointment ?? null;
            $selectedServiceIds = old('service_ids', ($appointment && $appointment->service_ids) ? $appointment->service_ids : (($appointment && $appointment->service_id) ? [$appointment->service_id] : []));
            if (is_string($selectedServiceIds)) {
                $selectedServiceIds = array_filter(explode(',', $selectedServiceIds));
            }
            if (is_array($selectedServiceIds)) {
                $selectedServiceIds = array_values(array_filter($selectedServiceIds, fn($id) => $id !== null && $id !== '' && $id !== []));
            }
            if (empty($selectedServiceIds)) {
              $selectedServiceIds = [];
            }
            $summaryServices = $services->whereIn('id', $selectedServiceIds)->pluck('name')->toArray();
          @endphp
          <form action="{{ route('schedule.confirm') }}" method="POST" id="scheduleForm">
            @csrf
            <input type="hidden" name="appointment_id" id="appointmentId" value="{{ $appointment?->id ?? '' }}">
            <input type="hidden" name="service_id" id="selectedServiceId" value="{{ $selectedServiceIds[0] ?? '' }}">
            <div id="serviceIdsContainer">
              @foreach($selectedServiceIds as $id)
                <input type="hidden" name="service_ids[]" value="{{ $id }}">
              @endforeach
            </div>
            <input type="hidden" id="selectedServiceIds" value="{{ implode(',', $selectedServiceIds) }}">
            <input type="hidden" name="date" id="selectedDate" value="{{ old('date', $appointment?->date ?? '') }}">
            <input type="hidden" name="time" id="selectedTime" value="{{ old('time', $appointment?->time ?? '') }}">
            <input type="hidden" name="reminder" value="whatsapp">

            <div class="schedule-step" data-step="1">
              <div class="schedule-section">
                <div class="section-title">Escolha a data</div>
                <div class="date-grid" id="dateGrid">
                  <div class="date-picker-block">
                    <input type="date" id="datePicker" class="date-picker" onchange="syncDateInput(this)" min="{{ date('Y-m-d') }}" />
                  </div>
                </div>
              </div>

              <div class="schedule-section schedule-time-section">
                <div class="section-title">Horários disponíveis</div>
                <div class="time-grid">
                  <div class="time-item busy">08:00</div>
                  <div class="time-item" onclick="selectTime(this)">09:00</div>
                  <div class="time-item" onclick="selectTime(this)">10:00</div>
                  <div class="time-item busy">11:00</div>
                  <div class="time-item" onclick="selectTime(this)">13:00</div>
                  <div class="time-item" onclick="selectTime(this)">14:00</div>
                  <div class="time-item" onclick="selectTime(this)">15:00</div>
                  <div class="time-item busy">16:00</div>
                  <div class="time-item" onclick="selectTime(this)">17:00</div>
                </div>
              </div>
            </div>

            <div class="schedule-step" data-step="2" style="display:none;">
              <div class="schedule-section">
                <div class="section-title">Serviços</div>
                <div id="scheduleServiceList" class="schedule-service-list">
                  @foreach($services as $service)
                    @php
                      $serviceIcon = $service->icon ?? '🔧';
                      $serviceBg = $service->bg ?? '#d1fae5';
                      $serviceBgClass = str_starts_with($serviceBg, '#') ? '' : $serviceBg;
                      $serviceBgStyle = str_starts_with($serviceBg, '#') ? "background: {$serviceBg};" : '';
                      $isSelected = in_array($service->id, $selectedServiceIds);
                    @endphp
                    <div class="service-card selectable{{ $isSelected ? ' selected' : '' }}" data-service-id="{{ $service->id }}" onclick="selectService(this)">
                      <div class="service-icon-box {{ $serviceBgClass }}" style="{{ $serviceBgStyle }}">
                        @if(is_string($serviceIcon) && str_starts_with($serviceIcon, 'fa'))
                          <i class="{{ $serviceIcon }}"></i>
                        @else
                          {{ $serviceIcon }}
                        @endif
                      </div>
                      <div class="service-info">
                        <div class="service-name">{{ $service->name }}</div>
                      </div>
                      <div class="radio-circle"><div class="radio-dot"></div></div>
                      <div class="service-price">{{ $service->price }}</div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="schedule-step" data-step="3" style="display:none;">
              <div class="schedule-section">
                <div class="section-title">Pagamento - Depósito de 30%</div>
                <p>Por segurança da CJOTA, é necessário efetuar 30% do valor dos serviços selecionados para confirmar o agendamento.</p>
                <div class="payment-summary">
                  <p><strong>Serviços selecionados:</strong> <span id="summaryServices">@if(!empty($summaryServices)){{ implode(', ', $summaryServices) }}@else Nenhum serviço selecionado @endif</span></p>
                  <p><strong>Total:</strong> <span id="summaryTotal">R$ 0,00</span></p>
                </div>
                <div id="paymentOptions">
                  <label style="margin-right:1rem;"><input type="radio" name="payment_method" value="pix" onchange="showPayment('pix')"> PIX</label>
                  <label><input type="radio" name="payment_method" value="card" onchange="showPayment('card')"> Cartão</label>
                </div>

                <div id="pixBlock" style="display:none; margin-top:1rem;">
                  <p id="pixAmountText"></p>
                </div>

                <div id="cardBlock" style="display:none; margin-top:1rem;">
                  <div class="card-form">
                    <input type="text" id="cardName" placeholder="Nome no cartão">
                    <input type="text" id="cardNumber" placeholder="Número do cartão">
                    <div class="card-row">
                      <input type="text" id="cardExp" placeholder="MM/AA">
                      <input type="text" id="cardCvc" placeholder="CVC">
                    </div>
                  </div>
                </div>

                <div id="paymentNotice" style="margin-top:1rem; color:#b6d8ee;">Após iniciar o pagamento, aguarde 10 segundos para permitir finalizar.</div>
                <div id="paymentTimer" style="margin-top:0.5rem; font-weight:700; display:none;">Aguardando confirmação: <span id="timer">10</span>s</div>
              </div>
              <div class="section-actions">
                <button type="button" id="startPaymentBtn" class="btn-proceed" onclick="startPayment()">Iniciar pagamento</button>
              </div>
            </div>
          </form>
        </div>
      </main>

    
    </div>

    <div class="proceed-bar fixed-bottom">
      <button type="button" id="bottomBackBtn" class="btn-secondary" onclick="prevStep()" style="display:none;">Voltar</button>
      <div class="selected-count">
        <span id="svcCountLabel">{{ count($selectedServiceIds) }}</span> serviço(s) selecionado(s)
      </div>
      <button type="button" id="bottomContinueBtn" class="btn-proceed" onclick="nextStep()">Continuar</button>
      <button type="submit" id="bottomConfirmBtn" form="scheduleForm" class="btn-proceed" style="display:none;">Confirmar</button>
    </div>
  </div>

  @include('partials.chat-widget')
  
  <!-- Payment policy modal -->
  <div id="paymentModal" class="payment-modal" style="display:none;">
    <div class="payment-modal-overlay" onclick="closePaymentModal()"></div>
    <div class="payment-modal-content">
      <h3>Política de Pagamento</h3>
      <p>Por segurança da CJOTA será cobrado 30% do valor dos serviços selecionados. Em caso de cancelamento não haverá reembolso desses 30%.</p>
      <p>Se houver um imprevisto, o cliente poderá escolher outra data e o valor já pago será mantido como crédito para o novo agendamento.</p>
    <div id="pixQrModalContainer" style="margin:0.5rem 0; display:none; text-align:center;"></div>
    <p><strong>Valor do depósito:</strong> <span class="modal-deposit">R$ 0,00</span></p>
      <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:1rem;"><button class="btn-secondary" onclick="closePaymentModal()">Fechar</button></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let currentStep = 1;
  function showStep(n){
    currentStep = n;
    document.querySelectorAll('.schedule-step').forEach(el=> el.style.display='none');
    const el = document.querySelector(`.schedule-step[data-step="${n}"]`);
    if(el) el.style.display='block';
    document.querySelectorAll('.progress-step').forEach(p=> p.classList.remove('active'));
    const prog = document.querySelector(`.progress-step[data-step="${n}"]`);
    if(prog) prog.classList.add('active');
    const bottomContinue = document.getElementById('bottomContinueBtn');
    const bottomConfirm = document.getElementById('bottomConfirmBtn');
    const bottomBack = document.getElementById('bottomBackBtn');
    if(bottomBack){
      bottomBack.style.display = n > 1 ? 'inline-block' : 'none';
    }
    if(bottomContinue && bottomConfirm){
      if(n < 3){
        bottomContinue.style.display = 'inline-block';
        bottomConfirm.style.display = 'none';
      } else {
        bottomContinue.style.display = 'none';
        bottomConfirm.style.display = 'inline-block';
      }
    }
    if(n === 3){
      updateScheduleSummary();
      const firstPaymentOption = document.querySelector('input[name="payment_method"]:checked');
      if(!firstPaymentOption){
        document.querySelectorAll('input[name="payment_method"]').forEach(r=> r.checked = false);
        document.getElementById('pixBlock').style.display = 'none';
        document.getElementById('cardBlock').style.display = 'none';
      }
    }
    const fill = document.getElementById('progressLineFill');
    if(fill){
      const percent = ((n - 1) / 2) * 100;
      fill.style.width = `${percent}%`;
    }
  }

  function nextStep(){
    if(currentStep===1){
      const date = document.getElementById('selectedDate').value;
      const time = document.getElementById('selectedTime').value;
      if(!date || !time){ alert('Selecione data e horário antes de continuar.'); return; }
    }
    if(currentStep===2){
      const selected = document.querySelectorAll('.service-card.selected');
      if(selected.length===0){ alert('Selecione pelo menos um serviço.'); return; }
    }
    showStep(Math.min(3, currentStep+1));
  }

  function prevStep(){ showStep(Math.max(1, currentStep-1)); }

  function syncDateInput(el){
    const pickedDate = el.value;
    document.getElementById('selectedDate').value = pickedDate;
    const summaryDate = document.getElementById('summaryDate');
    if(summaryDate){
      summaryDate.innerText = pickedDate ? pickedDate.split('-').reverse().join('/') : 'Selecione';
    }
    try{ localStorage.setItem('schedule_selected_date', pickedDate || ''); }catch(e){}
  }

  function selectTime(el){
    document.querySelectorAll('.time-item').forEach(i=> i.classList.remove('selected'));
    el.classList.add('selected');
    const val = el.innerText.trim();
    document.getElementById('selectedTime').value = val;
    const summaryTime = document.getElementById('summaryTime');
    if(summaryTime){
      summaryTime.innerText = val;
    }
  }

  function selectService(el){
    console.log('selectService called', el, el?.dataset?.serviceId);
    if(!el) return;
    el.classList.toggle('selected');
    const ids = Array.from(document.querySelectorAll('.service-card.selected')).map(s=> s.dataset.serviceId);
    const container = document.getElementById('serviceIdsContainer');
    container.innerHTML = ids.map(id=> `<input type="hidden" name="service_ids[]" value="${id}">`).join('');
    document.getElementById('selectedServiceIds').value = ids.join(',');
    document.getElementById('selectedServiceId').value = ids[0]||'';
    const summaryCount = document.getElementById('summaryCount');
    if(summaryCount){
      summaryCount.innerText = ids.length;
    }
    const names = Array.from(document.querySelectorAll('.service-card.selected .service-name')).map(n=> n.innerText.trim());
    const summaryServices = document.getElementById('summaryServices');
    if(summaryServices){
      summaryServices.innerText = names.join(', ') || 'Nenhum serviço selecionado';
    }
    const svcLabel = document.getElementById('svcCountLabel');
    if(svcLabel){
      svcLabel.textContent = ids.length;
    }
    // update counts and totals
    updateCount();
    updateScheduleSummary();
  }

  function formatCurrencyBR(value){
    return 'R$ ' + value.toFixed(2).replace('.', ',');
  }

  function updateScheduleSummary(){
    const serviceEls = Array.from(document.querySelectorAll('#scheduleServiceList .service-card.selected'));
    const serviceNames = serviceEls.map(el=> el.querySelector('.service-name')?.innerText.trim() || '');
    const prices = serviceEls.map(el=> parseFloat((el.querySelector('.service-price')?.innerText||'').replace(/[^0-9,\.]/g,'').replace(',','.')) || 0);
    const total = prices.reduce((a,b)=> a+b, 0);
    const summaryServices = document.getElementById('summaryServices');
    const summaryTotal = document.getElementById('summaryTotal');
    const summaryCount = document.getElementById('summaryCount');
    if(summaryServices) summaryServices.innerText = serviceNames.length>0 ? serviceNames.join(', ') : 'Nenhum serviço selecionado';
    if(summaryTotal) summaryTotal.innerText = formatCurrencyBR(total);
    if(summaryCount) summaryCount.innerText = serviceNames.length;
    // update pix amount text if present
    const pixAmountText = document.getElementById('pixAmountText');
    if(pixAmountText){
      const deposit = total * 0.3;
      pixAmountText.innerText = `Valor a pagar (30%): ${formatCurrencyBR(deposit)}`;
    }
    return total;
  }

  function toPayment(){
    // compute totals
    const prices = Array.from(document.querySelectorAll('.service-card.selected .service-price')).map(p=> parseFloat(p.innerText.replace(/[^0-9.,]/g,'').replace(',','.')) || 0);
    const total = prices.reduce((a,b)=> a+b, 0);
    const deposit = (total*0.3);
    document.getElementById('pixAmountText').innerText = `Valor a pagar (30%): ${formatCurrencyBR(deposit)}`;
    // generate placeholder QR
    generatePlaceholderQr(`PIX R$ ${deposit.toFixed(2)}`);
    showStep(3);
  }

  function showPayment(method){
    // Only toggle the visible payment blocks here.
    // The modal should NOT open on radio selection — it opens only when user clicks "Iniciar pagamento".
    document.getElementById('pixBlock').style.display = method==='pix' ? 'block' : 'none';
    document.getElementById('cardBlock').style.display = method==='card' ? 'block' : 'none';
  }

  function openPaymentModal(method){
    let modal = document.getElementById('paymentModal');
    if(!modal) return;
    const total = updateScheduleSummary();
    const deposit = (total * 0.3) || 0;
    const depositEl = modal.querySelector('.modal-deposit');
    if(depositEl) depositEl.innerText = formatCurrencyBR(deposit);
    // show QR inside modal only for PIX
    const pixQrModal = document.getElementById('pixQrModalContainer');
    if(pixQrModal){
      if(method === 'pix'){
        pixQrModal.style.display = 'block';
      } else {
        pixQrModal.style.display = 'none';
        pixQrModal.innerHTML = '';
      }
    }
    modal.style.display = 'flex';
  }

  function closePaymentModal(){
    const modal = document.getElementById('paymentModal');
    if(modal) modal.style.display = 'none';
  }

  // initialize summary on load
  try{ updateScheduleSummary(); updateCount(); }catch(e){}

  function generatePlaceholderQr(text, containerId = 'pixQrContainer', imageUrl = null){
    const container = document.getElementById(containerId);
    if(!container) return;
    container.innerHTML = '';
    // prefer a provided image URL (e.g., /images/pix-qr.png). If it fails to load, fall back to canvas placeholder.
    if(imageUrl){
      const img = new Image();
      img.onload = function(){
        img.alt = 'QR PIX';
        img.style.borderRadius = '8px';
        img.style.width = '220px';
        img.style.height = '220px';
        img.style.objectFit = 'cover';
        img.style.boxShadow = '0 6px 18px rgba(0,0,0,0.45)';
        img.style.border = '6px solid rgba(255,255,255,0.06)';
        container.style.display = 'flex';
        container.style.justifyContent = 'center';
        container.style.alignItems = 'center';
        container.style.padding = '8px 0';
        container.appendChild(img);
      };
      img.onerror = function(){
        // fallback to generated canvas
        renderCanvasPlaceholder(container, text);
      };
      img.src = imageUrl;
      return;
    }
    renderCanvasPlaceholder(container, text);
  }

  function renderCanvasPlaceholder(container, text){
    const canvas = document.createElement('canvas');
    canvas.width = 180; canvas.height = 180;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#fff'; ctx.fillRect(0,0,canvas.width,canvas.height);
    ctx.fillStyle = '#000'; ctx.fillRect(20,20,140,140);
    ctx.fillStyle = '#fff'; ctx.font = '12px sans-serif'; ctx.fillText(text, 10, 175);
    const img = document.createElement('img');
    img.src = canvas.toDataURL();
    img.alt = 'QR PIX';
    img.style.borderRadius = '8px';
    img.style.width = '220px';
    img.style.height = '220px';
    img.style.objectFit = 'cover';
    img.style.boxShadow = '0 6px 18px rgba(0,0,0,0.45)';
    img.style.border = '6px solid rgba(255,255,255,0.06)';
    container.style.display = 'flex';
    container.style.justifyContent = 'center';
    container.style.alignItems = 'center';
    container.style.padding = '8px 0';
    container.appendChild(img);
  }

  function startPayment(){
    const chosen = document.querySelector('input[name="payment_method"]:checked');
    if(!chosen){
      alert('Selecione o método de pagamento antes de iniciar.');
      return;
    }
    // Ensure the correct payment block is visible
    showPayment(chosen.value);

    // If PIX, generate the QR and update the amount now (visible in the payment section)
    const total = updateScheduleSummary();
    const deposit = total * 0.3;
    if(chosen.value === 'pix'){
      const pixAmountText = document.getElementById('pixAmountText');
      if(pixAmountText) pixAmountText.innerText = `Valor a pagar (30%): ${formatCurrencyBR(deposit)}`;
      try{ generatePlaceholderQr(`PIX R$ ${deposit.toFixed(2)}`, 'pixQrModalContainer', '/images/pix-qr.png'); }catch(e){ console.error(e); }
    }

    // Now open the payment policy modal (user clicked to start payment)
    try{ openPaymentModal(chosen.value); }catch(e){ console.error(e); }

    document.getElementById('startPaymentBtn').disabled = true;
    document.getElementById('paymentTimer').style.display = 'block';
    document.getElementById('timer').innerText = '10';
    let t = 10;
    const iv = setInterval(()=>{
      t--;
      document.getElementById('timer').innerText = t;
      if(t<=0){
        clearInterval(iv);
        const finalizeBtn = document.getElementById('finalizeBtn');
        if(finalizeBtn){
          finalizeBtn.style.display='inline-block';
          finalizeBtn.disabled = false;
        }
        document.getElementById('paymentTimer').innerText='Pagamento confirmado.';
      }
    },1000);
  }

  document.addEventListener('DOMContentLoaded', ()=>{
    const selectedValue = document.getElementById('selectedDate').value;
    const dateInput = document.getElementById('datePicker');
    const stored = (function(){ try{ return localStorage.getItem('schedule_selected_date'); }catch(e){ return null; } })();
    const initialDate = stored && stored !== '' ? stored : (selectedValue ? selectedValue : new Date().toISOString().slice(0,10));
    if(dateInput){
      // only set the input if browser didn't pre-fill or we have a stored value
      if(!dateInput.value || stored){
        dateInput.value = initialDate;
      }
      syncDateInput(dateInput);
    }
    showStep(1);
    // sync initial selected services
    // Event listeners handled by inline `onclick` attributes and by buildSchedule();
    // Avoid adding duplicate listeners here to prevent double-toggle.
    const form = document.getElementById('scheduleForm');
    if(form){
      form.addEventListener('submit', ()=>{ try{ localStorage.removeItem('schedule_selected_date'); }catch(e){} });
    }
  });

  // Defensive: remove any injected custom calendar markup and keep only the native date input
  (function(){
    function ensureDatePicker(){
      const dateGrid = document.getElementById('dateGrid');
      if(!dateGrid) return;
      // remove legacy or injected items
      Array.from(dateGrid.querySelectorAll('.date-item, .date-day, .date-num')).forEach(n=> n.remove());
      // keep or create the picker block
      let block = dateGrid.querySelector('.date-picker-block');
      if(!block){
        block = document.createElement('div');
        block.className = 'date-picker-block';
        dateGrid.appendChild(block);
      }
      // ensure single native input exists
      let input = block.querySelector('#datePicker');
      if(!input){
        input = document.createElement('input');
        input.type = 'date';
        input.id = 'datePicker';
        input.className = 'date-picker';
        input.setAttribute('onchange','syncDateInput(this)');
        block.appendChild(input);
      }
      // set initial value from localStorage or server value
      try{
        const stored = localStorage.getItem('schedule_selected_date');
        const selectedValue = document.getElementById('selectedDate').value;
        const initialDate = stored && stored !== '' ? stored : (selectedValue ? selectedValue : new Date().toISOString().slice(0,10));
        if(!input.value || stored) input.value = initialDate;
        syncDateInput(input);
      }catch(e){}

      // observe and remove any future injected nodes that match legacy classes
      const mo = new MutationObserver(muts=>{
        muts.forEach(m=>{
          m.addedNodes && m.addedNodes.forEach(n=>{
            if(n.nodeType===1 && (n.classList.contains('date-item') || n.classList.contains('date-day') || n.classList.contains('date-num'))){
              n.remove();
            }
          });
        });
      });
      mo.observe(dateGrid, { childList: true, subtree: true });
    }
    if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ensureDatePicker); else ensureDatePicker();
  })();
</script>
@endpush
