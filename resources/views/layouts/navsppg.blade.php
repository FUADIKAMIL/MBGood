<!-- Sidebar Layout -->
<div class="d-flex">

    <!-- SIDEBAR -->
    <nav id="sidebarMenu" class="d-lg-block bg-white shadow-sm sidebar collapse" style="width: 300px;">
        <div class="position-sticky">

            <div class="text-center py-4 border-bottom">
                <a class="navbar-brand fw-bold d-flex justify-content-center align-items-center" href="/">
                    <i class="bi bi-egg-fried me-2" style="color: var(--primary);"></i>
                    <span>MBGood</span>
                </a>
            </div>

            <ul class="nav flex-column px-3 py-3">

                <!-- Ajukan Menu -->
                <li class="nav-item mb-2">
                    <a href="{{ route('ajukan.view') }}"
                       class="nav-link d-flex align-items-center {{ request()->is('sppg/ajukan') ? 'active text-primary fw-semibold' : 'text-dark' }}">
                        <i class="bi bi-send-plus me-2"></i>
                        Ajukan Menu
                    </a>
                </li>

                <!-- Input Menu Harian -->
                <li class="nav-item mb-2">
                    <a href="{{ route('sppg.input.menu.view') }}"
                       class="nav-link d-flex align-items-center {{ request()->is('sppg/menu') ? 'active text-primary fw-semibold' : 'text-dark' }}">
                        <i class="bi bi-calendar2-check me-2"></i>
                        Input Menu
                    </a>
                </li>

                @auth
                    <hr>

                    <!-- Riwayat Menu -->
                    <li class="nav-item mb-2">
                        @php
                            // Vendor: sppg.riwayat
                            // Admin/user lain: dashboard (default)
                            $riwayatRoute = auth()->user()->role === 'vendor'
                                ? 'sppg.riwayat'
                                : 'dashboard';
                        @endphp

                        <a href="{{ route($riwayatRoute) }}"
                           class="nav-link d-flex align-items-center {{ request()->is('sppg/riwayat') ? 'active text-primary fw-semibold' : 'text-dark' }}">
                            <i class="bi bi-clock-history me-2"></i>
                            Riwayat Menu
                        </a>
                    </li>

                    <!-- Logout -->
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
    <main class="flex-grow-1 p-4">
        @yield('content')
    </main>

</div>
