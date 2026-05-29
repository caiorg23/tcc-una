<nav class="navbar" id="navbar">
  <div class="navbar-container">
    <div class="navbar-logo">
      <a href="{{ route('home') }}">
        <img src="{{ asset('images/cj-logo-nav.PNG') }}" alt="CJOTA" class="navbar-logo-img" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'logo-fallback-small\'>CJOTA</span>'">
      </a>
    </div>

    <div class="navbar-menu" id="navbarMenu">
      <a href="{{ route('home') }}">Início</a>
      <a href="{{ route('services') }}">Serviços</a>
      <a href="{{ route('schedule') }}">Agendar</a>
      <a href="{{ route('support') }}">Suporte</a>
    </div>

    <div class="navbar-actions desktop-only">
     
      <a href="https://wa.me/5531991256120" class="social-link" title="WhatsApp" target="_blank">
        <i class="fab fa-whatsapp"></i>
      </a>
      <a href="https://www.instagram.com/cjotabh?igsh=MTM0d3M5MzRkY3hrbw==" class="social-link" title="Instagram" target="_blank">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="mailto:bostinhacocozinho122@gmail.com" class="social-link" title="Email">
        <i class="fas fa-envelope"></i>
      </a>
    </div>

    @auth
    <div class="navbar-profile">
      <button id="profileToggle" class="btn-profile" type="button" aria-label="Abrir perfil">
        <span class="profile-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
</svg></span>
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

    @guest
    <div class="navbar-actions-guest desktop-only">
      <a href="{{ route('login') }}" class="btn-nav-login">Entrar</a>
      <a href="{{ route('register') }}" class="btn-nav-register">Cadastro</a>
    </div>
    @endguest

    <button class="navbar-burger" id="navbarToggle" type="button" aria-label="Abrir menu">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
</nav>

<div class="navbar-overlay" id="navbarOverlay"></div>

