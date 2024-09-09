@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.js') }}"></script>


    <script>
        One.helpersOnLoad(['js-flatpickr']);
    </script>

    <!-- Page JS Code -->


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Detail Pemintaan Refund
                    </h2>
                    <h5>No TRX : {{ $data->no_trx }}</h5>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Pemintaan Refund
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
                    DETAIL RETURAN PAKET
                </h3>
                <a href="javascript:history.back()" class="btn btn-alt-primary btn-sm">
                    <i class="fa fa-arrow-alt-circle-left text-info me-1"></i>Kembali
                </a>
            </div>

            <div class="block-content block-content-full">
                <div class="row g-4">
                    <div class="col-4">
                        <label class="form-label">Tanggal</label>
                        <input  class="form-control" value="{{ date('d-m-Y', strtotime($data->date)) }}" readonly/>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Toko</label>
                        <input  class="form-control" value="{{ $data->shop->name ?? '-' }}" readonly/>
                    </div>
                    <div class="col-4">
                        <label class="form-label">No Pesanan</label>
                        <input  class="form-control" value="{{ $data->no_pesanan ?? '-' }}" readonly/>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Customer_Id</label>
                        <input  class="form-control" value="{{ $data->customer ?? '-' }}" readonly/>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Nominal Refund</label>
                        <input  class="form-control" value="{{ $data->nominal_refund ?? '-' }}" readonly/>
                    </div>
                    <div class="col-4">
                        <label class="form-label">No Rekening</label>
                        <input  class="form-control" value="{{ $data->no_rekening ?? '-' }}" readonly/>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Nama Bank</label>
                        <input  class="form-control" value="{{ $data->nama_bank ?? '-' }}" readonly/>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Nama Pemilik Rekening</label>
                        <input  class="form-control" value="{{ $data->nama_pemilik_rekening ?? '-' }}" readonly/>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alasan Refund</label>
                        <textarea
                            class="form-control" readonly
                            placeholder="Masukan alasan retur">{{ $data->alasan_refund ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
