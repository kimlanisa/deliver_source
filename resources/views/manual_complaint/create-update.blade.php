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
            $('[name="alasan"]').on('change', function() {
                if ($(this).val() == 'Salah Packing') $('.remark').show();
                else $('.remark').hide();
            });
        });

        $('select[name="inbound_retur_id"]').on('change', function() {
            const inboundReturId = $(this).val();
            const inboundRetur = inboundReturData.find(item => item.id == inboundReturId);
            $('input[name="shop"]').val(inboundRetur?.shop?.name ?? '');
            $('input[name="shop_id"]').val(inboundRetur?.shop_id ?? '');
            $('input[name="no_resi"]').val(inboundRetur?.no_resi ?? '');
            $('.no_resi').text(inboundRetur?.no_resi ?? '');
        });

        $('.listActionSku').on('click', '.addRow', function() {
            var itemList = $(this).closest('.itemList'),
                clone = itemList.clone()
            valueInputSku = itemList?.find('input[name="sku[]"]')
            valueInputJumlah = itemList?.find('input[name="jumlah[]"]')
            if (valueInputSku.val() == '' || valueInputJumlah.val() == '') {
                $.notify({
                    title: 'Error',
                    message: 'Isi inputan terlebih dahulu',
                    icon: 'fa fa-times'
                }, {
                    type: 'danger'
                });
                return;
            }
            itemList.find('.addRow').html(`<i class="fas fa-minus"></>`).addClass('btn-danger removeRow')
                .removeClass('btn-primary addRow')
            clone.addClass('mt-3')
            clone.find('input').val('');
            $('.listActionSku').append(clone);
        });

        $('.listActionSku').on('click', '.removeRow', function() {
            var itemList = $(this).closest('.itemList');
            itemList.remove()
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
                            <label class="form-label">Tanggal</label>
                            <input type="date" value="{{ old('date', ($data->date_time  ?? null)? date('Y-m-d', strtotime($data->date_time)) : '') }}"
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
                            <label class="form-label">No Resi Inbound</label>
                            <input type="text" value="{{ old('no_resi', $data->no_resi ?? '') }}"
                                class="form-control @error('no_resi') is-invalid @enderror bg-gray" name="no_resi"
                                placeholder="Scan atau input no resi" />
                            @error('no_resi')
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
                                    'Produk Bermasalah',
                                    'Pembeli Salah C.O',
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
                        <div class="listActionSku mt-4">
                            @if ($data ?? null)
                                @php
                                $dataSkuJumlah = json_decode($data->sku_jumlah) ?? [];
                                @endphp
                                @foreach ($dataSkuJumlah ?? [] as $key => $item)
                                    <div class="row align-items-center itemList mt-3">
                                        <div class="col-4">
                                            <label class="form-label">SKU Bermasalah</label>
                                            <input type="text" value="{{ $item->sku ?? ''}}"
                                                class="form-control" name="sku[]"
                                                placeholder="Masukan sku" />
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" value="{{  $item->jumlah ?? '' }}"
                                                class="form-control" name="jumlah[]"
                                                placeholder="Masukan jumlah" />
                                        </div>
                                        <div class="col-1 mt-4">
                                            @if (($key + 1) == count($dataSkuJumlah))
                                            <button class="btn btn-primary addRow" type="button">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            @else
                                            <button class="btn btn-danger removeRow" type="button">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                <div class="row align-items-center itemList">
                                    <div class="col-4">
                                        <label class="form-label">SKU Bermasalah</label>
                                        <input type="text" value=""
                                            class="form-control" name="sku[]"
                                            placeholder="Masukan sku" />
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" value=""
                                            class="form-control" name="jumlah[]"
                                            placeholder="Masukan jumlah" />
                                    </div>
                                    <div class="col-1 mt-4">
                                        <button class="btn btn-primary addRow" type="button">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
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
