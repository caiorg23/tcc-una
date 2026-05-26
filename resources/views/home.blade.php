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
        <div class="alert-box" style="margin-bottom:18px;">
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
