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

  <!-- Cancel Policy Modal -->
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

  <!-- Review Modal -->
  <div id="reviewModalBackdrop" class="modal-backdrop hidden" aria-hidden="true" style="display:none;">
    <div class="review-modal" role="dialog" aria-modal="true" aria-labelledby="reviewModalTitle">
      <button type="button" class="modal-close" id="reviewModalClose" aria-label="Fechar avaliação">×</button>
      <h3 id="reviewModalTitle">Como foi seu atendimento?</h3>
      <div class="review-average-card">
        <div class="review-average-title">CJOTA Estética Automotiva</div>
        <div class="review-average-score">
          <strong id="reviewAverageValue">4,5</strong>
          <span class="review-average-stars">★★★★☆</span>
          <small id="reviewAverageCount">(23)</small>
        </div>
      
      </div>
      <p class="modal-text">Sua opinião nos ajuda a melhorar. Avalie o serviço e deixe um comentário rápido.</p>

      <div class="rating-stars" id="reviewStars">
        <span class="star" data-value="1">★</span>
        <span class="star" data-value="2">★</span>
        <span class="star" data-value="3">★</span>
        <span class="star" data-value="4">★</span>
        <span class="star" data-value="5">★</span>
      </div>

      <textarea id="reviewComment" placeholder="Escreva sua avaliação (opcional)"></textarea>

      <div class="modal-actions">
        <button type="button" class="btn-secondary" id="reviewLater">Avaliar depois</button>
        <button type="button" class="btn-primary" id="submitReview">Enviar avaliação</button>
      </div>

      <div class="review-success hidden" id="reviewSuccess">Obrigado pela avaliação! 😊</div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  let ratingValue = 0;

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

  function openReviewModal() {
    const backdrop = document.getElementById('reviewModalBackdrop');
    if (!backdrop) return;
    backdrop.classList.remove('hidden');
    backdrop.style.display = 'flex';
    backdrop.setAttribute('aria-hidden', 'false');
  }

  function closeReviewModal() {
    const backdrop = document.getElementById('reviewModalBackdrop');
    if (!backdrop) return;
    backdrop.classList.add('hidden');
    backdrop.style.display = 'none';
    backdrop.setAttribute('aria-hidden', 'true');
  }

  function updateStars(value) {
    ratingValue = value;
    const stars = document.querySelectorAll('#reviewStars .star');
    stars.forEach((star) => {
      const starValue = Number(star.dataset.value);
      star.classList.toggle('selected', starValue <= value);
    });
    updateAverageSummary(value);
  }

  function updateAverageSummary(value){
    // Mantém valores fixos: média 4,5 e 23 avaliações.
    return;
  }

  document.addEventListener('DOMContentLoaded', function () {
    const cancelPolicyClose = document.getElementById('cancelPolicyClose');
    const cancelPolicyCancel = document.getElementById('cancelPolicyCancel');
    const cancelPolicyConfirm = document.getElementById('cancelPolicyConfirm');
    const cancelPolicyAgree = document.getElementById('cancelPolicyAgree');
    const form = document.getElementById('confirmScheduleForm');
    const reviewBackdrop = document.getElementById('reviewModalBackdrop');
    const reviewClose = document.getElementById('reviewModalClose');
    const reviewLater = document.getElementById('reviewLater');
    const submitReviewBtn = document.getElementById('submitReview');
    const reviewComment = document.getElementById('reviewComment');
    const successMessage = document.getElementById('reviewSuccess');
    const reviewStars = document.querySelectorAll('#reviewStars .star');

    if (cancelPolicyClose) {
      cancelPolicyClose.addEventListener('click', closeCancelPolicyModal);
    }

    if (cancelPolicyCancel) {
      cancelPolicyCancel.addEventListener('click', closeCancelPolicyModal);
    }

    if (cancelPolicyAgree) {
      cancelPolicyAgree.addEventListener('change', updateCancelPolicyConfirm);
    }

    if (cancelPolicyConfirm) {
      cancelPolicyConfirm.addEventListener('click', function () {
        if (cancelPolicyAgree && cancelPolicyAgree.checked) {
          closeCancelPolicyModal();
          openReviewModal();
        }
      });
    }

    if (reviewClose) {
      reviewClose.addEventListener('click', closeReviewModal);
    }

    if (reviewLater) {
      reviewLater.addEventListener('click', function () {
        if (form) form.submit();
      });
    }

    if (submitReviewBtn) {
      submitReviewBtn.addEventListener('click', function () {
        successMessage.classList.remove('hidden');
        successMessage.textContent = ratingValue > 0
          ? 'Obrigado pela avaliação! 😊'
          : 'Obrigado! Sua opinião será considerada.';
        reviewComment.value = '';
        setTimeout(() => {
          if (form) form.submit();
        }, 1800);
      });
    }

    reviewStars.forEach((star) => {
      star.addEventListener('mouseenter', () => {
        updateStars(Number(star.dataset.value));
      });

      star.addEventListener('click', () => {
        updateStars(Number(star.dataset.value));
      });
    });

    const backdrop = document.getElementById('cancelPolicyModal');
    if (backdrop) {
      backdrop.addEventListener('click', function (event) {
        if (event.target === backdrop) {
          closeCancelPolicyModal();
        }
      });
    }

    if (reviewBackdrop) {
      reviewBackdrop.addEventListener('click', function (event) {
        if (event.target === reviewBackdrop) {
          closeReviewModal();
        }
      });
    }
  });
</script>
@endpush
@endsection
