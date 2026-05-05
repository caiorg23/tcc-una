@extends('layouts.site')

@section('title', 'Cadastrar - CJOTA')

@section('content')
<div class="page-wrapper page-no-navbar">
  <div class="login-page">
    <div class="auth-card">
      <div class="auth-header">
        <h1>Criar Conta</h1>
        <p>Rápido e fácil</p>
      </div>
      <div class="auth-body">
        <form action="{{ route('register.user') }}" method="POST">
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
            <div class="input-label">Nome completo</div>
            <input type="text" name="name" placeholder="Seu nome" value="{{ old('name') }}">
          </div>
          <div class="input-group">
            <div class="input-label">E-mail</div>
            <input type="email" name="email" placeholder="seu@email.com" value="{{ old('email') }}">
          </div>
          <div class="input-group">
            <div class="input-label">Senha</div>
            <input type="password" name="password" placeholder="Mínimo 6 caracteres">
          </div>
          <div class="input-group">
            <div class="input-label">Confirmar senha</div>
            <input type="password" name="password_confirmation" placeholder="Repita a senha">
          </div>
          <button type="submit" class="btn-primary">Criar conta</button>
        </form>
      </div>
      <div class="auth-footer">
        Já tem conta? <a href="{{ route('login') }}">Entrar</a>
      </div>
    </div>
  </div>
</div>
@endsection
