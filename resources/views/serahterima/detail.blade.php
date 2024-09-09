@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    {{-- <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css')}}"> --}}
@endsection

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>


    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var SITEURL = {!! json_encode(url('/')) !!};
    </script>

    <script>
        //DELETE
        $('body').on("click", ".btn-delete", function() {
            var id = $(this).attr("id");
            $(".btn-destroy-resi").attr("id", id);

            $("#modal-delete").modal("show");
        });

        $(".btn-destroy-resi").on("click", function() {
            var id = $(this).attr("id");
            $.ajax({
                url: `{{ url('/destroyResi/${id}') }}`,
                method: 'DELETE',
                success: function(response) {
                    $("#modal-delete").modal("hide")
                    One.helpers('jq-notify', {
                        type: 'danger',
                        icon: 'fa fa-check me-1',
                        message: 'Success Delete!'
                    });
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    alert('Error!!');
                },
            });
        })
        //END DELETE
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <span id="form_result"></span>
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Detail Serah Terima
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        No Tanda Terima : <span
                            class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info fs-sm">{{ $serahterima->no_tanda_terima }}</span>
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Serah Terima </a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Detail
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    JUMLAH PAKET : <span
                        class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info fs-sm">{{ $count }}</span>
                </h3>
                {{-- <a  class="btn btn-alt-primary me-1 btn-sm" href="{{route('printTandaTerima',$serahterima->id)}}" target="_blank"><i class="fa fa-print text-info me-1"></i>Tanda Terima</a> --}}
                <a href="javascript:history.back()" class="btn btn-alt-primary btn-sm">
                    <i class="fa fa-arrow-alt-circle-left text-info me-1"></i>Kembali
                </a>
            </div>

            <div class="block-content block-content-full">
                <p class="alert alert-info fs-sm">
                <i class="fa fa-fw fa-info me-1"></i> <b>Catatan : </b> {{ $serahterima->catatan }}
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">#</th>
                                <th>No Resi</th>
                                <th>Nama Expedisi</th>
                                <th>Waktu Discan</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach ($detail as $dt)
                                @php
                                    $colorExpedisi = $dt->color == null ? '#2B4C99' : $dt->color;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center"><a class="fw-semibold" href="javascript:viod(0);">
                                            <strong>{{ $dt->no_resi }}</strong>
                                        </a></td>
                                    <td class="text-center">
                                        <span class="badge"
                                            style="background: {{ $colorExpedisi }};color:white">{{ $dt->expedisi }}</span>
                                    </td>
                                    <td class="text-center">{{ $dt->created_at }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            @if ((Auth::user()->role == 'admin' || canPermission('Daftar Serah Terima.Full_Akses')) || ((Auth::user()->role == 'user' || !canPermission('Daftar Serah Terima.Full_Akses')) && date('Y-m-d', strtotime($dt->created_at)) === date('Y-m-d')))
                                                <a href="javascript:void(0)" id="{{ $dt->no_resi }}"
                                                    class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                                    data-bs-toggle="tooltip" title="Delete"><i
                                                        class="fa fa-fw fa-trash"></i>
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" id="{{ $dt->no_resi }}"
                                                    class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                                    data-bs-toggle="tooltip"
                                                    title="Data is lock"
                                                    style="pointer-events: none;background: #fca5a5;color:white"><i
                                                        class="fa fa-fw fa-lock"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

           <!-- <p class="alert alert-info fs-sm">
                <i class="fa fa-fw fa-info me-1"></i> <b>Catatan : </b> {{ $serahterima->catatan }}
            </p>-->
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
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-danger btn-destroy-resi">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Small Block Modal -->
@endsection
