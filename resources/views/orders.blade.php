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
          <h4>Meus Pedidos</h4>
          <p>O historico de seus pedidos seá excluidos após 60 dias</p>
        </div>
      </div>
    </div>

    <div class="container">
      @if(session('status'))
        <div class="alert-box alert-success">{{ session('status') }}</div>
      @endif

      <div class="section-title">Pedidos</div>
      <p class="confirm-note">Acompanhe os agendamentos já confirmados, os cancelados e os próximos horários.</p>
      <br>

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
            @php
              // Obter todos os nomes dos serviços selecionados (service_ids cast é array)
              $serviceNames = [];
              if (!empty($appointment->service_ids) && is_array($appointment->service_ids)) {
                  $serviceNames = \App\Models\Service::whereIn('id', $appointment->service_ids)->pluck('name')->toArray();
              } elseif ($appointment->service) {
                  $serviceNames = [$appointment->service->name];
              }
            @endphp
            <div class="appointment-card" style="margin-bottom:1rem;">
              <div class="appointment-info">
                <div class="appointment-title">{{ implode(', ', $serviceNames) ?: 'Serviço indisponível' }}</div>
                <div class="appointment-meta">
                  Data: {{ \Illuminate\Support\Carbon::parse($appointment->date)->format('d/m/Y') }}<br>
                  Horário: {{ $appointment->time }}<br>
                  Status: <strong>{{ ucfirst($appointment->status) }}</strong>
                </div>
              </div>
              <div class="appointment-actions">
                @php
                  $apptDate = \Illuminate\Support\Carbon::parse($appointment->date);
                  $isFuture = $apptDate->isFuture();
                  $isPast = $apptDate->isPast();
                @endphp

                {{-- Cancelar: apenas agendamentos futuros e que não foram cancelados --}}
                @if($appointment->status !== 'cancelado' && $isFuture)
                  <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" style="display:inline-block; margin-right:0.5rem;">
                    @csrf
                    <button type="submit" class="btn-small btn-small-danger">Cancelar</button>
                  </form>
                @endif

                {{-- Excluir: permitir excluir agendamentos passados ou cancelados --}}
                @if($isPast || $appointment->status === 'cancelado')
                  <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display:inline-block; margin-right:0.5rem;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-small btn-small-danger">Excluir</button>
                  </form>
                @endif

                {{-- Editar: apenas agendamentos futuros que não estão cancelados --}}
                @if($isFuture && $appointment->status !== 'cancelado')
                  <button type="button" class="btn-small" onclick="window.location='{{ route('schedule.edit', $appointment) }}'">Editar</button>
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
