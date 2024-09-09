@extends('layouts.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        {{ $data->name }}
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Daftar {{ $data->name }}
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            {{ $data->name }}
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
                @if (canPermission($data->permission_name . '.Create'))
                <a href="{{ route('custom-menu.menu.create', $data->slug) }}" class="btn btn-sm btn-alt-primary"><i
                        class="fa fa-plus text-info me-1"></i>{{ $data->name }}</a>
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
                                        placeholder="Search keyword data..." onkeydown="handlerSearchDataByKeyword(event)">
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $label_show = $data->label_show;
                        $label_show = array_filter($label_show, function ($item) {
                            return ($item['type_input'] ?? false) == 'select' && ($item['filter'] ?? false) == true;
                        });
                    @endphp
                    @foreach ($label_show as $flt_label)
                    <div class="d-flex align-items-center justify-content-start mt-3">
                        <div style="width: 15%" class="fw-bold">{{ $flt_label['label'] ?? '' }}</div>
                        <div style="width: 85%">
                            <div class="d-flex align-items-center">
                                <div style="width: 60%">
                                    @php
                                        $select_option = $flt_label['select_option'] ?? [];
                                    @endphp
                                   <select name="{{ $flt_label['name'] }}" id="created_by_id" class="form-control filter_select">
                                       <option value="">Semua</option>
                                       @foreach ($select_option as $option)
                                           <option value="{{ $option['label'] }}">{{ $option['label'] }}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    List Data {{ $data->name }}
                </h3>
                <div class="mb-3 d-flex">
                    <button type="button" onclick="handlerExportToExcel(event)" class="btn btn-alt-success btn-md">
                        <i class="fas fa-file-excel"></i>
                        Excel
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                @include('layouts._message')
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter fs-sm" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">#</th>
                                @foreach ($data->label_show ?? [] as $item)
                                    <th style="min-width: 150px">{{ $item['label'] }}</th>
                                @endforeach
                                <th style="width: 15%;" class="sticky-table">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
        let list_table = @json($data->label_show);

        list_table = list_table.map((item) => {
            if (item.label === 'Created At')
                item.name = 'created_at';
            else if (item.label === 'Created By') {
                item.data = 'created_by_name'
                item.name = 'users.name'
            }else if (item.label === 'Updated At') {
                item.name = 'updated_at'
            } else if (item.label === 'Updated By') {
                item.data = 'updated_by_name'
                item.name = 'updated_by.name'
            }

            return {
                name: item.name,
                data: (data) => {
                    if ((data[item.name] === null || data[item.name] === undefined ) && (data[item.data] === null || data[item.data] === undefined))
                        return ''

                    data[item.name] = replaceWord(data[item.name] || data[item.data]);

                    if (item.type_input === 'select') {
                        const find_select = item?.select_option.find((opt) => opt.label == data[item.name]) || {};
                        if (item.editable) {
                            return `<div class="d-flex align-items-center" id="select_option_${data?.id}_${item.name}">
                                        <div>
                                            <span class="badge py-1 px-2 text-white" style="background-color: ${find_select.color}; font-size: 13px">${data[item.name]}</span>
                                        </div>
                                        <button data-id="${data?.id}" data-name="${item.name}" type="button" class="ms-2 btn btn_edit_select_option">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex align-items-center d-none action-status-${data?.id}-${item.name}">
                                        <select class="form-control" name="status" style="min-width: 200px">
                                            <option value="">Pilih ${item.label}</option>
                                            ${item.select_option.map((opt) => `<option value="${opt.label}" ${opt.label == data[item.name] ? 'selected' : ''}>${opt.label}</option>`).join('')}
                                        </select>
                                        <button type="button" class="ms-2 btn btn_edit_save_select_option p-1" data-id="${data.id}" data-name="${item.name}">
                                            <i class="fas fa-save"></i>
                                        </button>
                                        <button type="button" class="ms-2 btn btn_edit_cancel_select_option p-1" data-id="${data.id}" data-name="${item.name}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>`
                        }

                        return `<span class="badge py-1 px-2 text-white" style="background-color: ${find_select.color}; font-size: 13px">${data[item.name]}</span>`
                    }
                    return data[item.name]
                }
            }
        });


        function replaceWord(str) {
            const urlRegex = /https?:\/\/(?:www\.)?[^\s/$.?#].[^\s]*/g;
            return str.replace(urlRegex, function(url) {
                return `<a href="${url}" target="_blank">${url}</a>`;
            });
        }

        $(document).on('click', '.btn_edit_select_option', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const actionStatus  = $(`.action-status-${id}-${name}`);
            actionStatus.removeClass('d-none');
            $(this).parent().addClass('d-none');
        }).on('click', '.btn_edit_cancel_select_option', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const select_option = $(this).closest('td').find(`#select_option_${id}_${name}`);

            select_option.removeClass('d-none' );
            $(this).parent().addClass('d-none');
        }).on('click', '.btn_edit_save_select_option', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const select_option = $(this).closest('td').find(`#select_option_${id}_${name}`);
            const value = $(this).closest('td').find(`select[name="status"]`).val();
            let url = `{{ route('custom-menu.menu.updateEditable', [':slug', ':id']) }}`;
            url = url.replace(':slug', '{{ $data->slug }}').replace(':id', id);

            if(value == '') {
                $.notify({
                    title: 'Error',
                    message: 'Isian harus diisi',
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
                    value: value,
                    name: name,
                    id: id,
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
        }).on('click', '.btn-view', function() {
            const id = $(this).data('id');
           $('#modalDetailData').modal('show');
           let url = '{{ route("custom-menu.menu.show-menu", [$data->slug, ":id"]) }}';
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

        const table = $("#dataTable").DataTable({
            lengthMenu: [
                [10, 25, 50, 100, 500, -1],
                [10, 25, 50, 100, 500, "All"],
            ],
            searching: true,
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
                url: `{{ route('custom-menu.menu.dataTable', $data->slug) }}`,
                method: "POST",
                data: function(d) {
                    d.filter = filter;
                    d.keyword = $('#valueSearch').val();
                    return d;
                },
            },
            columns: [{
                    name: "created_at",
                    data: "DT_RowIndex",
                },
                ...list_table,
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
        })
        var debounceTimer;

        function handlerSearchDataByKeyword(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {

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
            if (filterSplit.length > 0)
                table.ajax.reload();
        });

        $('.btnDeleteData').on('click', function() {
            idDelete = $(this).data('id');
        });


        $('#submitHandle').on('submit', function(e) {
            e.preventDefault();
            let url = `{{ route('custom-menu.menu.destroy', [':slug', ':id']) }}`;
            url = url.replace(':id', idDelete);
            url = url.replace(':slug', '{{ $data->slug }}');
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

        table.on('click', '.refundDetail', function() {
            let image = $(this).data('img');
            let no_trx = $(this).data('no_trx');

            $('.imgPreviewSlip').attr('src', image);
            $('.no_trx').html(no_trx);
            $('#detailShowImage').modal('show');
        });

        function handlerExportToExcel() {
            window.location.href = `{{ route('custom-menu.menu.exportExcel', $data->slug) }}?${$.param(filter)}`;
        }

        $('.filter_select').on('change', function() {
            const name = $(this).attr('name');
            const value = $(this).val();
            if (value == '') {
                delete filter[name];
            } else {
                filter[name] = value;
            }
            table.ajax.reload();
        });
    </script>
@endsection

@endsection
