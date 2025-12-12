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
            </div>

            @auth
                @php
                    $dashboardRoute = auth()->user()->role === 'vendor'
                        ? 'sppg.dashboard'
                        : 'dashboard';

                    $manageSchoolUrl = \Illuminate\Support\Facades\Route::has('admin.schools.index')
                        ? route('admin.schools.index')
                        : '#';

                    $approvalMenuUrl = \Illuminate\Support\Facades\Route::has('admin.menus.approval')
                        ? route('admin.menus.approval')
                        : '#';

                    $navSections = [
                        [
                            'title' => 'DASHBOARD',
                            'items' => [
                                [
                                    'label' => 'Dashboard',
                                    'icon' => 'bi-speedometer2',
                                    'url' => route($dashboardRoute),
                                    'active' => request()->is('*/dashboard'),
                                ],
                            ],
                        ],
                        [
                            'title' => 'ADMIN FEATURES',
                            'items' => [
                                [
                                    'label' => 'Manage SPPG',
                                    'icon' => 'bi-people',
                                    'url' => route('admin.vendors.index'),
                                    'active' => request()->is('admin/vendors*'),
                                ],
                                [
                                    'label' => 'Manage School',
                                    'icon' => 'bi-building',
                                    'url' => $manageSchoolUrl,
                                    'active' => request()->is('admin/schools*'),
                                ],
                                [
                                    'label' => 'Approval Menu',
                                    'icon' => 'bi-check2-square',
                                    'url' => $approvalMenuUrl,
                                    'active' => request()->is('admin/menus/approval*'),
                                ],
                            ],
                        ],
                    ];
                @endphp

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

                    #sidebarMenu .section-label {
                        letter-spacing: .1em;
                        font-size: .7rem;
                        color: #9aa0b2;
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

                    #sidebarMenu .nav-link.disabled-link {
                        color: #b6bac4;
                        cursor: not-allowed;
                    }
                </style>

                <ul class="nav flex-column px-3 py-3 flex-grow-1">
                    @foreach($navSections as $section)
                        <p class="section-label mb-2 mt-3">{{ $section['title'] }}</p>

                        @foreach($section['items'] as $item)
                            @php
                                $isDisabled = $item['url'] === '#';
                            @endphp
                            <li class="nav-item mb-1">
                                <a href="{{ $item['url'] }}"
                                   class="nav-link d-flex align-items-center {{ $item['active'] ? 'active-link' : '' }} {{ $isDisabled ? 'disabled-link' : '' }}">
                                    <i class="bi {{ $item['icon'] }} me-2"></i>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach

                        @if(!$loop->last)
                            <hr class="my-3">
                        @endif
                    @endforeach

                    <li class="nav-item mt-4 mb-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            @endauth

        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow-1 p-4 main-shell">
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
