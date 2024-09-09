@extends('layouts.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Permintaan Refund
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Daftar Permintaan Refund
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Permintaan Refund
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
                @if (canPermission('Daftar Request Refund.Create'))
                <a href="{{ route('request-refund.create') }}" class="btn btn-sm btn-alt-secondary"><i
                        class="fa fa-plus text-info me-1"></i>Permintaan Refund</a>
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
            <div class="block-header block-header-default d-flex justify-content-between pb-0">
                <ul class="nav nav-tabs tabs-process" style="cursor: pointer">
                    <li class="nav-item">
                        <div data-type="on_process" onclick="handleFilterStatus('on_process')" class="nav-link">Perlu
                            Dibayar</div>
                    </li>
                    <li class="nav-item">
                        <div data-type="tandai_selesai" onclick="handleFilterStatus('tandai_selesai')" class="nav-link">
                            Telah Dibayar
                        </div>
                    </li>
                    <li class="nav-item">
                        <div data-type="done_process" onclick="handleFilterStatus('done_process')" class="nav-link">Telah
                            Diproses</div>
                    </li>
                    <li class="nav-item">
                        <div data-type="all" onclick="handleFilterStatus('all')" class="nav-link active">Semua</div>
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
                                <th>Toko</th>
                                <th>No Pesanan</th>
                                <th>Customer_Id</th>
                                <th>Lampiran Refund</th>
                                <th>Nominal Refund</th>
                                <th>Alasan Refund</th>
                                <th>Dibuat Oleh</th>
                                <th>Waktu Dibuat</th>
                                <th class="sticky-table">Status</th>
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

    <div class="modal" id="modalAccRefund" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Acc Refund No TRX <span class="no_trx"></span></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form class="submitRefund">
                        @csrf
                        @method('PUT')
                        <div class="p-4 ">
                            <div class="form-group">
                                <label class="form-label">Tanggal Acc Refund</label>
                                <input class="form-control" type="date" name="date_acc_refund" />
                            </div>
                            <div class="mt-4 form-group">
                                <label class="form-label">Bukti Refund</label>
                                <input class="form-control" type="file" name="bukti_refund" />
                                <div class="img-preview"></div>
                            </div>
                        </div>
                        <div class=" text-end bg-body p-3">
                            <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                                data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-sm btn-danger">Simpan</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="modalUploadLampiran" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <form class="submitRefund">
                        @csrf
                        @method('PUT')
                        <div class="p-4 ">
                            <div class="mt-4 form-group">
                                <label class="form-label">Lampiran Refund</label>
                                <input class="form-control" type="file" name="lampiran_refund" />
                                <div class="img-preview"></div>
                            </div>
                        </div>
                        <div class=" text-end bg-body p-3">
                            <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                                data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-sm btn-danger">Simpan</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="detailShowImage" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Bukti Slip Refund <span class="no_trx"></span></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <img src="" alt="" class="img-fluid imgPreviewSlip" style="width: 100%">
                </div>
            </div>
            </form>
        </div>
    </div>
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
            position: sticky !important;
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
        let dataModalAccRefund = null;
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
                url: `{{ route('request-refund.dataTable') }}`,
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
                    data: 'no_trx'
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
                        return `<span class="badge py-1 px-2 text-white" style="background: ${shop?.color}; font-size: 13px">${shop?.name}</span>`
                    }
                },
                {
                    name: "no_pesanan",
                    data: "no_pesanan",
                },
                {
                    name: "customer",
                    data: "customer",
                },
                {
                    name: "lampiran_refund",
                    data: (data) =>  {
                        if(data?.lampiran_refund) {
                            return `<img src="${data?.lampiran_refund}" style="height: 80px; cursor: pointer" class="detailImage"/>`;
                        } else {
                            return `<button class="btn btn-primary showUploadLampiran" data-id="${data?.id}" style="font-size: 12px; white-space: nowrap">
                                <i class="fas fa-upload"></i>
                                Upload lampiran refund
                                </button>`
                        }
                    },
                },
                {
                    name: "nominal_refund",
                    data: "nominal_refund",
                },
                {
                    name: "alasan_refund",
                    data: "alasan_refund",
                },
                {
                    name: "created_at",
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
                    className: "sticky-table",
                    data: (item) => {
                        return `<span class="text-${item?.status == 1 ? 'danger' : 'success'}" style="font-weight: 700; white-space: nowrap">${item?.status == 1 ? 'Belum Dibayar' : 'Sudah Dibayar'}</span>`;
                    },
                },
                {
                    name: "action",
                    data: 'action',
                    className: "sticky-table",
                    orderable: false,
                },

            ],
        });
        $(document).on('click', '.btn-delete', function() {
            idDelete = $(this).data('id');
        }).on('click', '.detailData',function() {
            const id = $(this).data('id');
           $('#modalDetailData').modal('show');
           let url = '{{ route("request-refund.show", ":id") }}';
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
        }).on('click', '.actionDone', function() {
            let id = $(this).data('id');
            let url = `{{ route('request-refund.update', ':id') }}`;
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    status: 3
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
        }).on('click', '.showUploadLampiran', function() {
            let url = `{{ route('request-refund.update', ':id') }}`;
            url = url.replace(':id', $(this).data('id'));
            $('.submitRefund').attr('action', url);
            $('#modalUploadLampiran').modal('show')
        }).on('click','.detailImage', function() {
            $('#modalDetailData').modal('show')
            $('#bodyDetailModal').html(`<img src="${$(this).attr('src')}" style="width: 100%">`);
        })
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

        handleFilterStatus('on_process')

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

        $('.btnDeleteData').on('click', function() {
            idDelete = $(this).data('id');
        });

        table.on('click', '.actionBuy', function() {
            let url = `{{ route('request-refund.update', ':id') }}`;
            url = url.replace(':id', $(this).data('id'));
            $('.submitRefund').attr('action', url);
            $('.no_trx').html($(this).data('no_trx'));
            $('#modalAccRefund').modal('show');
        });

        $('#submitHandle').on('submit', function(e) {
            e.preventDefault();
            let url = `{{ route('request-refund.destroy', ':id') }}`;
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

        $('input[name="lampiran_refund"]').on("change", function() {
            const sampul = $(this);
            const fileSampul = new FileReader();
            fileSampul.readAsDataURL(sampul[0].files[0]);

            fileSampul.onload = function(e) {
                $(".img-preview").html(
                    `<div class="mt-4">
                        <div class="btn btn-sm btn-danger float-end trashImage">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <img src="${e.target.result}" class="img-fluid" style="width: 100%; height: 300px; object-fit: cover; object-position: center">
                    </div>`
                );
            };
        })

        $('input[name="bukti_refund"]').on("change", function() {
            const sampul = $(this);
            const fileSampul = new FileReader();
            fileSampul.readAsDataURL(sampul[0].files[0]);

            fileSampul.onload = function(e) {
                $(".img-preview").html(
                    `<div class="mt-4">
                        <div class="btn btn-sm btn-danger float-end trashImage">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <img src="${e.target.result}" class="img-fluid" style="width: 100%; height: 300px; object-fit: cover; object-position: center">
                    </div>`
                );
            };
        })

        $('.img-preview').on('click', '.trashImage', function() {
            $('input[name="bukti_refund"]').val('');
            $('.img-preview').html('');
        });

        $('.submitRefund').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = $(this).attr('action');
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status) {
                        table.ajax.reload();
                        $('#modalAccRefund').modal('hide');
                        $('#modalUploadLampiran').modal('hide');
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

        table.on('click', '.refundDetail',function() {
            let image = $(this).data('img');
            let no_trx = $(this).data('no_trx');

            $('.imgPreviewSlip').attr('src', image);
            $('.no_trx').html(no_trx);
            $('#detailShowImage').modal('show');
        });

        function handlerExportToExcel() {
            window.location.href = `{{ route('request-refund.exportExcel') }}?${$.param(filter)}`;
        }

        $('select[name="shop_id"]').on('change', function() {
            filter.shop_id = $(this).val();
            table.ajax.reload();
        });
    </script>
@endsection

@endsection
