@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">

    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    {{-- <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css')}}"> --}}

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

        .searchData:focus {
            border-color: #dfe3ea !important;
            box-shadow: none !important;
        }

        .parent-loading {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            background: rgba(27, 27, 27, .541);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        .parent-loading .loading-custom {
            position: relative;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 20px;
            padding-right: 20px;
            border-radius: 10px;
            background: white;
            left: 50%;
            transform: translate(-50%, 0);
            z-index: 99999;
        }
    </style>
@endsection

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>


    <!-- Page JS Plugins -->

    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.min.js') }}"></script>


    <!-- Page JS Code -->
    <script>
        One.helpersOnLoad(['js-flatpickr']);
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{-- <script src="{{ asset('js/pages/tables_datatables.js') }}"></script> --}}

    {{-- <script src="{{ asset('js/serahterima/list.js') }}"></script> --}}
    <script>
        $(document).ready(function() {});

        let filter = {};
        const table = $('.js-dataTable').DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 15, 20],
                [5, 10, 15, 20]
            ],
            autoWidth: false,
            ajax: {
                url: "{{ url('log-activitas') }}",
                data: function(d) {
                    d.filter = filter;
                    return d;
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id',
                    width: '5%'
                },
                {
                    data: (data) => {
                        return data.no_tanda_terima ?? data.no_ref;
                    },
                    name: 'no_tanda_terima',
                    width: '5%'
                },
                {
                    data: 'expedisi',
                    name: 'expedisi',
                    width: '10%'
                },
                {
                    data: 'no_resi',
                    name: 'no_resi',
                    width: '10%'
                },
                {
                    data: 'user',
                    name: 'user',
                    width: '10%'
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                    width: '15%'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    width: '10%'
                },
            ],
            dom: "<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>>" +
                "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });

        function handlerFilter(type) {
            $('.db-fltr').removeClass('active-fl');
            $(`span[data-type="${type}"]`).addClass('active-fl');
            if (type == 'range')
                $('#rangeTanggalFl').show();
            else
                $('#rangeTanggalFl').hide();
            if (type == 'all')
                delete filter.type;
            else
                filter.type = type;
            if (type !== 'range')
                table.ajax.reload();
        }

        $('#rangeTanggal').on('change', function() {
            filter.range_date = $(this).val();
            let filterSplit = filter.range_date.split(' to ');
            if (filterSplit.length > 0)
                table.ajax.reload();
        });

        const handlerViewResi = (serahTerimaId) => {
            $("#modal-detail").modal('show')
            $.ajax({
                type: 'POST',
                url: "{{ route('logactivitasdetail') }}",
                data: {
                    serahTerimaId
                },
                dataType: "JSON",
                success: function(response) {
                    $("#initDataDetail").empty();
                    if (response.length > 0) {
                        response.map((item, index) => {
                            $("#initDataDetail").append(`
                                <div class="py-2 px-4 border rounded">${index + 1 }. ${item.no_resi}</div>
                            `);
                        })
                    } else {
                        $("#initDataDetail").append(`
                                <div class="alert alert-danger w-100">No Data Available</div>
                            `);
                    }
                },
                error: function(xhr, status, error) {
                    initMessage('danger', 'fa fa-times', xhr.responseText)
                }
            });
        }
    </script>
@endsection

@section('content')
    <!-- Hero -->

    <div class="parent-loading" style="display: none">
        <div class="loading-custom">
            <div class="d-flex align-items-center justify-content-center">
                <div class="spinner-border spinner-border-sm me-3" role="status" aria-hidden="true"></div>
                <strong>Loading...</strong>
            </div>
        </div>
    </div>

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Laporan
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Log Activitas User
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Laporan</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Log Activitas
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table with Export Buttons -->

        <div class="block block-rounded mt-4">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Log Activitas
                </h3>
            </div>


            <div class="block-content block-content-full">
                @include('layouts._message')
                <div class="d-flex align-items-center justify-content-start mb-3">
                    <div style="width: 15%" class="fw-bold">Waktu</div>
                    <div style="width: 85%">
                        <div class="d-flex align-items-center mt-3">
                            @php
                                $listFilter = [
                                    [
                                        'type' => 'all',
                                        'name' => 'Semua',
                                    ],
                                    [
                                        'type' => 'now',
                                        'name' => 'Hari Ini',
                                    ],
                                    [
                                        'type' => 'yesterday',
                                        'name' => 'Kemarin',
                                    ],
                                    [
                                        'type' => '30day',
                                        'name' => '30 Hari',
                                    ],
                                    [
                                        'type' => 'range',
                                        'name' => 'Range Tanggal',
                                    ],
                                ];
                            @endphp
                            @foreach ($listFilter as $item)
                                <span class="db-fltr fs-sm me-4 {{ $item['type'] == 'all' ? 'active-fl' : '' }}"
                                    data-type="{{ $item['type'] }}"
                                    onclick="handlerFilter('{{ $item['type'] }}')">{{ $item['name'] }}</span>
                            @endforeach
                            <div id="rangeTanggalFl" style="display: none">
                                <input type="text" class="js-flatpickr form-control" id="rangeTanggal"
                                    name="rangeTanggal" placeholder="Select Date Range" data-mode="range"
                                    data-date-format="Y-m-d">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable fs-sm">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">#</th>
                                <th style="width: 200px;">No Referensi Data</th>
                                <th>Nama Expedisi</th>
                                <th>No Resi</th>
                                <th>User</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>

    <div class="modal fade" id="modal-detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modal-detailLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-detailLabel">Detail Data Resi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-3" style="flex-wrap: wrap" id="initDataDetail"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
