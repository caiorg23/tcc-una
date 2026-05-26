@extends('layouts.site')

@section('title', 'Confirmar Agendamento - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content bottom-space">
    <div class="header">
      <div class="header-top">
        <div class="back-btn" onclick="window.location='{{ route('schedule') }}'">‹</div>
        <div class="header-title-block">
          <h2>Confirmar Agendamento</h2>
          <p>Revise os detalhes antes de finalizar</p>
        </div>
      </div>
    </div>

    <div class="progress-steps">
      <div class="progress-step active">
        <span class="step-circle">1</span>
        <span class="step-label">Seleção</span>
      </div>
      <div class="step-divider"></div>
      <div class="progress-step active">
        <span class="step-circle">2</span>
        <span class="step-label">Confirmação</span>
      </div>
    </div>

    <div class="container">
      <div class="section-title">Resumo do agendamento</div>

      <ul class="service-summary-list" style="padding-left:1rem; margin-bottom:1.5rem;">
        @foreach($schedule['services'] as $service)
          <li style="margin-bottom:0.75rem; list-style: disc inside; display:flex; justify-content:space-between; align-items:center; gap:1rem;">
            <span>{{ $service['name'] }}</span>
            <strong>{{ $service['price'] }}</strong>
          </li>
        @endforeach
      </ul>

      <div class="confirm-card">
        <div class="confirm-line">
          <span>Data</span>
          <strong>{{ \Illuminate\Support\Carbon::parse($schedule['date'])->format('d/m/Y') }}</strong>
        </div>
        <div class="confirm-line">
          <span>Horário</span>
          <strong>{{ $schedule['time'] }}</strong>
        </div>
        <div class="confirm-line">
          <span>Local</span>
          <strong>{{ $schedule['location'] }}</strong>
        </div>
        <div class="confirm-line">
          <span>Metódo de confirmação</span>
          <strong>{{ $schedule['reminder'] }}</strong>
        </div>
      </div>
    </div>

    <div class="proceed-bar fixed-bottom">
      <a href="{{ route('schedule') }}" class="btn-secondary">Voltar</a>
      <form id="confirmScheduleForm" action="{{ route('schedule.done') }}" method="POST">
        @csrf
        <button type="button" class="btn-proceed" onclick="openCancelPolicyModal()">{{ $schedule['appointment_id'] ? 'Atualizar Agendamento' : 'Confirmar Agendamento' }}</button>
      </form>
    </div>
  </div>

  @include('partials.chat-widget')

  <div id="cancelPolicyModal" class="modal-backdrop hidden" role="dialog" aria-modal="true" aria-labelledby="cancelPolicyTitle" style="display:none; align-items:center; justify-content:center; padding:1rem;">
    <div class="confirm-modal" style="background:#0a0e27; border-radius:16px; max-width:520px; width:100%; padding:24px; box-shadow:0 20px 50px rgba(0,0,0,0.18); position:relative;">
      <button type="button" class="modal-close" id="cancelPolicyClose" aria-label="Fechar" style="position:absolute; top:14px; right:14px; border:none; background:none; font-size:1.5rem; cursor:pointer;">×</button>
      <h3 id="cancelPolicyTitle">Política de cancelamento</h3>
      <p style="margin-top:1rem; line-height:1.6;">Caso precise cancelar, avise com pelo menos 3 horas de antecedência para não gerar cobrança adicional. Caso contrário, não haverá reembolso do valor já pago.</p>
      <div style="margin-top:1.5rem; display:flex; align-items:flex-start; gap:0.75rem;">
        <input type="checkbox" id="cancelPolicyAgree" style="margin-top:0.2rem;">
        <label for="cancelPolicyAgree" style="line-height:1.5;">Li e concordo com a política de cancelamento.</label>
      </div>
      <div class="modal-actions" style="margin-top:1.75rem; display:flex; justify-content:flex-end; gap:0.75rem;">
        <button type="button" class="btn-secondary" id="cancelPolicyCancel">Cancelar</button>
        <button type="button" class="btn-proceed" id="cancelPolicyConfirm" disabled>Confirmar e seguir</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function openCancelPolicyModal() {
    const modal = document.getElementById('cancelPolicyModal');
    const checkbox = document.getElementById('cancelPolicyAgree');
    const confirmButton = document.getElementById('cancelPolicyConfirm');
    if (!modal || !checkbox || !confirmButton) return;

    checkbox.checked = false;
    confirmButton.disabled = true;
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
  }

  function closeCancelPolicyModal() {
    const modal = document.getElementById('cancelPolicyModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
  }

  function updateCancelPolicyConfirm() {
    const checkbox = document.getElementById('cancelPolicyAgree');
    const confirmButton = document.getElementById('cancelPolicyConfirm');
    if (!checkbox || !confirmButton) return;
    confirmButton.disabled = !checkbox.checked;
  }

  document.addEventListener('DOMContentLoaded', function () {
    const cancelPolicyClose = document.getElementById('cancelPolicyClose');
    const cancelPolicyCancel = document.getElementById('cancelPolicyCancel');
    const cancelPolicyConfirm = document.getElementById('cancelPolicyConfirm');
    const cancelPolicyAgree = document.getElementById('cancelPolicyAgree');
    const form = document.getElementById('confirmScheduleForm');

    if (cancelPolicyClose) {
      cancelPolicyClose.addEventListener('click', closeCancelPolicyModal);
    }

    if (cancelPolicyCancel) {
      cancelPolicyCancel.addEventListener('click', closeCancelPolicyModal);
    }

    if (cancelPolicyAgree) {
      cancelPolicyAgree.addEventListener('change', updateCancelPolicyConfirm);
    }

    if (cancelPolicyConfirm && form) {
      cancelPolicyConfirm.addEventListener('click', function () {
        if (cancelPolicyAgree && cancelPolicyAgree.checked) {
          form.submit();
        }
      });
    }

    const backdrop = document.getElementById('cancelPolicyModal');
    if (backdrop) {
      backdrop.addEventListener('click', function (event) {
        if (event.target === backdrop) {
          closeCancelPolicyModal();
        }
      });
    }
  });
</script>
@endpush
@endsection
