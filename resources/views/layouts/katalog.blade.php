<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Katalog</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendor Libraries CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
</head>


<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-bold ms-2">Katalog</span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <li class="menu-item {{ request()->is('katalog') ? 'active open' : '' }}">
                        <a href="{{ '/katalog' }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-smile"></i>
                            <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
                        </a>
                    </li>

                    @php
                        $folders = \App\Models\ParentsCategoriesKatalog::all();

                        $childCategories = \App\Models\ChildsCategoriesKatalog::whereIn(
                            'parents_id',
                            $folders->pluck('id'),
                        )
                            ->get()
                            ->groupBy('parents_id');
                    @endphp

                    @foreach ($folders as $folder)
                        @php
                            $isActiveParent =
                                request()->is('katalog/' . $folder->id . '/*') ||
                                request()->is('katalog/' . $folder->id);
                        @endphp

                        <li class="menu-item {{ $isActiveParent ? 'active open' : '' }}">
                            <a href="{{ route('katalog.show', $folder->id) }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-collection"></i>
                                <div class="text-truncate" data-i18n="Layouts">{{ $folder->name }}</div>
                                <i class="tf-icons bx bx-chevron-right arrow-icon"></i>
                            </a>

                            @if ($childCategories->has($folder->id))
                                <ul class="menu-sub">
                                    @foreach ($childCategories[$folder->id] as $file)
                                        <li
                                            class="menu-item {{ request()->is('katalog/' . $file->id) ? 'active' : '' }}">
                                            <a href="{{ route('katalog.show', $file->id) }}" class="menu-link">
                                                <div class="text-truncate" data-i18n="Vertical">{{ $file->name }}
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </aside>

            <div class="layout-page">

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="bx bx-menu bx-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search bx-md"></i>
                                <input type="text" id="global-search"
                                    class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
                                    aria-label="Search..." />
                            </div>
                        </div>

                        <script>
                            document.getElementById('global-search').addEventListener('input', function() {
                                const searchTerm = this.value.toLowerCase().trim();
                                const cards = Array.from(document.querySelectorAll('#main-container .grid-item'));

                                if (searchTerm === '') {
                                    cards.forEach(card => {
                                        card.style.display = '';
                                        removeHighlight(card);
                                    });
                                    resetGridLayout();
                                    return;
                                }

                                function highlightText(text, term) {
                                    const regex = new RegExp(`(${term})`, 'gi');
                                    return text.replace(regex, `<mark>$1</mark>`);
                                }

                                function removeHighlight(card) {
                                    const titleElement = card.querySelector('.card-title');
                                    const bodyElement = card.querySelector('.card-body .card-text');

                                    if (titleElement) {
                                        titleElement.innerHTML = titleElement.textContent;
                                    }
                                    if (bodyElement) {
                                        bodyElement.innerHTML = bodyElement.textContent;
                                    }
                                }

                                function resetGridLayout() {
                                    cards.forEach(card => {
                                        card.style.display = '';
                                    });
                                    const gridContainer = document.querySelector('.grid');
                                    if (gridContainer) {
                                        gridContainer.style.position = 'relative';
                                        const masonry = new Masonry(gridContainer, {
                                            itemSelector: '.grid-item',
                                            columnWidth: '.grid-sizer',
                                            percentPosition: true
                                        });
                                        masonry.layout();
                                    }
                                }

                                function arrangeCards() {
                                    const gridContainer = document.querySelector('.grid');
                                    if (gridContainer) {
                                        const masonry = new Masonry(gridContainer, {
                                            itemSelector: '.grid-item',
                                            columnWidth: '.grid-sizer',
                                            percentPosition: true
                                        });
                                        masonry.layout();
                                    }
                                }

                                cards.forEach((card) => {
                                    const titleElement = card.querySelector('.card-title');
                                    const bodyElement = card.querySelector('.card-body .card-text');

                                    const titleText = titleElement.textContent.toLowerCase();
                                    const bodyText = bodyElement.textContent.toLowerCase();

                                    if (titleText.includes(searchTerm) || bodyText.includes(searchTerm)) {
                                        card.style.display = '';

                                        if (titleElement) {
                                            titleElement.innerHTML = highlightText(titleElement.textContent, searchTerm);
                                        }
                                        if (bodyElement) {
                                            bodyElement.innerHTML = highlightText(bodyElement.textContent, searchTerm);
                                        }
                                    } else {
                                        card.style.display = 'none';
                                    }
                                });

                                arrangeCards();
                            });
                        </script>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Place this tag where you want the button to render. -->


                            <!-- User -->
                            {{-- <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="../assets/img/avatars/1.png" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="../assets/img/avatars/1.png" alt
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">John Doe</h6>
                                                    <small class="text-muted">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"> <i
                                                class="bx bx-cog bx-md me-3"></i><span>Settings</span> </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <span class="d-flex align-items-center align-middle">
                                                <i class="flex-shrink-0 bx bx-credit-card bx-md me-3"></i><span
                                                    class="flex-grow-1 align-middle">Billing Plan</span>
                                                <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <main id="main-container">
                            @yield('content')
                        </main>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="text-body">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    Security Scan
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>

        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
   

    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>


</body>

</html>
