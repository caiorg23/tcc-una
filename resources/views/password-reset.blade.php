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
            <p>Informe seu e-mail para redefinir a senha (se estiver cadastrado).</p>
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
              @if(!empty($show_reset) && !empty($email))
                <div class="input-group">
                  <div class="input-label">E-mail</div>
                  <input type="email" name="email" value="{{ $email }}" readonly>
                </div>
                <div class="input-group">
                  <div class="input-label">Redefinir senha</div>
                  <div class="password-wrapper">
                    <input type="password" name="password" id="resetPassword" placeholder="Nova senha">
                    <button type="button" class="password-toggle" onclick="togglePassword('resetPassword')">Ver</button>
                  </div>
                </div>
                <div class="input-group">
                  <div class="input-label">Confirmar senha</div>
                  <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="resetPasswordConfirm" placeholder="Confirme a nova senha">
                    <button type="button" class="password-toggle" onclick="togglePassword('resetPasswordConfirm')">Ver</button>
                  </div>
                </div>
                <div style="display:flex; gap:0.75rem; margin-top:0.5rem;">
                  <button type="submit" class="btn-primary">Redefinir senha</button>
                  <a href="{{ route('login') }}" class="btn-secondary" style="display:inline-flex; align-items:center; justify-content:center;">Cancelar</a>
                </div>
              @else
                <div class="input-group">
                  <div class="input-label">E-mail cadastrado</div>
                  <input type="email" name="email" placeholder="seu@email.com" value="{{ old('email') }}">
                </div>
                <button type="submit" class="btn-primary">Verificar e redefinir</button>
              @endif
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
          <script>
            function togglePassword(fieldId) {
              const input = document.getElementById(fieldId);
              if (!input) return;
              input.type = input.type === 'password' ? 'text' : 'password';
            }
          </script>
          @endsection
