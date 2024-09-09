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
                        {{ ($data ?? null) ? 'Edit' : 'Buat' }} {{ $data->name }}
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
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
        <form action="{{ route('custom-menu.menu.store', $data->slug) }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $data_value['id'] ?? '' }}">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        {{ $data->name }}
                    </h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="row g-4">
                        @foreach ($data->label_data as $item)
                        <div class="{{ $item['type_input'] === 'textarea' ? 'col-6' : 'col-4' }}">
                            <label class="form-label">{{ $item['label'] }}</label>
                            @switch($item['type_input'])
                                @case('date')
                                    <input type="date" value="{{ old($item['name'], $data_value[$item['name']] ?? '') }}"
                                        class="form-control @error($item['name']) is-invalid @enderror" name="{{ $item['name'] }}"
                                        placeholder="Masukan {{ $item['label'] }}" />
                                @break
                                @case('number')
                                    <input type="number" value="{{ old($item['name'], $data_value[$item['name']] ?? '') }}"
                                        class="form-control @error($item['name']) is-invalid @enderror" name="{{ $item['name'] }}"
                                        placeholder="Masukan {{ $item['label'] }}" />
                                @break
                                @case('textarea')
                                    <textarea
                                        class="form-control @error($item['name']) is-invalid @enderror" name="{{ $item['name'] }}"
                                        placeholder="Masukan {{ $item['label'] }}">{{ old($item['name'], $data_value[$item['name']] ?? '') }}</textarea>
                                @break
                                @case('select')
                                    <select name="{{ $item['name'] }}"
                                        class="form-control @error($item['name']) is-invalid @enderror">
                                        <option value="">Pilih {{ $item['label'] }}</option>
                                        @foreach ($item['select_option'] as $option)
                                            <option value="{{ $option['label'] }}"
                                                {{ old($item['name'], $data_value[$item['name']] ?? '') == $option['label'] ? 'selected' : '' }}>
                                                {{ $option['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                @break
                                @default
                                <input type="text" value="{{ old($item['name'], $data_value[$item['name']] ?? '') }}"
                                class="form-control @error($item['name']) is-invalid @enderror" name="{{ $item['name'] }}"
                                placeholder="Masukan {{ $item['label'] }}" />
                            @endswitch
                            @error($item['name'])
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="push">
                <a href="{{ route('custom-menu.menu', $data->slug) }}" type="button" class="btn btn-sm btn-danger btn-delete-all"
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
