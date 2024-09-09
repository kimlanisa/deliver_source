@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->

    <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css') }}">
    <link rel="stylesheet" id="css-main"
        href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <style>
        .db-fltr {
            cursor: pointer;
        }

        .db-fltr:hover {
            padding: 7px;
            border-radius: 10px;
            background: #eff6ff;
            font-weight: normal;
        }

        .active-fl {
            padding: 7px;
            border-radius: 10px;
            background: #eff6ff;
            font-weight: bold;
        }
    </style>
@endsection

@section('js_after')
@if(canPermission('Dashboard'))
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <script>
        One.helpersOnLoad(['js-flatpickr', 'jq-datepicker']);
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            const lastFiltered = getDataLocalStorage();
            if (Object.keys(lastFiltered).length > 0) {
                if (lastFiltered.type !== "range") {
                    handlerFilterDashboard(lastFiltered.type);
                } else {
                    if (lastFiltered.dateRange !== null) {
                        setTimeout(() => {
                            const fp = flatpickr("#rangeTanggal", {
                                onChange: function(selectedDates, dateStr, instance) {
                                    if (dateStr.includes("to")) {
                                        ajaxRequestGetData(lastFiltered.type, dateStr)
                                        localStorage.setItem("items", JSON.stringify({
                                            type: lastFiltered.type,
                                            dateRange: dateStr.split(' to ')
                                        }));
                                    }
                                },
                            });

                            fp.setDate(lastFiltered.dateRange);
                            if (fp.input.value.includes("to")) {
                                ajaxRequestGetData(lastFiltered.type, fp.input.value)
                            }
                        }, 800)
                        handlerFilterDashboard(lastFiltered.type);
                    } else {
                        console.log('sama dengan null');
                        handlerFilterDashboard(lastFiltered.type);
                    }

                }
            } else {
                handlerFilterDashboard('all');
            }
        })

        const handlerFilterDashboard = (type) => {
            if (type === 'all') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filterAll").addClass("active-fl")
            }

            if (type === 'now') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filterNow").addClass("active-fl")
            }

            if (type === 'yesterday') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filterYesterday").addClass("active-fl")
            }

            if (type === '7') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filter7Days").addClass("active-fl")
            }

            if (type === '30') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filter30Days").addClass("active-fl")
            }

            if (type === 'range') {
                removeClass();
                $("#rangeTanggalFl").show('slow');
                $(".filterRange").addClass("active-fl")
            }

            if (type !== 'range') {
                ajaxRequestGetData(type, null)
                localStorage.setItem("items", JSON.stringify({
                    type,
                    dateRange: null,
                    keywordSearch: null,
                    searchBy: null
                }));
            } else {
                flatpickr("#rangeTanggal", {
                    onChange: function(selectedDates, dateStr, instance) {
                        if (dateStr.includes("to")) {
                            ajaxRequestGetData(type, dateStr)
                            localStorage.setItem("items", JSON.stringify({
                                type,
                                dateRange: dateStr.split(' to '),
                                keywordSearch: null,
                                searchBy: null
                            }));
                        }
                    },
                });
            }
        }

        const ajaxRequestGetData = (type, dateStr) => {
            $(".items-push").empty();
            $(".items-push").append(`
                <div class="d-flex align-items-center justify-content-center mt-5">
                    <div class="spinner-border me-2" role="status"></div>
                    <div class="fw-bold">Loading...</div>
                </div>
            `)
            setTimeout(() => {
                $.ajax({
                    url: "{{ route('getData') }}",
                    method: "POST",
                    data: {
                        type,
                        dateStr
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $(".items-push").empty();
                        $(".items-push").append(`
                        <div class="d-flex align-items-center justify-content-center mt-5">
                            <div class="spinner-border me-2" role="status"></div>
                            <div class="fw-bold">Loading...</div>
                        </div>
                        `)
                    },
                    success: function(response) {
                        $(".items-push").empty();
                        if (response.data.length > 0) {
                            response.data.map((item) => {
                                $(".items-push").append(`
                                    <div class="col-sm-6 col-xxl-4">
                                        <div class="block block-rounded d-flex flex-column h-100 mb-0" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;color:white;background: ${item.color == null || item.color == "" ? '#2B4C99' : item.color}">
                                            <div class="block-content block-content-full">
                                                <dt class="fw-bold text-center countJne" style="font-size: 4rem">${item.total}</dt>
                                                <dd class="fs-2 fw-semibold text-center mb-0" style="color: #cbd5e1;text-shadow: 0 3px 8.896px #686363,0 -2px 1px #fff;">${item.expedisi}</dd>
                                            </div>
                                        </div>
                                    </div>
                                `)
                            })
                        } else {
                            $(".items-push").append(`
                                <div class="col-sm-12 col-xxl-12">
                                    <div class="alert alert-danger text-center h-75">
                                        <h2>No Data Available</h2>
                                    </div>
                                </div>
                            `)
                        }
                    },
                });
            }, 1000);
        }

        const removeClass = () => {
            $(".filterAll").removeClass("active-fl")
            $(".filterNow").removeClass("active-fl")
            $(".filterYesterday").removeClass("active-fl")
            $(".filter7Days").removeClass("active-fl")
            $(".filter30Days").removeClass("active-fl")
            $(".filterRange").removeClass("active-fl")
            $("#rangeTanggal").val("")
        }

        const getDataLocalStorage = () => {
            let items = [];

            if (localStorage.getItem("items")) {
                items = JSON.parse(localStorage.getItem("items"));
            }
            return Object.assign({}, items);
        }
    </script>
@endif
@endsection

@section('content')
    <main id="main-container">
        <div class="content">
            <div
                class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
                <div class="flex-grow-1 mb-1 mb-md-0">
                    <h1 class="h3 fw-bold mb-2">
                        Dashboard
                    </h1>
                    <h2 class="h6 fw-medium fw-medium text-muted mb-0">
                        Welcome <a class="fw-semibold" href="javascript:void();">{{ Auth::user()->name }}</a>, everything
                        looks great.
                    </h2>
                </div>
                <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                    {{-- <div class="dropdown d-inline-block">
                        <button type="button" class="btn btn-sm btn-alt-secondary space-x-1"
                            id="dropdown-analytics-overview" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-fw fa-calendar-alt opacity-50"></i>
                            <span>All time</span>
                            <i class="fa fa-fw fa-angle-down"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-overview"
                            style="">
                            <a class="dropdown-item fw-medium" href="javascript:void(0)">Last 30 days</a>
                            <a class="dropdown-item fw-medium" href="javascript:void(0)">Last month</a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between"
                                href="javascript:void(0)">
                                <span>All time</span>
                                <i class="fa fa-check"></i>
                            </a>
                        </div>
                    </div> --}}
                </div>
            </div>
            @if(canPermission('Dashboard'))
            <hr>
            <div class="d-flex align-items-center mt-3">
                <span class="db-fltr fs-sm me-4 filterAll" onclick="handlerFilterDashboard('all')">Semua</span>
                <span class="db-fltr fs-sm me-4 filterNow" onclick="handlerFilterDashboard('now')">Hari Ini</span>
                <span class="db-fltr fs-sm me-4 filterYesterday"
                    onclick="handlerFilterDashboard('yesterday')">Kemarin</span>
                <span class="db-fltr fs-sm me-4 filter7Days" onclick="handlerFilterDashboard('7')">7 Hari</span>
                <span class="db-fltr fs-sm me-4 filter30Days" onclick="handlerFilterDashboard('30')">30 Hari</span>
                <span class="db-fltr fs-sm me-4 filterRange" onclick="handlerFilterDashboard('range')">Range Tanggal</span>
                <div id="rangeTanggalFl" style="display: none">
                    <input type="text" class="js-flatpickr form-control" id="rangeTanggal" name="rangeTanggal"
                        placeholder="Select Date Range" data-mode="range" data-date-format="Y-m-d">
                </div>
            </div>
            @endif
        </div>
        @if(canPermission('Dashboard'))
        <div class="content">
            <div class="row items-push"></div>
        </div>
        @endif
    </main>
@endsection
