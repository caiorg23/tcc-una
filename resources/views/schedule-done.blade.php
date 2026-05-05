@extends('layouts.site')

@section('title', 'Agendamento Concluído - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content">
    <div class="header">
      <div class="header-top">
        <div class="back-btn" onclick="window.location='{{ route('home') }}'">‹</div>
        <div class="header-title-block">
          <h2>Agendamento Concluído</h2>
          <p>Seu horário foi reservado com sucesso</p>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="confirm-success-card">
        <div class="success-icon">✓</div>
        <h3>Agendamento confirmado!</h3>
        <p>Recebemos a solicitação e em breve você receberá a confirmação via WhatsApp.</p>
      </div>

      <div class="confirm-card">
        <div class="confirm-line">
          <span>Serviço</span>
          <strong>{{ $appointment->service->name }}</strong>
        </div>
        <div class="confirm-line">
          <span>Data</span>
          <strong>{{ date('d/m/Y', strtotime($appointment->date)) }}</strong>
        </div>
        <div class="confirm-line">
          <span>Horário</span>
          <strong>{{ $appointment->time }}</strong>
        </div>
      </div>

      <div class="section-title">O que acontece agora</div>
      <p class="confirm-note">Você pode voltar à home e acompanhar seus próximos agendamentos. Caso queira alterar, entre em contato pelo WhatsApp.</p>

      <button class="btn-primary" onclick="window.location='{{ route('home') }}'">Ir para a Home</button>
      <button class="btn-secondary" type="button" id="openReviewModal">Avaliar Serviço</button>
    </div>
  </div>

  <div id="reviewModalBackdrop" class="modal-backdrop hidden" aria-hidden="true" style="display:none;">
    <div class="review-modal" role="dialog" aria-modal="true" aria-labelledby="reviewModalTitle">
      <button type="button" class="modal-close" id="reviewModalClose" aria-label="Fechar avaliação">×</button>
      <h3 id="reviewModalTitle">Como foi seu atendimento?</h3>
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

  @include('partials.chat-widget')
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const backdrop = document.getElementById('reviewModalBackdrop');
    const openBtn = document.getElementById('openReviewModal');
    const closeBtn = document.getElementById('reviewModalClose');
    const laterBtn = document.getElementById('reviewLater');
    const submitBtn = document.getElementById('submitReview');
    const stars = document.querySelectorAll('#reviewStars .star');
    const successMessage = document.getElementById('reviewSuccess');
    const reviewComment = document.getElementById('reviewComment');
    let ratingValue = 0;

    function openModal() {
      backdrop.classList.remove('hidden');
      backdrop.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
      backdrop.classList.add('hidden');
      backdrop.setAttribute('aria-hidden', 'true');
    }

    function updateStars(value) {
      ratingValue = value;
      stars.forEach((star) => {
        const starValue = Number(star.dataset.value);
        star.classList.toggle('selected', starValue <= value);
      });
    }

    if (openBtn) {
      openBtn.addEventListener('click', openModal);
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', closeModal);
    }

    if (laterBtn) {
      laterBtn.addEventListener('click', closeModal);
    }

    stars.forEach((star) => {
      star.addEventListener('mouseenter', () => {
        updateStars(Number(star.dataset.value));
      });

      star.addEventListener('click', () => {
        updateStars(Number(star.dataset.value));
      });
    });

    submitBtn.addEventListener('click', () => {
      successMessage.classList.remove('hidden');
      successMessage.textContent = ratingValue > 0
        ? 'Obrigado pela avaliação! 😊'
        : 'Obrigado! Sua opinião será considerada.';
      reviewComment.value = '';
      setTimeout(closeModal, 1800);
    });

    backdrop.addEventListener('click', (event) => {
      if (event.target === backdrop) {
        closeModal();
      }
    });

    // Abre o modal automaticamente quando a página de confirmação for exibida
    openModal();
  });
</script>
@endpush
@endsection
