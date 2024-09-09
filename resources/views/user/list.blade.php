@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css') }}">
@endsection

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>


    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('js/master/user.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script type="text/javascript">
        const baseUrl = "{{ url('/') }}";

        $('.js-dataTable').dataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 15, 20],
                [5, 10, 15, 20]
            ],
            autoWidth: false,
            ajax: "{{ url('user') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id',
                    width: '5%'
                },
                {
                    data: 'name',
                    name: 'name',
                    width: '10%'
                },
                {
                    data: 'email',
                    name: 'email',
                    width: '25%'
                },
                {
                    data: (data) => {
                        let role = data?.role;
                        if(data?.roles?.[0]) {
                            role = data?.roles?.[0]?.name
                        }
                        return `<span >${role}</span>`
                    },
                    name: 'role',
                    width: '15%'
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
            var data = $(this).serialize();
            $.ajax({
                url: "user",
                method: "POST",
                data: $(this).serialize(),
                success: function() {
                    $("#modal-add").modal("hide")
                    $('.js-dataTable').DataTable().ajax.reload();
                    One.helpers('jq-notify', {
                        type: 'success',
                        icon: 'fa fa-check me-1',
                        message: 'Berhasil disimpan!'
                    });
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
                        User
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Data user aplikasi
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Master</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            User
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
                    LIST DATA USER
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
                                <th>Name</th>
                                <th>Email</th>
                                <!-- <th class="d-none d-sm-table-cell" style="width: 30%;">Password</th> -->
                                <th>Role</th>
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

    <!-- modal-add -->
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Tambah data user</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <form id="createForm">
                            <div class="form-floating mb-4">
                                <input type="hidden" name="is_akses" id="is_akses" value="0">
                                <input required="" autocomplete="off" type="text" class="form-control" id="name"
                                    name="name" placeholder="Nama User">
                                <label for="example-text-input-floating">Name</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input required="" autocomplete="off" type="text" class="form-control" id="email"
                                    name="email" placeholder="Email">
                                <label for="example-text-input-floating">Email</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input required="" autocomplete="off" type="password" class="form-control" id="password"
                                    name="password" placeholder="Password">
                                <label for="example-text-input-floating">Password</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input required="" autocomplete="off" type="password" class="form-control"
                                    id="repassword" name="repassword" placeholder="Konfirm Password">
                                <label for="example-text-input-floating">Confirm Password</label>
                            </div>
                            <div class="form-floating mb-4">
                                <select required class="form-select" id="role" name="role">
                                    <option selected="">--</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    @foreach ($role as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input hidden value="1" autocomplete="off" type="text" class="form-control"
                                id="active" name="active">
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
                        <h3 class="block-title">Ubah data USER</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <span id="form_result"></span>
                        <form id="editForm">
                            <div class="form-floating mb-4">
                                <input hidden required="" autocomplete="off" type="text" class="form-control"
                                    id="id" name="id">
                                <input type="hidden" name="edit_is_akses" id="edit_is_akses">
                                <input required="" autocomplete="off" type="text" class="form-control"
                                    id="editname" name="name" placeholder="Nama User">
                                <label for="example-text-input-floating">Name</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input required="" autocomplete="off" type="text" class="form-control"
                                    id="editemail" name="email" placeholder="Email">
                                <label for="example-text-input-floating">Email</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input autocomplete="off" type="password" class="form-control"
                                    id="editpassword" name="password" placeholder="New Password">
                                <label for="example-text-input-floating">Password <small class="text-danger">*Kosongi jika tidak diubah</small></label>
                            </div>
                            <div class="form-floating mb-4">
                                <select required class="form-select" id="editrole" name="role">
                                    <option selected="">--</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    @foreach ($role as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary btn-update">Simpan</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END modal edit -->


    <!-- modal-reset -->
    <div class="modal fade" id="modal-reset" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Reset Password</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <span id="form_result"></span>
                        <form id="resetForm">
                            <div class="form-floating mb-4">
                                <input hidden required="" autocomplete="off" type="text" class="form-control"
                                    id="reset_id" name="reset_id">
                                <input id="reset_password" name="reset_password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="new-password">

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


    <!-- modal delete -->
    <div class="modal" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Hapus User Akun?</h3>
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
@endsection
