@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2.select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('js/master/expedisi.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select-create').select2({
            tags: true,
            dropdownParent: $('#modal-add'),
        });

        $('#modal-edit').on('hidden.bs.modal', function() {
            let selectUpdate = $('.select-update');
            selectUpdate.empty();
            selectUpdate.find('option').remove();
            selectUpdate.val(null).trigger('change');

        });

        $('.select-update').select2({
            tags: true,
            dropdownParent: $('#modal-edit'),
        });
    </script>
    <script type="text/javascript">
        $('.js-dataTable').dataTable({
            pageLength: 20,
            lengthMenu: [
                [5, 10, 15, 20],
                [5, 10, 15, 20]
            ],
            autoWidth: false,
            ajax: "{{ url('expedisi') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'id',
                    width: '5%'
                },
                {
                    data: 'expedisi',
                    name: 'expedisi',
                },
                {
                    data: ({prefix}) => {
                        const data = JSON.parse(prefix || '[]');
                        return data
                    },
                    name: 'prefix',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: true,
                    width: '5%'
                },
            ],
            dom: "<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>>" +
                "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });

        $("#createForm").on("submit", function(e) {
            e.preventDefault()
            const form = new FormData(this)
            var data = {
                expedisi: form.get('expedisi'),
                color: form.get('color'),
                prefix: form.getAll('prefix')
            }
            $.ajax({
                url: "expedisi",
                method: "POST",
                data: data,
                success: function(response) {
                    if (response.success) {
                        $("#modal-add").modal("hide")
                        $('.js-dataTable').DataTable().ajax.reload();
                        One.helpers('jq-notify', {
                            type: 'success',
                            icon: 'fa fa-check me-1',
                            message: response.message
                        });
                    } else {
                        One.helpers('jq-notify', {
                            type: 'danger',
                            icon: 'fa fa-times me-1',
                            message: response.message
                        });
                    }

                }
            })


        })
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Expedisi
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Daftar Expedisi
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Master</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Expedisi
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
                    LIST DATA EXPEDISI
                </h3>
                <button type="button" class="btn btn-alt-primary  btn-sm" data-bs-toggle="modal"
                    data-bs-target="#modal-add">Tambah Data</button>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable fs-sm">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">#</th>
                                <th>Nama Expedisi</th>
                                <th>Prefix</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
    <!-- END Page Content -->

    <!-- modal delete -->
    <div class="modal" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Hapus Expedisi?</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <h5>Yakin akan menghapus data?</h5>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-danger btn-destroy">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Small Block Modal -->

    <!-- modal-add -->
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Tambah data Expedisi</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <form id="createForm">
                            <div class="form-floating mb-4">
                                <input required="" autocomplete="off" type="text" class="form-control" id="expedisi"
                                    name="expedisi" placeholder="Nama Expedisi">
                                <label for="example-text-input-floating">Nama Expedisi</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input required="" autocomplete="off" type="color" class="form-control" id="color"
                                    name="color" placeholder="Color">
                                <label for="example-text-input-floating">Warna Expedisi</label>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Prefix</label>
                                <select class="form-select select-create" name="prefix" multiple>
                                </select>
                            </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary btn-store">Simpan</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END modal add -->


    <!-- modal-edit -->
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Ubah Data Expedisi</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <span id="form_result"></span>
                        <form id="editFormEx">
                            <div class="form-floating mb-4">
                                <input hidden required="" autocomplete="off" type="text" class="form-control"
                                    id="id" name="id">
                                <input required="" autocomplete="off" type="text" class="form-control"
                                    id="editexpedisi" name="expedisi" placeholder="Nama Expedisi">
                                <label for="example-text-input-floating">Nama Expedisi</label>

                            </div>
                            <div class="form-floating mb-4">
                                <input required="" autocomplete="off" type="color" class="form-control"
                                    id="editcolor" name="color" placeholder="Color">
                                <label for="example-text-input-floating">Warna Expedisi</label>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Prefix</label>
                                <select class="form-select select-update" name="prefix" id="prefix" multiple>
                                </select>
                            </div>
                            <div class="block-content block-content-full text-end bg-body">
                                <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-sm btn-primary btn-update">Update</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END modal edit -->
@endsection
