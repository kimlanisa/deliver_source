@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <script>
        One.helpersOnLoad(['js-flatpickr']);
        $('select[name="inbound_retur_id"]').select2({
            placeholder: 'Pilih No Resi Inbound',
            allowClear: true
        });
    </script>

    <!-- Page JS Code -->


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            $('[name="alasan"]').on('change', function() {
                if ($(this).val() == 'Salah Packing') $('.remark').show();
                else $('.remark').hide();
            });
        });

        const inboundReturData = @json($inbound_retur);
        $('select[name="inbound_retur_id"]').on('change', function() {
            const inboundReturId = $(this).val();
            const inboundRetur = inboundReturData.find(item => item.id == inboundReturId);
            $('input[name="shop"]').val(inboundRetur?.shop?.name ?? '');
            $('input[name="shop_id"]').val(inboundRetur?.shop_id ?? '');
            $('input[name="no_resi"]').val(inboundRetur?.no_resi ?? '');
            $('.no_resi').text(inboundRetur?.no_resi ?? '');
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
                        {{ ($data ?? null) ? 'Edit' : 'Buat' }} Komplain
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Komplain
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <form action="{{ route('manual-complaint.store') }}" method="post">
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
                        Komplain
                    </h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="row g-4">
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
                            <label class="form-label"> No Resi Inbound</label>
                            <select class="form-select @error('inbound_retur_id') is-invalid @enderror" name="inbound_retur_id">
                                <option value="">Pilih No Resi Inbound</option>
                                @foreach ($inbound_retur as $ib)
                                    <option value="{{ $ib->id }}"
                                        @if (old('inbound_retur_id', $data->inbound_retur_id ?? '') == $ib->id) selected="selected" @endif>{{ $ib->no_resi }} - {{ $ib->shop->name ?? '' }} - {{ $ib->ekspedisi->expedisi ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="no_resi" value="{{ old('no_resi', $data->no_resi ?? '') }}">
                            @if (($data->no_resi ?? ''))
                              <div class="mt-2">
                                <span style="font-size: 14px">No Resi :</span>  <span class="no_resi" style="font-size: 15px; font-weight: bold">{{ $data->no_resi }}</span>
                              </div>
                            @endif
                            @error('inbound_retur_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">Toko</label>
                            <input type="text"
                                class="form-control @error('shop') is-invalid @enderror" value="{{ ($data->shop->name ?? '') }}" name="shop" disabled />
                            <input type="hidden" name="shop_id" value="{{ ($data->shop->id ?? '') }}">
                            {{-- <select class="form-select @error('shop_id') is-invalid @enderror" name="shop_id">
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
                            @enderror --}}
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
                            <label class="form-label">No Whatsapp Customer</label>
                            <input type="number" value="{{ old('no_whatsapp', $data->no_whatsapp ?? '') }}"
                                class="form-control @error('no_whatsapp') is-invalid @enderror" name="no_whatsapp"
                                placeholder="Masukan no whatsapp" />
                            @error('no_whatsapp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">Solusi</label>
                            @php
                                $solutions = [
                                    'Refund',
                                    'Tukar Barang',
                                ];
                            @endphp
                            <select class="form-select @error('solution') is-invalid @enderror" name="solution">
                                <option value="">Pilih Solusi</option>
                                @foreach ($solutions as $item)
                                    <option value="{{ $item }}"
                                        @if (old('solution', $data->solution ?? '') == $item) selected="selected" @endif>{{ $item }}
                                    </option>
                                @endforeach
                            </select>
                            @error('solution')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">Alasan</label>
                            @php
                                $alasan = [
                                    'Salah Packing',
                                    'Produk Bermasalah / Rijek',
                                    'Pembeli Salah Checkout',
                                ];
                            @endphp
                            <select class="form-select @error('alasan') is-invalid @enderror" name="alasan">
                                <option value="">Pilih Alasan </option>
                                @foreach ($alasan as $item)
                                    <option value="{{ $item }}"
                                        @if (old('alasan', $data->alasan ?? '') == $item) selected="selected" @endif>{{ $item }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alasan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 remark" style="display: {{ (($data->alasan ?? '') === 'Salah Packing') ? 'show' : 'none' }}">
                            <label class="form-label">Remark</label>
                            <textarea
                                class="form-control @error('remark') is-invalid @enderror" name="remark"
                                placeholder="Masukan remark">{{ old('remark', $data->remark ?? '') }}</textarea>
                            @error('remark')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan Bermasalah</label>
                            <textarea
                                class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                placeholder="Masukan alasan retur">{{ old('keterangan', $data->keterangan ?? '') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="push">
                <a href="{{ route('manual-complaint.index') }}" type="button" class="btn btn-sm btn-danger btn-delete-all"
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
