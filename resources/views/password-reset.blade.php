@extends('layouts.site')

@section('title', 'Recuperar senha - CJOTA')

@section('content')
<div class="page-wrapper page-no-navbar">
  <div class="login-page">
    <div class="login-container">
      <div class="login-left">
        <div class="login-logo">
          <img src="{{ asset('images/cjota-logo.png') }}" alt="CJOTA Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'logo-fallback-xl\'>CJOTA</span>'">
        </div>
      </div>
      <div class="login-right">
        <div class="auth-card">
          <div class="auth-header">
            <h1>Recuperar senha</h1>
            <p>Informe seu e-mail para receber o link de redefinição.</p>
          </div>
          <div class="auth-body">
            @if(session('status'))
              <div class="alert-box alert-success">
                {{ session('status') }}
              </div>
            @endif
            @if($errors->any())
              <div class="alert-box">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <form action="{{ route('password.email') }}" method="POST">
              @csrf
              <div class="input-group">
                <div class="input-label">E-mail cadastrado</div>
                <input type="email" name="email" placeholder="seu@email.com" value="{{ old('email') }}">
              </div>
              <button type="submit" class="btn-primary">Enviar link de recuperação</button>
            </form>
            <div class="auth-link" style="margin-top: 1rem;">
              <a href="{{ route('login') }}">Voltar para o login</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
