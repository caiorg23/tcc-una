<nav class="navbar" id="navbar">
  <div class="navbar-container">
    <div class="navbar-logo">
      <img src="{{ asset('images/cjota-logo.png') }}" alt="CJOTA" class="navbar-logo-img" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'logo-fallback-small\'>CJOTA</span>'">
    </div>

    <div class="navbar-menu" id="navbarMenu">
      <a href="{{ route('home') }}">Início</a>
      <a href="{{ route('services') }}">Serviços</a>
      <a href="{{ route('schedule') }}">Agendar</a>
    </div>

    @auth
    <div class="navbar-profile">
      <button id="profileToggle" class="btn-profile" type="button" aria-label="Abrir perfil">
        <span class="profile-icon">👤</span>
      </button>
      <div class="profile-dropdown" id="profileDropdown">
        <div class="profile-dropdown-header">Meu perfil</div>
        <div class="profile-dropdown-item">
          <div class="profile-dropdown-label">Nome</div>
          <div class="profile-dropdown-value">{{ Auth::user()->name }}</div>
        </div>
        <div class="profile-dropdown-item">
          <div class="profile-dropdown-label">E-mail</div>
          <div class="profile-dropdown-value">{{ Auth::user()->email }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="profile-dropdown-form">
          @csrf
          <button type="submit" class="profile-dropdown-logout">Sair da conta</button>
        </form>
      </div>
    </div>
    @endauth

    <div class="navbar-actions desktop-only">
      @guest
        <a href="{{ route('login') }}" class="btn-nav-login">Entrar</a>
        <a href="{{ route('register') }}" class="btn-nav-register">Cadastro</a>
      @endguest
    </div>

    <button class="navbar-burger" id="navbarToggle" type="button" aria-label="Abrir menu">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
</nav>


