<!-- Sidebar Layout -->
<div class="d-flex flex-column flex-lg-row position-relative">

    <!-- Mobile Toggle -->
    <div class="d-lg-none d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-white position-sticky top-0 z-3">
        <button class="btn btn-outline-primary btn-sm" id="sidebarToggleMobile">
            <i class="bi bi-list"></i>
            Menu
        </button>
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
            <i class="bi bi-egg-fried me-2" style="color: var(--primary);"></i>
            <span>MBGood</span>
        </a>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>
    <nav id="sidebarMenu" class="bg-white shadow-sm sidebar collapse show">
        <div class="position-sticky d-flex flex-column h-100">

            <div class="text-center py-4 border-bottom">
                <a class="navbar-brand fw-bold d-flex justify-content-center align-items-center" href="/">
                    <i class="bi bi-egg-fried me-2" style="color: var(--primary);"></i>
                    <span>MBGood</span>
                </a>

                @auth
                    <div class="mt-3">
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                @endauth
            </div>

            <style>
                #sidebarMenu {
                    width: 280px;
                    min-height: 100vh;
                    transition: transform .3s ease;
                }

                @media (max-width: 991.98px) {
                    #sidebarMenu {
                        position: fixed;
                        top: 0;
                        left: 0;
                        height: 100vh;
                        transform: translateX(-100%);
                        z-index: 1050;
                    }

                    #sidebarMenu.open {
                        transform: translateX(0);
                    }

                    .sidebar-backdrop {
                        position: fixed;
                        inset: 0;
                        background: rgba(0, 0, 0, 0.45);
                        opacity: 0;
                        visibility: hidden;
                        transition: opacity .3s ease;
                        z-index: 1040;
                    }

                    .sidebar-backdrop.show {
                        opacity: 1;
                        visibility: visible;
                    }
                }

                #sidebarMenu .nav-link {
                    border-radius: .5rem;
                    padding: .55rem .75rem;
                    font-size: .95rem;
                    color: #424750;
                    transition: all .2s ease;
                }

                #sidebarMenu .nav-link:hover {
                    background-color: rgba(13, 110, 253, .08);
                    color: var(--primary);
                }

                #sidebarMenu .nav-link.active-link {
                    background-color: rgba(13, 110, 253, .12);
                    color: var(--primary);
                    font-weight: 600;
                }
            </style>

            <ul class="nav flex-column px-3 py-3 flex-grow-1">

                <!-- Ajukan Menu -->
                <li class="nav-item mb-2">
                    <a href="{{ route('ajukan.view') }}"
                       class="nav-link d-flex align-items-center {{ request()->is('sppg/ajukan') ? 'active-link' : '' }}">
                        <i class="bi bi-send-plus me-2"></i>
                        Ajukan Menu
                    </a>
                </li>

                <!-- Input Menu Harian -->
                <li class="nav-item mb-2">
                    <a href="{{ route('sppg.input.menu.view') }}"
                       class="nav-link d-flex align-items-center {{ request()->is('sppg/menu') ? 'active-link' : '' }}">
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
                           class="nav-link d-flex align-items-center {{ request()->is('sppg/riwayat') ? 'active-link' : '' }}">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebarMenu');
            const toggleButton = document.getElementById('sidebarToggleMobile');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (!sidebar || !toggleButton || !backdrop) {
                return;
            }

            const closeSidebar = () => {
                sidebar.classList.remove('open');
                backdrop.classList.remove('show');
            };

            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('show');
            });

            backdrop.addEventListener('click', closeSidebar);

            document.addEventListener('keyup', (event) => {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });
        });
    </script>
@endpush
