@extends('layouts.site')

@section('title', 'Meus Pedidos - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content">
    <div class="header">
      <div class="header-top">
        <div class="back-btn" onclick="window.location='{{ route('home') }}'">‹</div>
        <div class="header-title-block">
          <h2>Meus Pedidos</h2>
          <p>Veja aqui todos os agendamentos confirmados e cancelados.</p>
        </div>
      </div>
    </div>

    <div class="container">
      @if($appointments->isEmpty())
        <div class="appointment-empty">Nenhum pedido encontrado. Faça um novo agendamento.</div>
      @else
        <div class="appointment-list">
          @foreach($appointments as $appointment)
            <div class="appointment-card {{ $appointment->status === 'cancelado' ? 'appointment-canceled' : '' }}">
              <div class="appointment-info">
                <div class="appointment-title">{{ $appointment->service->name }}</div>
                <div class="appointment-meta">{{ date('d/m/Y', strtotime($appointment->date)) }} • {{ $appointment->time }}</div>
                <div class="appointment-meta">{{ $appointment->location }}</div>
              </div>
              <div class="appointment-actions">
                <span class="status-pill status-{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>

                @php
                  $isFuture = strtotime($appointment->date) >= strtotime(now()->toDateString());
                @endphp

                @if($appointment->status !== 'cancelado' && $isFuture)
                  <a href="{{ route('schedule.edit', $appointment) }}" class="btn-small">Editar</a>
                  <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline-form">
                    @csrf
                    <button type="submit" class="btn-small btn-small-danger">Cancelar</button>
                  </form>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  @include('partials.chat-widget')
</div>
@endsection
