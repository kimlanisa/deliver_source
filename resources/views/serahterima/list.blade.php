@extends('layouts.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Serah Terima
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Daftar Serah Terima
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Serah Terima
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
                @if (canPermission('Daftar Serah Terima.Create'))
                    <a href="{{ route('serahterima.create') }}" class="btn btn-sm btn-alt-secondary"><i
                            class="fa fa-plus text-info me-1"></i>Serah Terima Baru</a>
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
                                            'type' => 'lastWeek',
                                            'name' => '7 Hari',
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
                                    <span class="db-fltr fs-sm me-4 {{ $item['type'] == 'now' ? 'active-fl' : '' }}"
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
                                <div style="width: 20%">
                                    <select name="searchBy" id="searchBy" class="form-control searchData"
                                        style="border-right: 0;border-top-right-radius:0;border-bottom-right-radius:0;cursor:pointer">
                                        <option value="1">No Tanda Terima</option>
                                        <option value="2">Expedisi</option>
                                        <option value="3">No Resi</option>
                                    </select>
                                </div>
                                <div style="width: 30%">
                                    <input type="text" name="valueSearch" id="valueSearch"
                                        class="form-control searchData w-100"
                                        style="border-left: 0;border-top-left-radius:0;border-bottom-left-radius:0;"
                                        placeholder="Search keywoard data..."
                                        onkeydown="handlerSearchDataByKeyword(event)">
                                </div>
                                <div style="width: 20%">
                                    <button type="submit" data-toggle="layout-cari"
                                        onclick="handlerSearchDataByKeyword(event)">
                                        <i class="fa fa-fw fa-search"></i></button>
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
                <div class="d-flex align-items-center" style="gap: 10px">
                    <h3 class="block-title">
                        DAFTAR SERAH TERIMA
                    </h3>
                    <div style="margin-left 50px">
                        <span style="font-weight: 500">Total Paket :</span>
                        <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info fs-sm"
                            id="totalPaket">0</span>
                    </div>
                </div>
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
                                <th style="width: 200px;">No Tanda Terima</th>
                                <th>Jumlah Paket</th>
                                <th>Nama Expedisi</th>
                                <th>Created By</th>
                                <th>Waktu Dibuat</th>
                                <th>Catatan</th>
                                <th style="width: 15%;">Action</th>
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
      <!--modal update catatan-->
      <div class="modal fade" id="modal-updateCatatan" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
          <div class="modal-content">
              <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                      <h3 class="block-title">Update Catatan</h3>
                      <div class="block-options">
                          <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                              <i class="fa fa-fw fa-times"></i>
                          </button>
                      </div>
                  </div>
                  <div class="block-content fs-sm">
                      <span id="form_result"></span>
                      <div class="form-floating mb-4">
                          <input hidden required="" autocomplete="off" type="text" class="form-control"
                              id="update_id" name="update_id">
                          <textarea class="form-control" id="catatanEdit" name="catatanEdit" rows="5" cols="3"></textarea>
                          <label for="example-text-input-floating">Catatan</label>
                      </div>
                  </div>
                  <div class="block-content block-content-full text-end bg-body">
                      <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                          data-bs-dismiss="modal">Batal</button>
                      <button type="button" class="btn btn-sm btn-primary"
                          onclick="handlerRequestUpdateCatatan(event)">Update</button>
                  </div>

              </div>
          </div>
      </div>
  </div>

  <!--modal edit detail-->
  <div class="modal fade" id="modal-editDetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="container">
                      <div class="table-responsive">
                          <table class="table table-striped" width="100%" id="initDetailSerahTterima">
                              <thead>
                                  <tr class="bg-primary text-white text-center">
                                      <td width="10%">
                                          <input type="checkbox" id="check-all-pilih" width="20"
                                              style="transform: scale(1.5)" onchange="checkAll(this)" />
                                      </td>
                                      <td width="20%"><strong>No Resi</strong></td>
                                      <td width="10%"><strong>Nama Expedisi</strong></td>
                                      <td width="10%"><strong>Waktu Scan</strong></td>
                                  </tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary btn-update-detail"
                      onclick="handlerRequestUpdateDataDetail(event)">Update</button>
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
                url: `{{ route('getDataSerahTerima') }}`,
                method: "POST",
                data: function(d) {
                    d.filter = filter;
                    d.keyword = $('#valueSearch').val();
                    d.searchBy = $('#searchBy').val();
                    return d;
                },
            },
            columns: [{
                    name: "created_at",
                    data: "DT_RowIndex",
                },
                {
                    name: "no_tanda_terima",
                    data: 'no_tanda_terima',
                },
                {
                    name: "no_resi_count",
                    data: 'no_resi_count',
                },
                {
                    name: "expedisi.expedisi",
                    data: (
                        item
                    ) => {
                        return `<div class="text-center">
                            <span class="badge py-1 px-2 text-white" style="background: ${item?.expedisi?.color}; font-size: 13px">${item?.expedisi?.expedisi}</span>
                            </div>`
                    }
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
                    name: "catatan",
                    data: 'catatan',
                },
                {
                    name: "action",
                    data: 'action',
                    orderable: false,
                },

            ],
        });


        function getTotalPaket() {
            $('#totalPaket').html(` <div class="d-flex align-items-center justify-content-center">
                                        <div class="spinner-border spinner-border-sm me-3" role="status" aria-hidden="true"></div>
                                        <strong>Loading...</strong>
                                    </div>`);
            $.ajax({
                url: `{{ route('serahterima.getTotalPaket') }}?${$.param({
                    filter: filter,
                    keyword: $('#valueSearch').val(),
                    searchBy: $('#searchBy').val()
                })}`,
                method: 'GET',
                success: function(res) {
                    $('#totalPaket').html(res);
                }
            });
        }

        handlerFilter('now');

        $(document).on('click', '.btn-delete', function() {
            idDelete = $(this).data('id');
        })


        var debounceTimer;

        function handlerSearchDataByKeyword(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filter.keyword = e.target.value;
                table.ajax.reload();
                getTotalPaket();
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
            if (type !== 'range') {
                table.ajax.reload();
                getTotalPaket();
            }
        }

        $('#rangeTanggal').on('change', function() {
            filter.range_date = $(this).val();
            let filterSplit = filter.range_date.split(' to ');
            if (filterSplit.length > 1) {
                table.ajax.reload();
                getTotalPaket();
            }
        });

        $('#submitHandle').on('submit', function(e) {
            e.preventDefault();
            let url =  `{{ url('/serahterima/${idDelete}') }}`;
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
                        getTotalPaket();
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
            getTotalPaket();
        });

        function handlerExportToExcel() {
           window.open(`{{ route('serahterima.exportExcel') }}?${$.param(filter)}`, '_blank');
        }

        const handlerUpdateCatatan = (id, catatan) => {
            $("#modal-updateCatatan").modal('show');
            $("#update_id").val(id);
            $("#catatanEdit").html(catatan == 'null' ? '' : catatan);
        }

        const handlerRequestUpdateCatatan = (event) => {
            const id = $("#update_id").val();
            const catatan = $("#catatanEdit").val();

            $.ajax({
                url: "{{ route('updateCatatan') }}",
                method: "POST",
                data: {
                    id,
                    catatan
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        One.helpers('jq-notify', {
                            type: 'success',
                            icon: 'fa fa-check me-1',
                            message: 'Success update!'
                        });
                        window.location.reload();
                    } else {
                        One.helpers('jq-notify', {
                            type: 'danger',
                            icon: 'fa fa-check me-1',
                            message: 'Gagal update!'
                        });
                    }
                },
            });
        }
    </script>
@endsection

@endsection
