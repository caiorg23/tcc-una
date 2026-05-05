@extends('layouts.site')

@section('title', 'Confirmar Agendamento - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content">
    <div class="header">
      <div class="header-top">
        <div class="back-btn" onclick="window.location='{{ route('schedule') }}'">‹</div>
        <div class="header-title-block">
          <h2>Confirmar Agendamento</h2>
          <p>Revise os detalhes antes de finalizar</p>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="section-title">Resumo do agendamento</div>

      @foreach($schedule['services'] as $service)
        <div class="service-card selected" style="margin-bottom: 16px;">
          <div class="service-icon-box si-teal">🔧</div>
          <div class="service-info">
            <div class="service-name">{{ $service['name'] }}</div>
            <div class="service-desc">{{ $service['description'] }}</div>
          </div>
          <div class="service-price">{{ $service['price'] }}</div>
        </div>
      @endforeach

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
          <span>Forma de aviso</span>
          <strong>{{ $schedule['reminder'] }}</strong>
        </div>
      </div>

      <div class="section-title">Política de cancelamento</div>
      <p class="confirm-note">Caso precise cancelar, avise com pelo menos 3 horas de antecedência para não gerar cobrança adicional.</p>
    </div>

    <div class="proceed-bar">
      <form action="{{ route('schedule.done') }}" method="POST">
        @csrf
        <button type="submit" class="btn-proceed">{{ $schedule['appointment_id'] ? 'Atualizar Agendamento' : 'Confirmar Agendamento' }}</button>
      </form>
    </div>
  </div>

  @include('partials.chat-widget')
</div>
@endsection
