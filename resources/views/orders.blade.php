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
          <p>Veja todos os seus agendamentos e cancele ou edite quando precisar.</p>
        </div>
      </div>
    </div>

    <div class="container">
      @if(session('status'))
        <div class="alert-box">{{ session('status') }}</div>
      @endif

      <div class="section-title">Pedidos</div>
      <p class="confirm-note">Acompanhe os agendamentos já confirmados, os cancelados e os próximos horários.</p>

      @if($appointments->isEmpty())
        <div class="appointment-card">
          <div class="appointment-info">
            <div class="appointment-title">Nenhum pedido encontrado</div>
            <div class="appointment-meta">Você ainda não tem agendamentos registrados. Agende agora e volte aqui para acompanhar.</div>
          </div>
        </div>
      @else
        <div class="appointment-list">
          @foreach($appointments as $appointment)
            <div class="appointment-card">
              <div class="appointment-info">
                <div class="appointment-title">{{ optional($appointment->service)->name ?? 'Serviço indisponível' }}</div>
                <div class="appointment-meta">
                  Data: {{ \Illuminate\Support\Carbon::parse($appointment->date)->format('d/m/Y') }}<br>
                  Horário: {{ $appointment->time }}<br>
                  Status: <strong>{{ ucfirst($appointment->status) }}</strong>
                </div>
              </div>
              <div class="appointment-actions">
                @if($appointment->status !== 'cancelado' && \Illuminate\Support\Carbon::parse($appointment->date)->isFuture())
                  <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-small btn-small-danger">Cancelar</button>
                  </form>
                @endif
                <button type="button" class="btn-small" onclick="window.location='{{ route('schedule.edit', $appointment) }}'">Editar</button>
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
