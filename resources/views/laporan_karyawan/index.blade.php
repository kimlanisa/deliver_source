@extends('layouts.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Laporan Karyawan
                    </h2>
                    <div class="text-muted mb-0">
                        Daftar Laporan Karyawan
                    </div>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Laporan Karyawan
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
                @if (canPermission('Laporan Karyawan.Create'))
                    <a href="{{ route('laporan-karyawan.create') }}" class="btn btn-sm btn-alt-secondary"><i
                            class="fa fa-plus text-info me-1"></i>Laporan Baru</a>
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
                            </div>
                        </div>
                    </div>
                    @if (canPermission('Laporan Karyawan.Full_Akses') || Auth::user()->role !== 'admin')
                    <div class="d-flex align-items-center justify-content-start mt-3">
                        <div style="width: 15%" class="fw-bold">Dibuat Oleh</div>
                        <div style="width: 85%">
                            <div class="d-flex align-items-center">
                                <div style="width: 60%">
                                    @php
                                        $users = \App\Models\User::all();
                                    @endphp
                                   <select name="created_by_id" id="created_by_id" class="form-control">
                                       <option value="">Semua</option>
                                       @foreach ($users as $user)
                                           <option value="{{ $user->id }}">{{ $user->name }}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="block block-rounded mt-4">
            {{-- <div class="block-header block-header-default d-flex justify-content-between pb-0">
                <ul class="nav nav-tabs tabs-process" style="cursor: pointer">
                    <li class="nav-item">
                        <div data-type="on_process"  onclick="handleFilterStatus('on_process')" class="nav-link active">Perlu Diproses</div>
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
            </div> --}}


            <div class="block-content block-content-full">
                @include('layouts._message')
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter fs-sm" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">#</th>
                                <th style="width: 180px;">No Laporan</th>
                                <th style="width: 200px;">Tanggal</th>
                                <th style="width: 160px">Dibuat Oleh</th>
                                <th style="width: 200px">Waktu Dibuat</th>
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
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="bodyDetailModal">
                <div id="loadingDetail d-none">
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

    <div class="modal" id="modalDetailFile" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <div>
                            <div style="cursor: pointer" data-url="" type="button" class="btn btn-primary btn-sm download-file">
                                Download
                                <i class="fa fa-fw fa-download"></i>
                            </div>
                        </div>
                        <div class="block-options">
                            <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm" id="bodyDetailFile">

                    </div>
                </div>
            </div>
        </div>
    </div>

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

    {{-- <script type="module">
        import {
            SpreadsheetViewer
        } from 'https://tandaterima.com/js/spreadsheet-viewer/client-library/clientLibrary.js';

        SpreadsheetViewer({
                container: document.querySelector('div#spreadsheet-viewer'),
                assetsUrl: 'https://tandaterima.com/jss/spreadsheet-viewer/sv/index.html'
            })
            .then(instance => {
                instance.configure({
                    licenseKey: 'evaluation' // contact us to order a license key for commercial use beyond evaluation
                });
                instance.loadWorkbook('https://tandaterima.com/import_component_by_employee_-_20240802 (1).xlsx', 0);
            })
            .catch(error => {
                console.error(error.message);
            });
    </script> --}}


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
        const base_url = window.location.origin;
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
                url: `{{ route('laporan-karyawan.dataTable') }}`,
                method: "POST",
                data: function(d) {
                    d.filter = filter;
                    return d;
                },
            },
            columns: [
                {
                    name: "created_at",
                    data: "DT_RowIndex",
                },
                {
                    name: "no_laporan",
                    data: 'no_laporan'
                },
                {
                    name: "date",
                    data: (item) => {
                        return `<span style="white-space: nowrap">${moment(item?.created_at).format('DD-MM-YYYY')}</span>`;
                    },
                },
                {
                    name: "created_by_name",
                    data: 'created_by_name'
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
        let files_input = [];
        $(document).on('click', '.btn-delete', function() {
            idDelete = $(this).data('id');
        }).on('click', '.detailData', function() {
            const id = $(this).data('id');
            $('#modalDetailData').modal('show');
            let url = '{{ route('laporan-karyawan.show', ':id') }}';
            $('#loadingDetail').removeClass('d-none');
            $.ajax({
                url: url.replace(':id', id),
                method: 'GET',
                data: {
                    show_detail_modal: true
                },
                success: function(res) {
                    if (res) {
                        res?.data?.laporan_karyawan_detail?.map((item, index) => {
                            files_input[item.id] = JSON.parse(item.images);
                        });
                        $('#bodyDetailModal').html(res?.view);
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
            const actionStatus = $(`.action-status-${id}`);
            actionStatus.removeClass('d-none');
            $(this).parent().addClass('d-none');
        }).on('click', '.btn-edit-cancel', function() {
            const id = $(this).data('id');
            const statusAction = $(`#statusAction${id}`);
            statusAction.removeClass('d-none');
            $(this).parent().addClass('d-none')
        }).on('click', '.btn-edit-save', function() {
            let select = $(this).parent().find(`input[name="no_resi"]`),
                valueInput = select.val(),
                id = $(this).data('id');
            var url = `{{ route('laporan-karyawan.update', ':id') }}`,
                url = url.replace(':id', id);
            if (valueInput == '') {
                $.notify({
                    title: 'Error',
                    message: 'No Resi harus diisi',
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
                        no_resi: valueInput,
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

        handleFilterStatus('on_process');

        $('#rangeTanggal').on('change', function() {
            filter.range_date = $(this).val();
            let filterSplit = filter.range_date.split(' to ');
            if (filterSplit.length > 0)
                table.ajax.reload();
        });

        table.on('click', '.btn-delete', function() {
            idDelete = $(this).data('id');
        });

        table.on('click', '.actionProcess', function() {
            let id = $(this).data('id');
            let url = `{{ route('laporan-karyawan.update', ':id') }}`;
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
            let url = `{{ route('laporan-karyawan.destroy', ':id') }}`;
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
            window.location.href = `{{ route('laporan-karyawan.exportExcel') }}?${$.param(filter)}`;
        }

        $('select[name="created_by_id"]').on('change', function() {
            filter.created_by_id = $(this).val();
            table.ajax.reload();
        });
    </script>
    <script>

        $('#modalDetailData').on('click', '.show-detail', function() {
                const id = $(this).data('id');
                const key = $(this).data('key');
                const file = files_input[id][key];
                console.log(files_input);
                const open = file.url ? `${base_url}/${file?.url}` : URL.createObjectURL(file);
                $('.download-file').data('url', open);
                if(!file.url) {
                    window.open(open, '_blank');
                    return
                }
                let url = ''
                if (file.url?.includes('pdf')) {
                    url = `https://docs.google.com/gview?url=${base_url}/${file.url}&embedded=true`
                }else if (file.url?.includes('doc') || file.url?.includes('docx') || file.url?.includes('ppt') || file.url?.includes('pptx') || file.url?.includes('xls') || file.url?.includes('xlsx')) {
                    url = `https://view.officeapps.live.com/op/embed.aspx?src=${base_url}/${file.url}`
                }  else {
                    url = `${base_url}/${file.url}`
                }

                $('#modalDetailFile').modal('show');
                $('#bodyDetailFile').html(`
                <iframe src="${url}" style="width: 100%; height: 100vh"></iframe>
                `);
                // window.open(open, '_blank');
            }).on('click', '.download-file',function(e) {
                downloadFile(e, this);

            });

            $('#modalDetailFile').on('click', '.download-file',function(e) {
                downloadFile(e, this);
            });

            function downloadFile(e, element) {
                e.preventDefault();
                e.stopPropagation();
                var link = $(element).data('url');
                var element = document.createElement('a');
                element.setAttribute('href', link);
                element.setAttribute('target', '_blank');
                element.setAttribute('download', '');
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            }
    </script>
@endsection

@endsection
