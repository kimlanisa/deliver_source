@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/js/bootstrap-datetimepicker.min.js"></script>

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
        $(function() {
            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? "." : "";
                    rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
            }
            $('input[name="nominal_refund"]').on('keyup', function() {
                var nominal_refund = $(this).val();
                var nominal_refund = formatRupiah(nominal_refund);
                $('input[name="nominal_refund"]').val(nominal_refund);
            });
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
                        {{ ($data ?? null) ? 'Edit' : 'Buat' }} Permintaan Refund
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
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
        <form action="{{ route('request-refund.store') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Permintaan Refund
                    </h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="row g-4">
                        <div class="col-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" value="{{ old('date', ($data->date  ?? null)? date('Y-m-d', strtotime($data->date)) : '') }}"
                                class="form-control datetimepicker  @error('date') is-invalid @enderror" name="date"
                                placeholder="Masukan tanggal" />
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">Toko</label>
                            <select class="form-select @error('shop_id') is-invalid @enderror" name="shop_id">
                                <option value="">Pilih Toko</option>
                                @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}"
                                        @if (old('shop_id', $data->shop_id ?? '') == $shop->id) selected="selected" @endif>{{ $shop->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shop_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">No Pesanan</label>
                            <input type="text" value="{{ old('no_pesanan', $data->no_pesanan ?? '') }}"
                                class="form-control @error('no_pesanan') is-invalid @enderror" name="no_pesanan"
                                placeholder="Masukan no pesanan" />
                            @error('no_pesanan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">Customer_Id</label>
                            <input type="text" value="{{ old('customer', $data->customer ?? '') }}"
                                class="form-control @error('customer') is-invalid @enderror" name="customer"
                                placeholder="Masukan customer" />
                            @error('customer')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">Nominal Refund</label>
                            <input type="text" value="{{ old('nominal_refund', $data->nominal_refund ?? '') }}"
                                class="form-control @error('nominal_refund') is-invalid @enderror" name="nominal_refund"
                                placeholder="Masukan Nominal Refund" />
                            @error('nominal_refund')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">No Rekening</label>
                            <input type="text" value="{{ old('no_rekening', $data->no_rekening ?? '') }}"
                                class="form-control @error('no_rekening') is-invalid @enderror" name="no_rekening"
                                placeholder="Masukan No Rekening" />
                            @error('no_rekening')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" value="{{ old('nama_bank', $data->nama_bank ?? '') }}"
                                class="form-control @error('nama_bank') is-invalid @enderror" name="nama_bank"
                                placeholder="Masukan Nama Bank" />
                            @error('nama_bank')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">Nama Pemilik Rekening</label>
                            <input type="text" value="{{ old('nama_pemilik_rekening', $data->nama_pemilik_rekening ?? '') }}"
                                class="form-control @error('nama_pemilik_rekening') is-invalid @enderror" name="nama_pemilik_rekening"
                                placeholder="Masukan Nama Pemilik Rekening" />
                            @error('nama_pemilik_rekening')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alasan Refund</label>
                            <textarea
                                class="form-control @error('alasan_refund') is-invalid @enderror" name="alasan_refund"
                                placeholder="Masukan alasan retur">{{ old('alasan_refund', $data->alasan_refund ?? '') }}</textarea>
                            @error('alasan_refund')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="push">
                <a href="{{ route('request-refund.index') }}" type="button" class="btn btn-sm btn-danger btn-delete-all"
                    onclick="handlerDeleteDataAll()">
                    <i class="fa fa-minus me-1"></i> Batalkan
                </a>
                <button type="submit" class="btn btn-sm btn-success btn-save-all">
                    <i class="fa fa-check me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
    <!-- END Page Content -->
@endsection
