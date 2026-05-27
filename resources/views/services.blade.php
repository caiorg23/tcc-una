@extends('layouts.site')

@section('title', 'Serviços - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content">
    <div class="header">
      <div class="header-top">
        <div class="back-btn" onclick="window.location='{{ route('home') }}'">‹</div>
        <div class="header-title-block">
          <h2>Nossos Serviços</h2>
          <p>Qualidade e cuidado para seu veículo</p>
        </div>
      </div>
    </div>

    <div class="container" id="servicesListContainer">
      {{-- Server-side fallback rendering so changes show even if JS is cached/blocked --}}
      <div class="services-grid">
        @foreach($services as $s)
          <div class="service-card selectable" data-service-id="{{ $s->id }}" style="margin-bottom:8px;" onclick="(function(){ var ev = document.createEvent('HTMLEvents'); ev.initEvent('serviceCardClick', true, true); this.dispatchEvent(ev); })()">
            <div class="service-icon-box {{ $s->bg ?? '' }}">
              @php
                $serviceIcon = $s->icon ?? 'bi bi-question-circle';
                $iconMap = [
                  '✨' => 'bi bi-stars',
                  '💧' => 'bi bi-droplet',
                  '🌀' => 'bi bi-bucket',
                  '🛡️' => 'bi bi-shield-lock',
                  '💎' => 'bi bi-gem',
                  '👁️' => 'bi bi-eye',
                  '🔧' => 'bi bi-tools',
                ];
                $serviceIcon = $iconMap[$serviceIcon] ?? $serviceIcon;
                if (!is_string($serviceIcon) || (!str_starts_with($serviceIcon, 'fa') && !str_starts_with($serviceIcon, 'bi'))) {
                  $serviceIcon = 'bi bi-question-circle';
                }
              @endphp
              @if(is_string($serviceIcon) && (str_starts_with($serviceIcon, 'fa') || str_starts_with($serviceIcon, 'bi')))
                <i class="{{ $serviceIcon }}"></i>
              @else
                {{ $serviceIcon }}
              @endif
            </div>
            <div class="service-info">
              <div class="service-name">{{ $s->name }}</div>
            </div>
            <div class="service-price">{{ $s->price }}</div>
          </div>
        @endforeach
      </div>
    </div>
    
    <!-- Service detail modal -->
    <div id="serviceDetailModal" class="modal-backdrop hidden" style="display:none;">
      <div class="modal service-detail-modal" role="dialog" aria-modal="true" aria-labelledby="serviceDetailTitle">
        <button type="button" class="modal-close" id="serviceDetailClose" aria-label="Fechar">×</button>
        <div class="modal-header">
          <h2 id="serviceDetailTitle"></h2>
          <p class="modal-text" id="serviceDetailSubtitle">Detalhes do serviço selecionado</p>
        </div>
        <div class="service-detail-body">
          <p id="serviceDetailDesc" class="modal-text"></p>
          <div class="service-detail-price">Preço: <strong id="serviceDetailPrice"></strong></div>
        </div>
        <div class="modal-actions" style="margin-top:1.5rem; justify-content:flex-end;">
          <button type="button" class="btn-secondary" id="serviceDetailCancel">Fechar</button>
          <a href="#" id="serviceDetailSchedule" class="btn-proceed">Agendar</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    window.appServices = @json($services);

    (function(){
      function renderServices(){
        const container = document.getElementById('servicesListContainer');
        if(!container || !window.appServices) return;
        // if server-side already rendered cards, do not overwrite — just attach handlers
        if(container.querySelector('.service-card')) return attachHandlers();
        container.innerHTML = '';
        const grid = document.createElement('div');
        grid.className = 'services-grid';
        window.appServices.forEach(s=>{
          const card = document.createElement('div');
          card.className = 'service-card selectable';
          card.setAttribute('data-service-id', s.id);
          card.style.marginBottom = '8px';
          const iconMap = {
            '✨': 'bi bi-bucket',
            '💧': 'bi bi-droplet',
            '🌀': 'bi bi-brush',
            '🛡️': 'bi bi-shield-lock',
            '💎': 'bi bi-stars',
            '👁️': 'bi bi-eye',
            '🔧': 'bi bi-tools',
          };
          const rawIcon = s.icon || 'bi bi-question-circle';
          let serviceIcon = iconMap[rawIcon] || rawIcon;
          if (typeof serviceIcon !== 'string' || (!serviceIcon.startsWith('fa') && !serviceIcon.startsWith('bi'))) {
            serviceIcon = 'bi bi-question-circle';
          }
          const serviceIconHtml = `<i class="${serviceIcon}"></i>`;
          card.innerHTML = `
            <div class="service-icon-box ${s.bg ?? ''}">${serviceIconHtml}</div>
            <div class="service-info">
              <div class="service-name">${s.name}</div>
            </div>
            <div class="service-price">${s.price}</div>
          `;
          card.addEventListener('click', ()=> openServiceModal(s));
          grid.appendChild(card);
        });
        container.appendChild(grid);
      }

      function attachHandlers(){
        const cards = document.querySelectorAll('#servicesListContainer .service-card');
        cards.forEach(card=>{
          card.addEventListener('click', function(){
            const id = this.dataset.serviceId;
            const svc = (window.appServices || []).find(x=> String(x.id) === String(id));
            if(svc) openServiceModal(svc);
            else {
              // fallback: make an AJAX request for details (not implemented)
            }
          });
        });
      }

      function openServiceModal(s){
        const modal = document.getElementById('serviceDetailModal');
        if(!modal) return;
        document.getElementById('serviceDetailTitle').innerText = s.name;
        document.getElementById('serviceDetailDesc').innerText = s.description || s.description_short || '';
        document.getElementById('serviceDetailPrice').innerText = s.price || '';
        const scheduleLink = document.getElementById('serviceDetailSchedule');
        if(scheduleLink) scheduleLink.href = `{{ route('schedule') }}?service=${s.id}`;
        modal.classList.remove('hidden'); modal.style.display = 'flex'; modal.setAttribute('aria-hidden','false');
      }

      function closeServiceModal(){
        const modal = document.getElementById('serviceDetailModal');
        if(!modal) return; modal.classList.add('hidden'); modal.style.display = 'none'; modal.setAttribute('aria-hidden','true');
      }

      document.addEventListener('DOMContentLoaded', ()=>{
        renderServices();
        // If for some reason the container is emptied by another script,
        // retry rendering a few times to ensure the UI shows the services.
        const container = document.getElementById('servicesListContainer');
        let attempts = 0;
        const retry = setInterval(()=>{
          attempts++;
          if(!container) return clearInterval(retry);
          if(!container.querySelector('.service-card')){
            renderServices();
          } else {
            clearInterval(retry);
          }
          if(attempts>6) clearInterval(retry);
        }, 300);
        const close = document.getElementById('serviceDetailClose');
        const cancel = document.getElementById('serviceDetailCancel');
        const backdrop = document.getElementById('serviceDetailModal');
        if(close) close.addEventListener('click', closeServiceModal);
        if(cancel) cancel.addEventListener('click', closeServiceModal);
        if(backdrop) backdrop.addEventListener('click', (e)=>{ if(e.target===backdrop) closeServiceModal(); });
      });
      // expose for other scripts
      window.openServiceModal = openServiceModal;
      window.closeServiceModal = closeServiceModal;
      window.attachServiceHandlers = attachHandlers;
      window.renderServices = renderServices;
    })();
  </script>

  @include('partials.chat-widget')
</div>
@endsection
