@extends('layouts.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Returan Paket
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Daftar Returan Paket
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Returan Paket
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
                @if (canPermission('Daftar Returan.Create'))
                <a href="{{ route('retur.create') }}" class="btn btn-sm btn-alt-secondary"><i
                        class="fa fa-plus text-info me-1"></i>Returan Baru</a>
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
                        <div style="width: 15%" class="fw-bold">Nama Toko</div>
                        <div style="width: 85%">
                            <div class="d-flex align-items-center">
                                <div style="width: 60%">
                                    <select class="form-select" name="shop_id">
                                        <option value="">Pilih Nama Toko</option>
                                        @php
                                            $shops = \App\Models\Shop::orderBy('name', 'asc')->get();
                                        @endphp
                                        @foreach ($shops as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    DAFTAR RETUR
                </h3>
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
                                <th>Tanggal Input</th>
                                <th>Nama Toko</th>
                                <th>No Pesanan</th>
                                <th>No Komplain</th>
                                <th>Customer_Id</th>
                                <th>No Whatsapp Customer</th>
                                <th>No Resi</th>
                                <th>SKU</th>
                                <th style="width: 40%" class="sticky-table">Kondisi</th>
                                <th style="width: 40%" class="sticky-table">Remark</th>
                                <th>Dibuat Oleh</th>
                                <th>Waktu Dibuat</th>
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

    <div class="modal" id="modalDetailData" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="bodyDetailModal">
                <div
                id="loadingDetail d-none"
                >
                    <div class="parent-loading">
                        <div class="loading-custom">
                            <div class="spinner-border text-info" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                url: `{{ route('retur.dataTable') }}`,
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
                    data: "no_trx",
                },
                {
                    name: "date",
                    data: (item) => {
                        return `<span class="" style="white-space: nowrap">${moment(item.date).format('DD-MM-YYYY')}</span>`
                    },
                },
                {
                    name: "shop.name",
                    data: ({
                        shop
                    }) => {
                        return `<span class="badge py-1 px-2 text-white" style="background: ${shop.color}; font-size: 13px">${shop.name}</span>`
                    }
                },
                {
                    name: "no_pesanan",
                    data: "no_pesanan",
                },
                {
                    name: "no_complaint",
                    data: "no_complaint",
                },
                {
                    name: "customer",
                    data: "customer",
                },
                {
                    name: "no_whatsapp",
                    data: (data) => {
                        return `
                        <div class="d-flex align-items-center" id="whatsappAction${data?.id}">
                            <div>${data?.no_whatsapp ?? ''}</div>
                            <button data-id="${data?.id}" type="button" class="ms-2 btn btn-edit-whatsapp">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center d-none action-whatsapp-${data?.id}">
                            <input class="form-control" name="no_whatsapp" style="min-width: 200px" value="${data?.no_whatsapp}"/>
                            <button type="button" class="ms-2 btn btn-edit-save-wa p-1" data-id=${data.id}>
                                <i class="fas fa-save"></i>
                            </button>
                            <button type="button" class="ms-2 btn btn-edit-cancel p-1" data-id="${data.id}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        `;
                    },
                },
                {
                    name: "no_resi",
                    data: "no_resi",
                },
                {
                    name: "sku_jumlah",
                    data: (data) => {
                        let element = [];
                        data?.sku_jumlah?.map((item) => {
                            element.push(`<div class="text-nowrap">${item?.sku} (${item?.jumlah})</div>`);
                        }) ?? '';
                        return element;
                    },
                },
                {
                    name: "status",
                    data: (data) => {
                        return `
                        <div class="d-flex align-items-center" id="statusAction${data?.id}">
                            <div>${data?.status ?? ''}</div>
                            <button data-id="${data?.id}" type="button" class="ms-2 btn btn-edit-status">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center d-none action-status-${data?.id}">
                            <select class="form-control" name="status" style="min-width: 200px">
                                <option value="">Pilih Status</option>
                                <option value="RIJECT" ${data?.status == 'RIJECT' && 'selected'}>RIJECT</option>
                                <option value="NORMAL" ${data?.status == 'NORMAL' && 'selected'}>NORMAL</option>
                            </select>
                            <button type="button" class="ms-2 btn btn-edit-save p-1" data-id=${data.id}>
                                <i class="fas fa-save"></i>
                            </button>
                            <button type="button" class="ms-2 btn btn-edit-cancel p-1" data-id="${data.id}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        `;
                    },
                    // className: "sticky-table",

                },
                {
                    name: "status2",
                    data: (data) => {
                        return `
                        <div class="d-flex align-items-center" id="status2Action${data?.id}">
                            <div class="badge bg-black">${data?.status2 ?? ''}</div>
                            <button data-id="${data?.id}" type="button" class="ms-2 btn btn-edit-status2">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center d-none action-status2-${data?.id}">
                            <select class="form-control" name="status2" style="min-width: 200px">
                                <option value="">Pilih Status</option>
                                <option value="DONE BIGSELLER" ${data?.status2 == 'DONE BIGSELLER' && 'selected'}>DONE BIGSELLER</option>
                                <option value="MANUAL ACC" ${data?.status2 == 'MANUAL ACC' && 'selected'}>MANUAL ACC</option>
                                <option value="NOTHING" ${data?.status2 == 'NOTHING' && 'selected'}>NOTHING</option>
                            </select>
                            <button type="button" class="ms-2 btn btn-edit-save p-1" data-id=${data.id}>
                                <i class="fas fa-save"></i>
                            </button>
                            <button type="button" class="ms-2 btn btn-edit-cancel p-1" data-id="${data.id}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        `;
                    },
                    // className: "sticky-table",

                },
                {
                    name: "created_by.name",
                    data: (item) => {
                        return item?.created_by?.name ?? '';
                    },
                },
                {
                    name: "created_at",
                    data: (item) => {
                        return `<span style="white-space: nowrap">${moment(item?.created_at).format('DD-MM-YYYY HH:mm:ss')}</span>`;
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


        $(document).on('click', '.btn-delete', function() {
            idDelete = $(this).data('id');
        }).on('click', '.detailData',function() {
            const id = $(this).data('id');
           $('#modalDetailData').modal('show');
           let url = '{{ route("retur.show", ":id") }}';
           $('#loadingDetail').removeClass('d-none');
           $.ajax({
                url: url.replace(':id', id),
                method: 'GET',
                success: function(res) {
                    if (res) {
                        $('#bodyDetailModal').html(res);
                        $('#loadingDetail').addClass('d-none');
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
        }).on('click', '.btn-edit-status', function() {
            const id = $(this).data('id');
            const actionStatus  = $(`.action-status-${id}`);
            actionStatus.removeClass('d-none');
            $(this).parent().addClass('d-none');
        }).on('click', '.btn-edit-cancel', function() {
            const id = $(this).data('id');
           const statusAction = $(`#statusAction${id}`);
           statusAction.removeClass('d-none');
           $(this).parent().addClass('d-none')
        }).on('click', '.btn-edit-save', function() {
            let select = $(this).parent().find(`select[name="status"]`),
                select2 = $(this).parent().find(`select[name="status2"]`),
                valueSelect = select.val(),
                valueSelect2 = select2.val(),
                id = $(this).data('id');
            var url = `{{ route('retur.update', ':id') }}`,
                url = url.replace(':id', id);
            if(valueSelect == '') {
                $.notify({
                    title: 'Error',
                    message: 'Status harus diisi',
                    icon: 'fa fa-times'
                }, {
                    type: 'danger'
                });
                return
            } else {
                $.ajax({
                url: url,
                method: 'POST',
                data: {
                    status: valueSelect,
                    status2: valueSelect2,
                    id: id,
                    _method: 'PUT'
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
            }
        }).on('click', '.btn-edit-whatsapp', function() {
            const id = $(this).data('id');
            const actionStatus  = $(`.action-whatsapp-${id}`);
            actionStatus.removeClass('d-none');
            $(this).parent().addClass('d-none');
        }).on('click', '.btn-edit-cancel', function() {
            const id = $(this).data('id');
            const whatsappAction = $(`#whatsappAction${id}`);
            whatsappAction.removeClass('d-none');
            $(this).parent().addClass('d-none')
        }).on('click', '.btn-edit-save-wa', function() {
            let select = $(this).parent().find(`input[name="no_whatsapp"]`),
                valueSelect = select.val(),
                id = $(this).data('id');
            var url = `{{ route('retur.update', ':id') }}`,
                url = url.replace(':id', id);
            if(valueSelect == '') {
                $.notify({
                    title: 'Error',
                    message: 'No Whatsapp harus diisi',
                    icon: 'fa fa-times'
                }, {
                    type: 'danger'
                });
                return
            } else {
                $.ajax({
                url: url,
                method: 'POST',
                data: {
                    no_whatsapp: valueSelect,
                    id: id,
                    _method: 'PUT'
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
            }
        }).on('click', '.btn-edit-status2', function() {
            const id = $(this).data('id');
            const actionStatus  = $(`.action-status2-${id}`);
            actionStatus.removeClass('d-none');
            $(this).parent().addClass('d-none');
        }).on('click', '.detailDataComplaint', function() {
            const id = $(this).data('id');
           $('#modalDetailData').modal('show');
           let url = '{{ route("manual-complaint.show", ":id") }}';
           $('#loadingDetail').removeClass('d-none');
           $.ajax({
                url: url.replace(':id', id),
                method: 'GET',
                success: function(res) {
                    if (res) {
                        $('#bodyDetailModal').html(res);
                        $('#loadingDetail').addClass('d-none');
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
        })


        var debounceTimer;

        function handlerSearchDataByKeyword(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filter.keyword = e.target.value;
                table.ajax.reload();
            }, 500);
        }

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
            if (filterSplit.length > 1)
                table.ajax.reload();
        });

        $('#submitHandle').on('submit', function(e) {
            e.preventDefault();
            let url = `{{ route('retur.destroy', ':id') }}`;
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

        $('select[name="shop_id"]').on('change', function() {
            filter.shop_id = $(this).val();
            table.ajax.reload();
        });

        function handlerExportToExcel() {
            window.location.href = `{{ route('retur.exportExcel') }}?${$.param(filter)}`;
        }
    </script>
@endsection

@endsection
