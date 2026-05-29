@extends('layouts.site')

@section('title', 'CJOTA Estética Automotiva')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content home-page">
    
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
        <div class="alert-box alert-success" style="margin-bottom:18px;">
          {{ session('status') }}
        </div>
      @endif

   
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
          <div class="card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16">
  <path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7"/>
  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
</svg></div>
          <div>
            <div class="card-title">Novo Agendamento</div>
            <div class="card-subtitle">Agende agora</div>
          </div>
        </div>
        <div class="home-card" onclick="window.location='{{ route('orders') }}'">
          <div class="card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
  <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
</svg></div>
          <div>
            <div class="card-title">Meus Pedidos</div>
            <div class="card-subtitle">Histórico</div>
          </div>
        </div>
      </div>

      <div class="location-footer">
        <div class="map-preview footer-map">
          <iframe
            src="https://maps.google.com/maps?q=Av.%20Cristiano%20Machado%2C%201395%20Silveira%20Belo%20Horizonte%20MG&z=15&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            aria-label="Mapa da localização"
          ></iframe>
        </div>
      </div>
  </div>

  @include('partials.chat-widget')
</div>
@endsection
