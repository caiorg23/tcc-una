@extends('layouts.site')

@section('title', 'Agendar - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content">
    <div class="header">
      <div class="header-top">
        <div class="back-btn" onclick="window.location='{{ route('home') }}'">‹</div>
        <div class="header-title-block">
          <h2>{{ isset($appointment) ? 'Editar Agendamento' : 'Agendamento' }}</h2>
          <p>{{ isset($appointment) ? 'Atualize data, hora ou serviço' : 'Escolha data, hora e serviços' }}</p>
        </div>
      </div>
      @if(isset($appointment))
        <div class="edit-note">Você está editando o agendamento de {{ date('d/m/Y', strtotime($appointment->date)) }} às {{ $appointment->time }}.</div>
      @endif
    </div>

    <div class="container">
      @if ($errors->any())
        <div class="alert-box">
          <strong>Por favor corrija os campos abaixo:</strong>
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @php
        $appointment = $appointment ?? null;
        $selectedServiceIds = old('service_ids', ($appointment && $appointment->service_ids) ? $appointment->service_ids : (($appointment && $appointment->service_id) ? [$appointment->service_id] : [$services->first()->id ?? '']));
        if (is_string($selectedServiceIds)) {
            $selectedServiceIds = array_filter(explode(',', $selectedServiceIds));
        }
      @endphp
      <form action="{{ route('schedule.confirm') }}" method="POST" id="scheduleForm">
        @csrf
        <input type="hidden" name="appointment_id" id="appointmentId" value="{{ $appointment?->id ?? '' }}">
        <input type="hidden" name="service_id" id="selectedServiceId" value="{{ $selectedServiceIds[0] ?? '' }}">
        <div id="serviceIdsContainer">
          @foreach($selectedServiceIds as $id)
            <input type="hidden" name="service_ids[]" value="{{ $id }}">
          @endforeach
        </div>
        <input type="hidden" id="selectedServiceIds" value="{{ implode(',', $selectedServiceIds) }}">
        <input type="hidden" name="date" id="selectedDate" value="{{ old('date', $appointment?->date ?? '') }}">
        <input type="hidden" name="time" id="selectedTime" value="{{ old('time', $appointment?->time ?? '') }}">
        <input type="hidden" name="reminder" value="whatsapp">

        <div class="section-title">Escolha a data</div>
        <div class="date-grid" id="dateGrid"></div>

        <div class="section-title">Horários disponíveis</div>
        <div class="time-grid">
          <div class="time-item busy">08:00</div>
          <div class="time-item" onclick="selectTime(this)">09:00</div>
          <div class="time-item" onclick="selectTime(this)">10:00</div>
          <div class="time-item busy">11:00</div>
          <div class="time-item" onclick="selectTime(this)">13:00</div>
          <div class="time-item" onclick="selectTime(this)">14:00</div>
          <div class="time-item" onclick="selectTime(this)">15:00</div>
          <div class="time-item busy">16:00</div>
          <div class="time-item" onclick="selectTime(this)">17:00</div>
        </div>

        <div class="section-title">Serviços</div>
        <div id="scheduleServiceList">
          @foreach($services as $service)
            @php
              $serviceIcon = $service->icon ?? '🔧';
              $serviceBg = $service->bg ?? '#d1fae5';
              $serviceBgClass = str_starts_with($serviceBg, '#') ? '' : $serviceBg;
              $serviceBgStyle = str_starts_with($serviceBg, '#') ? "background: {$serviceBg};" : '';
              $isSelected = in_array($service->id, $selectedServiceIds);
            @endphp
            <div class="service-card selectable{{ $isSelected ? ' selected' : '' }}" data-service-id="{{ $service->id }}" onclick="selectService(this)">
              <div class="service-icon-box {{ $serviceBgClass }}" style="{{ $serviceBgStyle }}">
                @if(is_string($serviceIcon) && str_starts_with($serviceIcon, 'fa'))
                  <i class="{{ $serviceIcon }}"></i>
                @else
                  {{ $serviceIcon }}
                @endif
              </div>
              <div class="service-info">
                <div class="service-name">{{ $service->name }}</div>
                <div class="service-desc">{{ $service->description }}</div>
              </div>
              <div class="radio-circle"><div class="radio-dot"></div></div>
              <div class="service-price">{{ $service->price }}</div>
            </div>
          @endforeach
        </div>
      </form>
    </div>

    <div class="proceed-bar">
      <div class="selected-count">
        <span id="svcCountLabel">1</span> serviço(s) selecionado(s)
      </div>
      <button type="submit" form="scheduleForm" class="btn-proceed">Confirmar</button>
    </div>
  </div>

  @include('partials.chat-widget')
</div>
@endsection
