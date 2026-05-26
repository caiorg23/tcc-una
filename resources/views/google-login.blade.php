@extends('layouts.site')

@section('title', 'Login com Google - CJOTA')

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
            <h1>Login com Google</h1>
            <p>Esta funcionalidade ainda não está configurada.</p>
          </div>
          <div class="auth-body">
            <div class="alert-box alert-success">
              O botão "Continuar com Google" agora está ativo e direciona para esta página.
            </div>
            <p style="color:#d7ecff; line-height:1.6; margin-bottom:1rem;">A integração com a conta Google ainda será implementada. Por enquanto, use o login com e-mail e senha abaixo ou registre uma nova conta.</p>
            <a href="{{ route('login') }}" class="btn-primary">Voltar para o login</a>
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
