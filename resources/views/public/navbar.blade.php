<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #0066cc, #004c99);">
    <div class="container">

        <a class="navbar-brand fw-bold text-white" href="/" style="letter-spacing: 1px;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9e/Lambang_Kementerian_Kesehatan_Republik_Indonesia.png" 
                 alt="Logo" width="32" height="32" class="me-2">
            MBG Monitoring
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">Beranda</a>
                </li>
            </ul>

            <ul class="navbar-nav">

                @guest
                <li class="nav-item">
                    <a class="btn btn-warning fw-bold px-3" href="/login">Login</a>
                </li>
                @endguest

                @auth
                <li class="nav-item d-flex align-items-center text-white me-3">
                    Halo, <strong class="ms-1">{{ auth()->user()->name }}</strong>
                </li>

                <li class="nav-item">
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="btn btn-danger">Logout</button>
                    </form>
                </li>
                @endauth

            </ul>

        </div>
    </div>
</nav>
