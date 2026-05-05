@extends('layouts.site')

@section('title', 'Serviços - CJOTA')

@section('content')
<div class="page-wrapper">
  @include('partials.navbar')

  <div class="page-content">
    <div class="header">
      <div class="header-top">
        <div class="back-btn" onclick="window.location='{{ route('home') }}'">‹</div>
        <div class="header-title-block">
          <h2>Nossos Serviços</h2>
          <p>Qualidade e cuidado para seu veículo</p>
        </div>
      </div>
    </div>

    <div class="container" id="servicesListContainer"></div>
  </div>

  <script>
    window.appServices = @json($services);
  </script>

  @include('partials.chat-widget')
</div>
@endsection
