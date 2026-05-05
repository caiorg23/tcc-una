@extends('layouts.site')

@section('title', 'CJOTA Estética Automotiva')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content">
    <div class="home-hero">
      <div class="hero-top">
        <div>
          <div class="hero-label">Olá,</div>
          <div class="greeting-name">{{ Auth::check() ? Auth::user()->name : 'BEM-VINDO' }}</div>
        </div>
        <!--<div class="home-logo">
          <img src="{{ asset('images/cjota-logo.png') }}" alt="CJOTA Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'logo-fallback\'>CJOTA</span>'">
        </div>-->
      </div>

      @if(session('status'))
        <div class="alert-box" style="margin-bottom:18px;">
          {{ session('status') }}
        </div>
      @endif

      <div class="status-card">
        <div class="status-icon">🚗</div>
        <div>
          <div class="status-title">Meus agendamentos</div>
          <div class="status-text">Aqui estão os seus próximos e últimos serviços</div>
        </div>
      </div>
    </div>

    <div class="container">
      @if(! $nextAppointment)
        <div class="appointment-empty">Nenhum agendamento futuro encontrado. Agende agora mesmo.</div>
      @else
        <div class="appointment-list">
          <div class="appointment-card">
            <div class="appointment-info">
              <div class="appointment-title">{{ $nextAppointment->service->name }}</div>
              <div class="appointment-meta">{{ date('d/m/Y', strtotime($nextAppointment->date)) }} • {{ $nextAppointment->time }}</div>
              <div class="appointment-meta">{{ $nextAppointment->location }}</div>
            </div>
            <div class="appointment-actions">
              <span class="status-pill status-{{ $nextAppointment->status }}">{{ ucfirst($nextAppointment->status) }}</span>
              <a href="{{ route('schedule.edit', $nextAppointment) }}" class="btn-small">Editar</a>
              <form action="{{ route('appointments.cancel', $nextAppointment) }}" method="POST" class="inline-form">
                @csrf
                <button type="submit" class="btn-small btn-small-danger">Cancelar</button>
              </form>
            </div>
          </div>
        </div>
      @endif

      <div class="section-title">Acesso rápido</div>
      <div class="home-grid">
        <div class="home-card" onclick="window.location='{{ route('schedule') }}'">
          <div class="card-icon">📅</div>
          <div>
            <div class="card-title">Novo Agendamento</div>
            <div class="card-subtitle">Agende agora</div>
          </div>
        </div>
        <div class="home-card" onclick="window.location='{{ route('orders') }}'">
          <div class="card-icon">📋</div>
          <div>
            <div class="card-title">Meus Pedidos</div>
            <div class="card-subtitle">Histórico</div>
          </div>
        </div>
        <div class="home-card" onclick="window.location='{{ route('services') }}'">
          <div class="card-icon">🔧</div>
          <div>
            <div class="card-title">Serviços</div>
            <div class="card-subtitle">Ver tabela</div>
          </div>
        </div>
        <div class="home-card" onclick="window.location='{{ route('support') }}'">
          <div class="card-icon">🎧</div>
          <div>
            <div class="card-title">Suporte</div>
            <div class="card-subtitle">Perguntas frequentes</div>
          </div>
        </div>
        <div class="home-card location-card">
          <div class="location-header">
            <div class="card-icon">📍</div>
            <div>
              <div class="card-title">Nossa Localização</div>
              <div class="card-subtitle">Av. Cristiano Machado, 1395 — Silveira, BH</div>
            </div>
          </div>
          <div class="map-preview">
            <iframe
              src="https://maps.google.com/maps?q=Av.%20Cristiano%20Machado%2C%201395%20Silveira%20Belo%20Horizonte%20MG&z=15&output=embed"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              aria-label="Mapa da localização"
            ></iframe>
          </div>
          <a class="map-link" href="https://maps.google.com/?q=Av.+Cristiano+Machado,+1395+Silveira+BH" target="_blank" rel="noreferrer">
            Abrir no Google Maps
          </a>
        </div>
      </div>
    </div>
  </div>

  @include('partials.chat-widget')
</div>
@endsection
