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
            <button type="button" class="btn-google">
              <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Continuar com Google
            </button>

            <div class="divider">
              <span>ou</span>
            </div>

            <form action="{{ route('authenticate') }}" method="POST">
              @csrf
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
                <input type="password" name="password" placeholder="••••••••">
              </div>
              <div class="input-group">
                <label><input type="checkbox" name="remember"> Manter conectado</label>
              </div>
              <button type="submit" class="btn-primary">Entrar</button>
            </form>

            <div class="auth-link">
              <a href="#">Esqueci minha senha</a>
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
@endsection
