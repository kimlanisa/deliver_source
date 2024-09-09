@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">

    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    {{-- <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css')}}"> --}}

    <style>
        .button {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translateX(-50%);
            width: 74px;
            height: 36px;
            /* margin: -20px auto 0 auto; */
            overflow: hidden;
            background: #ffff;
            border: 1px solid grey;
        }

        .button.r,
        .button.r .layer {
            border-radius: 100px;
        }

        .button.b2 {
            border-radius: 2px;
        }

        .checkbox {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }

        .knobs {
            z-index: 2;
        }

        .layer {
            width: 100%;
            background-color: #ebf7fc;
            transition: 0.3s ease all;
            z-index: 1;
        }

        /* Button 1 */
        #button-1 .knobs:before {
            content: "OFF";
            position: absolute;
            top: 4px;
            left: 4px;
            width: 28px;
            height: 28px;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            line-height: 1;
            padding: 9px 4px;
            background-color: #f44336;
            border-radius: 50%;
            transition: 0.3s cubic-bezier(0.18, 0.89, 0.35, 1.15) all;
        }

        #button-1 .checkbox:checked+.knobs:before {
            content: "ON";
            left: 42px;
            background-color: #03a9f4;
        }

        #button-1 .checkbox:checked~.layer {
            background-color: #fcebeb;
        }

        #button-1 .knobs,
        #button-1 .knobs:before,
        #button-1 .layer {
            transition: 0.3s ease all;
        }
    </style>
@endsection

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

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
        $(document).ready(function() {
            initDataUser();
        });

        const initMessage = (type, icon, message) => {
            One.helpers('jq-notify', {
                type: type,
                icon: `${icon} me-1`,
                message: message
            });
        }

        const initDataUser = () => {
            if ($.fn.DataTable.isDataTable('.js-dataTable')) {
                $('.js-dataTable').DataTable().destroy();
            }
            $('.js-dataTable > tbody').empty();

            $('.js-dataTable').dataTable({
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                autoWidth: false,
                ajax: "{{ url('setting-akses') }}",
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
                        width: '20%'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        width: '15%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true,
                        width: '10%'
                    },
                    {
                        data: 'last_updated_akses',
                        name: 'last_update_akses',
                        width: '10%'
                    },
                ],
                dom: "<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>>" +
                    "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
            });
        }

        const handlerSettingAksesUser = (event, idUser) => {
            if (event.currentTarget.checked) {
                requestUpdateAksesUser('true', idUser)
            } else {
                requestUpdateAksesUser('false', idUser)
            }
        }

        const requestUpdateAksesUser = (type, idUser) => {
            $.ajax({
                type: 'POST',
                url: "{{ route('updateakses') }}",
                data: {
                    type,
                    idUser
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        initMessage('success', 'fa fa-check', response.message)
                        initDataUser();
                    } else {
                        initMessage('danger', 'fa fa-times', response.message)
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
                        Setting Akses User
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        List User
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Setting</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Setting Akses User
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
                    List Users
                </h3>
                <div>Tanggal {{ date('d F Y', strtotime(date('Y-m-d'))) }}</div>
            </div>

            <div class="block-content block-content-full">
                @include('layouts._message')

                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable fs-sm">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Setting Akses</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
@endsection
