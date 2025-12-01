<!-- Sidebar Layout -->
<div class="d-flex">

    <!-- SIDEBAR -->
    <nav id="sidebarMenu" class="d-lg-block bg-white shadow-sm sidebar collapse">
        <div class="position-sticky">

            <div class="text-center py-4 border-bottom">
                <a class="navbar-brand fw-bold d-flex justify-content-center align-items-center" href="/">
                    <i class="bi bi-egg-fried me-2" style="color: var(--primary);"></i>
                    <span>MBGood</span>
                </a>
            </div>

            <ul class="nav flex-column px-3 py-3">

                <li class="nav-item mb-2">
                    <a href="/" class="nav-link d-flex align-items-center {{ request()->is('/') ? 'active text-primary fw-semibold' : 'text-dark' }}">
                        <i class="bi bi-house-door me-2"></i>  
                        Beranda
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('about') }}"
                       class="nav-link d-flex align-items-center {{ request()->is('tentang*') ? 'active text-primary fw-semibold' : 'text-dark' }}">
                        <i class="bi bi-info-circle me-2"></i>
                        Tentang MBG
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('cari') }}"
                       class="nav-link d-flex align-items-center {{ request()->is('cari*') ? 'active text-primary fw-semibold' : 'text-dark' }}">
                        <i class="bi bi-search me-2"></i>
                        Cari Sekolah
                    </a>
                </li>

                @auth
                    <hr>

                    <li class="nav-item mb-2">
                        @php
                            $dashboardRoute = auth()->user()->role === 'vendor'
                                ? 'sppg.dashboard'
                                : 'dashboard';
                        @endphp

                        <a href="{{ route($dashboardRoute) }}" 
                           class="nav-link d-flex align-items-center {{ request()->is('*/dashboard') ? 'active text-primary fw-semibold' : 'text-dark' }}">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>

        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow-1 p-4" style="margin-left: 250px;">
        @yield('content')
    </main>

</div>
