<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">

    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="navbar-brand" href="/">MBGood</a>
        </li>
    </ul>
        @guest
        <li class="nav-item">
            <a class="btn" href="/login" aria-label="Login">
              <i class="bi bi-box-arrow-in-right"></i>
            </a>
        </li>
        @endguest

        <li class="nav-item">
            <a class="btn" href="/dashboard" aria-label="Login">
              dashboard
            </a>
        </li>

        @auth
        <li class="nav-item me-2 d-flex align-items-center">
            <span class="text-secondary">Halo, {{ auth()->user()->name }}</span>
        </li>

        <li class="nav-item">
            <form method="POST" action="/logout">
                @csrf
                <button class="btn btn-danger">Logout</button>
            </form>
        </li>
        @endauth
    </div>
  </div>
</nav>
