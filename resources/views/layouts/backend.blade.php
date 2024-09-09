<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Security Scan</title>

    <meta name="description" content="member apps by agungapp">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ asset('/css/oneui.css') }}">

    <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="{{ '/css/themes/amethyst.css' }}"> -->
    @yield('css_after')

     <style>
        table#dataTable thead tr {
           background: #d9e8c3 !important;
        }
        table#dataTable thead th {
           background: #d9e8c3 !important;
        }
    </style>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    </script>
</head>

<body>

    <div id="page-container"
        class="sidebar-o enable-page-overlay sidebar-dark side-scroll page-header-fixed main-content-narrow">
        <!-- Side Overlay-->
        <aside id="side-overlay" class="fs-sm">
            <!-- Side Header -->
            <div class="content-header border-bottom">
                <!-- User Avatar -->
                <a class="img-link me-1" href="javascript:void(0)">
                    <img class="img-avatar img-avatar32" src="{{ asset('media/avatars/avatar0.jpg') }}" alt="">
                </a>
                <!-- END User Avatar -->

                <!-- User Info -->
                <div class="ms-2">
                    <a class="text-dark fw-semibold fs-sm" href="javascript:void(0)">{{ Auth::user()->name }}</a>
                </div>
                <!-- END User Info -->

                <!-- Close Side Overlay -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="ms-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout"
                    data-action="side_overlay_close">
                    <i class="fa fa-fw fa-times"></i>
                </a>
                <!-- END Close Side Overlay -->
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="content-side">
                <p>
                    Content..
                </p>
            </div>
            <!-- END Side Content -->
        </aside>
        <!-- END Side Overlay -->

        <!-- Sidebar -->

        <nav id="sidebar" aria-label="Main Navigation">
            <!-- Side Header -->
            <div class="content-header">
                <!-- Logo -->
                <a class="fw-semibold text-dual" href="/home">
                    <span class="smini-visible">
                        <i class="fa fa-circle-notch text-primary"></i>
                    </span>
                    <span class="smini-hide fs-5 tracking-wider">Security<span class="fw-normal">Scan</span></span>
                </a>
                <!-- END Logo -->

                <!-- Extra -->
                <div>
                    <!-- Dark Mode -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="btn btn-sm btn-alt-secondary" data-toggle="layout" data-action="dark_mode_toggle"
                        href="javascript:void(0)">
                        <i class="far fa-moon"></i>
                    </a>
                    <!-- END Dark Mode -->

                    <!-- Options -->
                    <div class="dropdown d-inline-block ms-1">
                        <a class="btn btn-sm btn-alt-secondary" id="sidebar-themes-dropdown" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" href="#">
                            <i class="far fa-circle"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end fs-sm smini-hide border-0"
                            aria-labelledby="sidebar-themes-dropdown">
                            <!-- Color Themes -->
                            <!-- Layout API, functionality initialized in Template._uiHandleTheme() -->
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-medium"
                                data-toggle="theme" data-theme="default" href="#">
                                <span>Default</span>
                                <i class="fa fa-circle text-default"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-medium"
                                data-toggle="theme" data-theme="{{ 'css/themes/amethyst.css' }}" href="#">
                                <span>Amethyst</span>
                                <i class="fa fa-circle text-amethyst"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-medium"
                                data-toggle="theme" data-theme="{{ 'css/themes/city.css' }}" href="#">
                                <span>City</span>
                                <i class="fa fa-circle text-city"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-medium"
                                data-toggle="theme" data-theme="{{ 'css/themes/flat.css' }}" href="#">
                                <span>Flat</span>
                                <i class="fa fa-circle text-flat"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-medium"
                                data-toggle="theme" data-theme="{{ 'css/themes/modern.css' }}" href="#">
                                <span>Modern</span>
                                <i class="fa fa-circle text-modern"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-medium"
                                data-toggle="theme" data-theme="{{ 'css/themes/smooth.css' }}" href="#">
                                <span>Smooth</span>
                                <i class="fa fa-circle text-smooth"></i>
                            </a>
                            <!-- END Color Themes -->

                            <div class="dropdown-divider"></div>

                            <!-- Sidebar Styles -->
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <a class="dropdown-item fw-medium" data-toggle="layout" data-action="sidebar_style_light"
                                href="javascript:void(0)">
                                <span>Sidebar Light</span>
                            </a>
                            <a class="dropdown-item fw-medium" data-toggle="layout" data-action="sidebar_style_dark"
                                href="javascript:void(0)">
                                <span>Sidebar Dark</span>
                            </a>
                            <!-- END Sidebar Styles -->

                            <div class="dropdown-divider"></div>

                            <!-- Header Styles -->
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <a class="dropdown-item fw-medium" data-toggle="layout" data-action="header_style_light"
                                href="javascript:void(0)">
                                <span>Header Light</span>
                            </a>
                            <a class="dropdown-item fw-medium" data-toggle="layout" data-action="header_style_dark"
                                href="javascript:void(0)">
                                <span>Header Dark</span>
                            </a>
                            <!-- END Header Styles -->
                        </div>
                    </div>
                    <!-- END Options -->

                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout"
                        data-action="sidebar_close" href="javascript:void(0)">
                        <i class="fa fa-fw fa-times"></i>
                    </a>
                    <!-- END Close Sidebar -->
                </div>
                <!-- END Extra -->
            </div>
            <!-- END Side Header -->

            <!-- Sidebar Scrolling -->
            <div class="js-sidebar-scroll">
                <!-- Side Navigation -->
                <div class="content-side">
                    <ul class="nav-main">
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('dashboard') ? ' active' : '' }}" href="/home">
                                <i class="nav-main-link-icon si si-speedometer"></i>
                                <span class="nav-main-link-name">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            @if(canPermission('Daftar Serah Terima'))
                            <a class="nav-main-link{{ request()->is('dashboard') ? ' active' : '' }}"
                                href="{{ route('serahterima.index') }}">
                                <i class="nav-main-link-icon si si-cursor"></i>
                                <span class="nav-main-link-name">Daftar Serah Terima</span>
                            </a>
                            @endif
                            {{-- @if(canPermission('Daftar Serah Terima.Create'))
                            <a class="nav-main-link{{ request()->is('dashboard') ? ' active' : '' }}"
                                href="{{ route('serahterima.create') }}">
                                <i class="nav-main-link-icon si si-plus"></i>
                                <span class="nav-main-link-name">Serah Terima Baru</span>
                            </a>
                            @endif --}}
                        </li>
                        @if (Auth::user()->role == 'admin' || canPermission('Daftar Blacklist', true) || canPermission('Daftar Blacklist.Create', true))
                        <li class="nav-main-heading">Blacklist Resi</li>
                        <li class="nav-main-item open">
                            @if(canPermission('Daftar Blacklist', true))
                            <a class="nav-main-link" href="{{ route('blacklist') }}">
                                <i class="nav-main-link-icon si si-rocket"></i>
                                <span class="nav-main-link-name">Daftar Blacklist</span>
                            </a>
                            @endif
                        </li>
                        @endif
                        @if (canPermission('Daftar Retur') || canPermission('Daftar Returan.Create') || canPermission('Daftar Inbound Retur'))
                        <li class="nav-main-heading">Returan</li>
                        @if (canPermission('Daftar Inbound Retur', true))
                        <a class="nav-main-link{{ request()->is('inbound-retur') ? ' active' : '' }}"
                            href="{{ route('inbound-retur.index') }}">
                            <i class="nav-main-link-icon si si-cursor"></i>
                            <span class="nav-main-link-name">Daftar Inbound Retur</span>
                        </a>
                        @endif
                        <li class="nav-main-item open">
                            @if (canPermission('Daftar Returan'))
                            <a class="nav-main-link" href="{{ route('retur.index') }}">
                                <i class="nav-main-link-icon si si-rocket"></i>
                                <span class="nav-main-link-name">Daftar Returan</span>
                            </a>
                            @endif
                        </li>
                        @endif

                        @if (canPermission('Daftar Komplain') || canPermission('Daftar Komplain.Create'))
                        <li class="nav-main-heading">Pusat Komplain Manual</li>
                        <li class="nav-main-item open">
                            @if (canPermission('Daftar Komplain'))
                            <a class="nav-main-link" href="{{ route('manual-complaint.index') }}">
                                <i class="nav-main-link-icon si si-rocket"></i>
                                <span class="nav-main-link-name">Daftar Komplain</span>
                            </a>
                            @endif
                        </li>
                        @endif

                        @if (canPermission('Stock Opname Request') || canPermission('Stock Opname Request.Create'))
                        <li class="nav-main-heading">Stock Opname Request</li>
                        <li class="nav-main-item open">
                            @if (canPermission('Stock Opname Request'))
                            <a class="nav-main-link" href="{{ route('stock-opname-request.index') }}">
                                <i class="nav-main-link-icon si si-rocket"></i>
                                <span class="nav-main-link-name">Stock Opname Request</span>
                            </a>
                            @endif
                        </li>
                        @endif

                        @if(canPermission('Daftar Request Refund') || canPermission('Daftar Request Refund.Create'))
                        <li class="nav-main-heading">Request Refund</li>
                        <li class="nav-main-item open">
                            @if(canPermission('Daftar Request Refund'))
                            <a class="nav-main-link" href="{{ route('request-refund.index') }}">
                                <i class="nav-main-link-icon si si-rocket"></i>
                                <span class="nav-main-link-name">Daftar Request Refund</span>
                            </a>
                            @endif
                        </li>
                        @endif

                        <li class="nav-main-heading">Menu Kustom</li>
                        <li class="nav-main-item open">
                            @if (canPermission("Daftar Menu Kustom"))
                           <a class="nav-main-link" href="{{ route('custom-menu.index') }}">
                                <i class="nav-main-link-icon si si-rocket"></i>
                                <span class="nav-main-link-name">Menu Kustom</span>
                            </a>
                           @endif
                            @php
                                $customMenu = App\Models\CustomMenu::orderBy('name', 'asc')->get();
                            @endphp

                            @foreach ($customMenu as $item)
                            @if (canPermission($item->permission_name))
                            <a class="nav-main-link" href="{{ route('custom-menu.menu', $item->slug) }}">
                                <i class="nav-main-link-icon si si-rocket"></i>
                                <span class="nav-main-link-name">{{ $item->name }}</span>
                            </a>
                            @endif
                            @endforeach
                        </li>

                        @if(canPermission('Laporan Karyawan') || canPermission('Laporan Karyawan.Create'))
                        <li class="nav-main-heading">Laporan Karyawan</li>
                        <li class="nav-main-item open">
                            @if(canPermission('Laporan Karyawan'))
                            <a class="nav-main-link" href="{{ route('laporan-karyawan.index') }}">
                                <i class="nav-main-link-icon si si-rocket"></i>
                                <span class="nav-main-link-name">Daftar Laporan Karyawan</span>
                            </a>
                            @endif
                        </li>
                        @endif

                        @if (Auth::user()->role == 'admin')

                            <li class="nav-main-heading">Master Data</li>
                            <li class="nav-main-item open">
                                <a class="nav-main-link" href="{{ route('expedisi.index') }}">
                                    <i class="nav-main-link-icon si si-rocket"></i>
                                    <span class="nav-main-link-name">Master Expedisi</span>
                                </a>
                                <a class="nav-main-link" href="{{ route('shop.index') }}">
                                    <i class="nav-main-link-icon si si-users"></i>
                                    <span class="nav-main-link-name">Master Toko</span>
                                </a>
                                <a class="nav-main-link" href="{{ route('user.index') }}">
                                    <i class="nav-main-link-icon si si-users"></i>
                                    <span class="nav-main-link-name">User</span>
                                </a>
                                <a class="nav-main-link" href="{{ route('role-permission.index') }}">
                                    <i class="nav-main-link-icon si si-users"></i>
                                    <span class="nav-main-link-name">Peran dan Hak Akses</span>
                                </a>
                                <a class="nav-main-link" href="{{ route('pic-report.index') }}">
                                    <i class="nav-main-link-icon si si-rocket"></i>
                                    <span class="nav-main-link-name">Master PIC Laporan</span>
                                </a>
                            </li>

                            <li class="nav-main-heading">Katalog</li>
                            <li class="nav-main-item">
                                {{-- <a class="nav-main-link" href="{{ url('laravel-filemanager') }}" target="_blank">
                                    <i class="nav-main-link-icon si si-doc"></i>
                                    <span class="nav-main-link-name">File Manager</span>
                                </a> --}}
                                <a class="nav-main-link" href="{{ route('katalog') }}">
                                    <i class="nav-main-link-icon si si-doc"></i>
                                    <span class="nav-main-link-name">Katalog</span>
                                </a>
                            </li>

                            <li class="nav-main-heading">Laporan</li>
                            <li class="nav-main-item open">
                                <a class="nav-main-link" href="{{ route('logactivitas') }}">
                                    <i class="nav-main-link-icon si si-notebook"></i>
                                    <span class="nav-main-link-name">Log Activitas User</span>
                                </a>
                            </li>

                        {{-- <li class="nav-main-heading">Setting</li>
                        <li class="nav-main-item open">
                            <a class="nav-main-link" href="{{ route('settingakses') }}">
                                <i class="nav-main-link-icon si si-settings"></i>
                                <span class="nav-main-link-name">User Akses Drop Data</span>
                            </a>
                        </li> --}}
                        @endif


                        <li class="nav-main-heading">Log Out</li>
                        <li class="nav-main-item open">
                            <a class="nav-main-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="nav-main-link-icon si si-logout"></i>
                                <span class="nav-main-link-name">Log Out</span>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END Side Navigation -->
            </div>
            <!-- END Sidebar Scrolling -->
        </nav>
        <!-- END Sidebar -->

        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">
                    <!-- Toggle Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout"
                        data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <!-- END Toggle Sidebar -->

                    <!-- Toggle Mini Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-none d-lg-inline-block"
                        data-toggle="layout" data-action="sidebar_mini_toggle">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                    </button>
                    <!-- END Toggle Mini Sidebar -->

                    <!-- Open Search Section (visible on smaller screens) -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-sm btn-alt-secondary d-md-none" data-toggle="layout"
                        data-action="header_search_on">
                        <i class="fa fa-fw fa-search"></i>
                    </button>
                    <!-- END Open Search Section -->

                </div>
                <!-- END Left Section -->

                <!-- Right Section -->
                <div class="d-flex align-items-center">
                    <!-- User Dropdown -->
                    <div class="dropdown d-inline-block ms-2">
                        <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                            id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            @if (Auth::user()->foto_profile == '')
                                <img class="rounded-circle" src="{{ asset('media/avatars/avatar0.jpg') }}"
                                    alt="Header Avatar" style="width: 21px;">
                            @else
                                <img class="rounded-circle"
                                    src="{{ asset('storage/profile/' . Auth::user()->foto_profile) }}"
                                    alt="Header Avatar" style="width: 21px; height:21px;">
                            @endif
                            <span class="d-none d-sm-inline-block ms-2">{{ Auth::user()->name }}</span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ms-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                            aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                                @if (Auth::user()->foto_profile == '')
                                    <img class="img-avatar img-avatar48 img-avatar-thumb"
                                        src="{{ asset('media/avatars/avatar0.jpg') }}" alt="">
                                @else
                                    <img class="img-avatar img-avatar48 img-avatar-thumb"
                                        src="{{ asset('storage/profile/' . Auth::user()->foto_profile) }}"
                                        alt="">
                                @endif
                                <p class="mt-2 mb-0 fw-medium">{{ Auth::user()->name }}</p>
                                <p class="mb-0 text-muted fs-sm fw-medium">{{ Auth::user()->role }}</p>
                            </div>
                            <div role="separator" class="dropdown-divider m-0"></div>
                            <div class="p-2">
                                <a href="#"
                                    class="dropdown-item d-flex align-items-center justify-content-between resetPassword"
                                    onclick="handlerResetPassword(event)">
                                    <span class="fs-sm fw-medium">Reset Password</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                            <div role="separator" class="dropdown-divider m-0"></div>
                            <div class="p-2">
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <span class="fs-sm fw-medium">Log Out</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END User Dropdown -->


                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

            <!-- Header Search -->
            <div id="page-header-search" class="overlay-header bg-body-extra-light">
                <div class="content-header">
                    <form class="w-100" action="/dashboard" method="POST">
                        @csrf
                        <div class="input-group">
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <button type="button" class="btn btn-alt-danger" data-toggle="layout"
                                data-action="header_search_off">
                                <i class="fa fa-fw fa-times-circle"></i>
                            </button>
                            <input type="text" class="form-control" placeholder="Search or hit ESC.."
                                id="page-header-search-input" name="page-header-search-input">
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Header Search -->

            <!-- Header Loader -->
            <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
            <div id="page-header-loader" class="overlay-header bg-body-extra-light">
                <div class="content-header">
                    <div class="w-100 text-center">
                        <i class="fa fa-fw fa-circle-notch fa-spin"></i>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </header>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            @yield('content')
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <footer id="page-footer" class="bg-body-light">
            <div class="content py-3">
                <div class="row fs-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
                        <!-- Development<i class="fa fa-heart text-danger"></i> by <a class="fw-semibold" href="/" target="_blank"></a> -->
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
                        <a class="fw-bold" href="" target="_blank">Security Scan</a> &copy; <span
                            data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!-- modal-reset -->
    <div class="modal fade" id="resetPassword" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Reset Password</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal"
                                aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <span id="form_result"></span>
                        <form id="resetPasswordForm">
                            <div class="form-floating mb-4">
                                <input hidden required="" autocomplete="off" type="text" class="form-control"
                                    id="reset_id" name="reset_id" value="{{ Auth::user()->id }}">
                                <input id="reset_password" name="reset_password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label for="example-text-input-floating">New Password</label>
                            </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary btn-reset-pass">Reset</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END modal reset -->


    <!-- OneUI Core JS -->
    <script src="{{ asset('js/oneui.app.js') }}"></script>

    <!-- Laravel Scaffolding JS -->
    <!-- <script src="{{ '/js/laravel.app.js' }}"></script> -->

    @yield('js_after')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const baseUrl = "{{ url('/') }}";
        $(document).on('show.bs.dropdown', function(e) {
            var dropdown = $(e.target).parents('.dropdown').find('.dropdown-menu');
                dropdown.appendTo('main');

            $(this).on('hidden.bs.dropdown', function () {
                //dropdown.appendTo(e.target);
            })
        });
    </script>
    <script src="{{ asset('js/master/user.js') }}"></script>
</body>

</html>
