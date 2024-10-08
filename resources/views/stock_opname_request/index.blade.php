@extends('layouts.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Permintan Stock Opname
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Daftar Permintaan Stock Opname
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Stock Opname
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

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Filter
                </h3>
                @if (canPermission('Stock Opname Request.Create'))
                <a href="{{ route('stock-opname-request.create') }}" class="btn btn-sm btn-alt-secondary"><i
                        class="fa fa-plus text-info me-1"></i>Stock Opname</a>
                @endif
            </div>
            <div class="block-content block-content-full">
                <div class="container">
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
                    <div class="d-flex align-items-center justify-content-start mt-3">
                        <div style="width: 15%" class="fw-bold">Pencarian</div>
                        <div style="width: 85%">
                            <div class="d-flex align-items-center">
                                <div style="width: 60%">
                                    <input type="text" name="valueSearch" id="valueSearch"
                                        class="form-control searchData w-100"
                                        style="border-top-left-radius:0;border-bottom-left-radius:0;"
                                        placeholder="Search keywoard data..." onkeydown="handlerSearchDataByKeyword(event)">
                                </div>
                                {{-- <div style="width: 20%">
                                    <select name="searchType" id="searchType" class="form-control searchData"
                                        style="border-left: 0;border-top-left-radius:0;border-bottom-left-radius:0;cursor:pointer">
                                        <option value="1">Spesifik</option>
                                        <option value="2">Samar</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-rounded mt-4">
            <div class="block-header block-header-default d-flex justify-content-between pb-0">
                <ul class="nav nav-tabs tabs-process" style="cursor: pointer">
                    <li class="nav-item">
                        <div data-type="on_process"  onclick="handleFilterStatus('on_process')" class="nav-link">Perlu Diproses</div>
                    </li>
                    <li class="nav-item">
                        <div  data-type="done_process" onclick="handleFilterStatus('done_process')" class="nav-link">Telah Diproses</div>
                    </li>
                    <li class="nav-item">
                        <div data-type="all"  onclick="handleFilterStatus('all')" class="nav-link">Semua</div>
                    </li>
                </ul>
                <button type="button" onclick="handlerExportToExcel(event)" class="btn btn-alt-success btn-md">
                    <i class="fas fa-file-excel"></i>
                    Excel
                </button>
            </div>


            <div class="block-content block-content-full">
                @include('layouts._message')
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter fs-sm" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">#</th>
                                <th style="width: 200px;">No TRX</th>
                                <th>Tanggal</th>
                                <th>SKU</th>
                                <th>Minus</th>
                                <th>Tambah</th>
                                <th>Nama User</th>
                                <th>Waktu Dibuat</th>
                                <th style="width: 15%;" class="sticky-table">Status</th>
                                <th style="width: 15%;" class="sticky-table">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>

    <!-- modal delete -->
    <div class="modal" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Hapus Data?</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <h5>Yakin akan menghapus data ini?</h5>
                    </div>
                    <form id="submitHandle">
                        <div class="block-content block-content-full text-end bg-body">
                            <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                                data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Small Block Modal -->

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">

    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
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

        div[data-notify="container"] {
            z-index: 999999 !important;
        }

        .sticky-table {
            position: sticky;
            right: 0;
            background: white !important;
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
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>


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
    <script>
        let filter = {}
        let idDelete = null;
        $(document).on('click', '.btn-delete', function() {
            idDelete = $(this).data('id');
        })
        const table = $("#dataTable").DataTable({
            lengthMenu: [
                [10, 25, 50, 100, 500, -1],
                [10, 25, 50, 100, 500, "All"],
            ],
            searching: false,
            responsive: false,
            lengthChange: true,
            autoWidth: false,
            order: [],
            pagingType: "full_numbers",
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari...",
                processing: '<div class="spinner-border text-info" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    "</div>",
                paginate: {
                    Search: '<i class="icon-search"></i>',
                    first: "<i class='fas fa-angle-double-left'></i>",
                    previous: "<i class='fas fa-angle-left'></i>",
                    next: "<i class='fas fa-angle-right'></i>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                },
            },
            oLanguage: {
                sSearch: "",
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: `{{ route('stock-opname-request.dataTable') }}`,
                method: "POST",
                data: function(d) {
                    d.filter = filter;
                    return d;
                },
            },
            columns: [{
                    name: "created_at",
                    data: "DT_RowIndex",
                },
                {
                    name: "no_trx",
                    data: ({
                        no_trx
                    }) => {
                        // return `<a style="white-space: nowrap" href="{{ route('stock-opname-request.index') }}/${no_trx}">${no_trx}</a>`;
                        return `<span style="white-space: nowrap">${no_trx}</span>`
                    }
                },
                {
                    name: "date",
                    data: (item) => {
                        return `<span class="" style="white-space: nowrap">${moment(item.date).format('DD-MM-YYYY')}</span>`
                    },
                },
                {
                    name: "sku",
                    data: (item) => {
                        return `<span class="" style="white-space: nowrap">${item.sku}</span>`
                    },
                },
                {
                    name: "minus",
                    data: (data) => {
                        return data?.minus ? `<div class="badge bg-danger" style="font-weight: bold; font-size: 14px">${data?.minus}</div>` : ''
                    } ,
                },
                {
                    name: "plus",
                    data: (data) => {
                        return data?.plus ? `<div class="badge bg-success" style="font-weight: bold; font-size: 14px">${data?.plus}</div>` : ''
                    },
                },
                {
                    name: "user.name",
                    data: (item) => {
                        return item?.user?.name ?? '';
                    },
                },
                {
                    name: "created_at",
                    data: (item) => {
                        return `<span style="white-space: nowrap">${moment(item?.created_at).format('DD-MM-YYYY HH:mm:ss')}</span>`;
                    },
                },
                {
                    name: "status",
                    className: 'sticky-table',
                    data: (item) => {
                        return `
                        <span class="text-${item?.status == 1 ? 'danger' : 'success'}" style="font-weight: 700; white-space: nowrap">${item?.status == 1 ? 'Belum Selesai' : 'Selesai'}</span>
                        ${
                            item?.status == 2 ? `
                            <div style="font-weight: bold;" class="text-primary text-nowrap">
                            Di proses oleh ${item?.process_by?.name ?? '-'}<br>
                            pada ${item?.process_at ? moment(item?.process_at).format('DD-MM-YYYY HH:mm:ss') : '-'}
                            </div>
                                ` : ''
                        }
                        `;
                    },
                },
                {
                    name: "action",
                    className: "sticky-table",
                    data: 'action',
                    orderable: false,
                },

            ],
        });
        var debounceTimer;

        function handlerSearchDataByKeyword(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filter.keyword = e.target.value;
                table.ajax.reload();
            }, 500);
        }


        function handleFilterStatus(type) {
            $('.tabs-process .nav-link').removeClass('active');
            $(`div[data-type="${type}"]`).addClass('active');
            if (type == 'all')
                delete filter.status;
            else
                filter.status = type;
            table.ajax.reload();
        }

        handleFilterStatus('on_process');

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

        table.on('click', '.actionProcess', function() {
            let id = $(this).data('id');
            let url = `{{ route('stock-opname-request.update', ':id') }}`;
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _method: 'PUT'
                },
                success: function(res) {
                    if (res.status) {
                        table.ajax.reload();
                        $.notify({
                            title: 'Success',
                            message: res.message,
                            icon: 'fa fa-check'
                        }, {
                            type: 'success'
                        });
                    } else {
                        $.notify({
                            title: 'Error',
                            message: res.message,
                            icon: 'fa fa-times'
                        }, {
                            type: 'danger'
                        });
                    }
                },
                error: function(err) {
                    $.notify({
                        title: 'Error',
                        message: 'Terjadi kesalahan',
                        icon: 'fa fa-times'
                    }, {
                        type: 'danger'
                    });
                }
            });
        });

        $('#submitHandle').on('submit', function(e) {
            e.preventDefault();
            let url = `{{ route('stock-opname-request.destroy', ':id') }}`;
            url = url.replace(':id', idDelete);
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    id: idDelete,
                    _method: 'DELETE'
                },
                success: function(res) {
                    if (res.status) {
                        table.ajax.reload();
                        $('#modal-delete').modal('hide');
                        $.notify({
                            title: 'Success',
                            message: res.message,
                            icon: 'fa fa-check'
                        }, {
                            type: 'success'
                        });
                    } else {
                        $.notify({
                            title: 'Error',
                            message: res.message,
                            icon: 'fa fa-times'
                        }, {
                            type: 'danger'
                        });
                    }
                },
                error: function(err) {
                    $.notify({
                        title: 'Error',
                        message: 'Terjadi kesalahan',
                        icon: 'fa fa-times'
                    }, {
                        type: 'danger'
                    });
                }
            });
        });

        function handlerExportToExcel() {
            window.location.href = `{{ route('stock-opname-request.exportExcel') }}?${$.param(filter)}`;
        }
    </script>
@endsection

@endsection
