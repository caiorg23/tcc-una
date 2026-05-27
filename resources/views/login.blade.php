@extends('layouts.site')

@section('title', 'Entrar - CJOTA')

@section('content')
<div class="page-wrapper page-no-navbar">
  <div class="login-page">
    <div class="login-container">
      <!-- LADO ESQUERDO: LOGO -->
      <div class="login-left">
        <div class="login-logo">
          <img src="{{ asset('images/cjota-logo.png') }}" alt="CJOTA Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'logo-fallback-xl\'>CJOTA</span>'">
        </div>
       
      </div>

      <!-- LADO DIREITO: FORMULÁRIO -->
      <div class="login-right">
        <div class="auth-card">
          <div class="auth-header">
            <h1>Bem-vindo de volta</h1>
            <p>Acesse sua conta</p>
          </div>
          <div class="auth-body">
            <a href="{{ route('google.login') }}" class="btn-google">
              <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Continuar com Google
<script>
  function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
  }
</script>
            </a>

            <div class="divider">
              <span>ou</span>
            </div>

            <form action="{{ route('authenticate') }}" method="POST">
              @csrf
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
              <div class="input-group">
                <div class="input-label">E-mail</div>
                <input type="email" name="email" placeholder="seu@email.com" value="{{ old('email') }}">
              </div>
              <div class="input-group">
                <div class="input-label">Senha</div>
                <div class="password-wrapper">
                  <input type="password" name="password" id="loginPassword" placeholder="••••••••">
                  <button type="button" class="password-toggle" onclick="togglePassword('loginPassword')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
  <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
  <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
  <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
</svg></button>
                </div>
              </div>
              
              <button type="submit" class="btn-primary-login">Entrar</button>
            </form>

            <div class="auth-link">
              <a href="{{ route('password.request') }}">Esqueci minha senha</a>
            </div>
          </div>
          <div class="auth-footer">
            Não tem conta? <a href="{{ route('register') }}">Criar uma conta</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        <script>
          function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
          }
        </script>
        @endsection
