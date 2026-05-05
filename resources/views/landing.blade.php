@extends('layouts.site')

@section('title', 'CJOTA Estética Automotiva')

@section('content')
<div class="page-wrapper page-no-navbar">
  <div id="landing" class="screen active">
    <div class="landing-hero">
      <div class="landing-content">
        <div class="hero-logo-area">
          <div class="hero-logo-circle">
            <img src="{{ asset('images/cjota-logo.png') }}" alt="CJOTA Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'logo-fallback-lg\'>CJOTA</span>'">
          </div>
        </div>
        <h1>Padrão CJOTA</h1>
        <p>Estética Automotiva de Qualidade</p>
        <div class="landing-buttons">
          <a href="{{ route('login') }}" class="btn-primary">Entrar</a>
          <a href="{{ route('register') }}" class="btn-secondary">Criar Conta</a>
        </div>
        <div class="landing-info">
          <div class="info-item">
            <span class="info-icon">📍</span>
            <span>Av. Cristiano Machado, 1395 — Silveira, BH</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
