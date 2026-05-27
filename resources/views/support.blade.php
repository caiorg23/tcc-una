@extends('layouts.site')

@section('title', 'Suporte - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content">
    <div class="header">
      <div class="header-top">
        <div class="back-btn" onclick="window.location='{{ route('home') }}'">‹</div>
        <div class="header-title-block">
          <h1>Suporte</h1>
          <p>Tire suas dúvidas</p>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="support-section">
        <h3>Perguntas Frequentes</h3>
        <div class="faq-list">
          @foreach($faqs as $faq)
            <div class="faq-item">
              <div class="faq-question">{{ $faq['question'] }}</div>
              <div class="faq-answer">{{ $faq['answer'] }}</div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="support-section">
        <h3>Precisa de ajuda?</h3>
        <p>Entre em contato conosco através do WhatsApp ou chat online.</p>
        <div class="support-actions">
          <a href="https://wa.me/5531999999999" class="btn-primary" target="_blank">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>
        </div>
      </div>
    </div>
  </div>

  @include('partials.chat-widget')
</div>
@endsection