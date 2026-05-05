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
          <h2>Suporte</h2>
          <p>Encontre respostas rápidas para suas dúvidas.</p>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="faq-list">
        @foreach($faqs as $faq)
          <div class="faq-item">
            <div class="faq-question">{{ $faq['question'] }}</div>
            <div class="faq-answer">{{ $faq['answer'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  @include('partials.chat-widget')
</div>
@endsection
