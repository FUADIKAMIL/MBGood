<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <!-- Brand/Logo -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
            <i class="bi bi-egg-fried me-2" style="color: var(--primary);"></i>
            <span>MBGood</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Side Nav Items -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                        <i class="bi bi-house-door d-lg-none me-2"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('tentang*') ? 'active' : '' }}" href="{{ route('about') }}">
                        <i class="bi bi-info-circle d-lg-none me-2"></i>Tentang MBG
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('cari*') ? 'active' : '' }}" href="{{ route('cari') }}">
                        <i class="bi bi-search d-lg-none me-2"></i>Cari Sekolah
                    </a>
                </li>
            </ul>

            <!-- Right Side Nav Items -->
            <div class="d-flex align-items-center">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                @else
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" 
                           id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="me-2 d-none d-lg-flex flex-column text-end">
                                <span class="small fw-bold text-dark">{{ auth()->user()->name }}</span>
                                <small class="text-muted">{{ auth()->user()->role }}</small>
                            </div>
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                 style="width: 36px; height: 36px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </a>
                        @php
                            $dashboardRoute = auth()->user()->role === 'vendor' ? 'sppg.dashboard' : 'dashboard';
                        @endphp
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 mt-2" 
                            aria-labelledby="userDropdown" style="min-width: 200px;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route($dashboardRoute) }}">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
