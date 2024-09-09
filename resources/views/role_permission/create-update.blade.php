@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .table-permission {
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .table-permission thead {
            background-color: #ddd;
        }
        .table-permission thead th {
            border: 1px solid #ddd;
        }
        .table-permission tbody td {
            border: 1px solid #ddd;
        }
    </style>
@endsection


@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/js/bootstrap-datetimepicker.min.js">
    </script>

    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <script>
        One.helpersOnLoad(['js-flatpickr']);

        // $('[name="checkAll"]').on('change', function() {
        //     if ($(this).is(':checked')) {
        //         $('input[name="permission[]"]').prop('checked', true);
        //     } else {
        //         $('input[name="permission[]"]').prop('checked', false);
        //     }
        // });

        // $('input[name="permission[]"]').on('change', function() {
        //     if ($(this).is(':checked')) {
        //         if ($('input[name="permission[]"]').length == $('input[name="permission[]"]:checked').length) {
        //             $('[name="checkAll"]').prop('checked', true);
        //         }
        //     } else {
        //         $('[name="checkAll"]').prop('checked', false);
        //     }
        // });

        // $(document).ready(function() {
        //     $('input[name="permission[]"]').each(function() {
        //         if ($(this).is(':checked')) {
        //             if ($('input[name="permission[]"]').length == $('input[name="permission[]"]:checked').length) {
        //                 $('[name="checkAll"]').prop('checked', true);
        //             }
        //         } else {
        //             $('[name="checkAll"]').prop('checked', false);
        //         }
        //     });
        // });
    </script>

<script>
    $(function () {
         $('.hader-toggle').on('click', function () {
             const permission = $(this).parent().find('.check-all').attr('data-permission');
             const child = $(`.collapse${permission}`);
             if(child.hasClass('show')) {
                 child.collapse('hide');
                 $(this).html('<i class="fas fa-chevron-down"></i>');
             } else {
                 child.collapse('show');
                 $(this).html('<i class="fas fa-chevron-up"></i>');
             }
         });

         $('.collapse-all').on('click', function() {
             const child = $('.collapse');
             if(child.hasClass('show')) {
                 child.collapse('hide');
                 $(this).html('Buka semua <i class="fas fa-chevron-down"></i>');
             } else {
                 child.collapse('show');
                 $(this).html('Tutup semua <i class="fas fa-chevron-up"></i>');
             }
         });

         $('.check-all').on('change', function () {
             const checked = $(this).prop("checked");
             const permission = $(this).attr('data-permission');
             $('input[data-permission="' + permission + '"]').prop('checked', checked);
         });

         $('.checked-all').on('change', function () {
             const checkedAll = $('.check-all');
             const checkedAllPermission = $('.check-all-permission');
             checkedAll.prop('checked', this.checked);
             checkedAllPermission.prop('checked', this.checked);
         });

         $('.check-all-permission').on('click', function () {
             checkedAll(this);
         });

         checked();
         function checked() {
             const parent = $('.check-all-permission');
             $.each(parent, function(i, v) {
                 checkedAll($(v));
             })
         }

         function checkedAll(element) {
             let permission = $(element).attr('data-permission');
                 checkPermission = $('.check-all-permission[data-permission="' + permission + '"]');
                 checkedPermission = $('.check-all-permission[data-permission="' + permission + '"]:checked');

             if($(this).prop("checked") != true) {
                 $('.check-all[data-permission="' + permission + '"]').prop("checked", false);
             }

             if(checkPermission.length == checkedPermission.length){
                 $('.check-all[data-permission="' + permission + '"]').prop("checked", true);
             }
         }
     });
 </script>

    <!-- Page JS Code -->


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {});
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
                        {{ $data ?? null ? 'Edit' : 'Buat' }} Peran & Hak Akses
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Peran & Hak Akses
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <form action="{{ route('role-permission.store') }}" method="post">
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
                        Peran & Hak Akses
                    </h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="row g-4">
                        <div class="col-4">
                            <label class="form-label">Nama Peran</label>
                            <input type="text" value="{{ old('name', $data->name ?? '') }}"
                                class="form-control @error('name') is-invalid @enderror" name="name"
                                placeholder="Masukan name" />
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 mt-4">
                            {{-- <div class="mb-3">
                                <label class="d-flex align-items-center" style="gap: 12px; max-width: 150px ">
                                    <input type="checkbox" name="checkAll" style="height: 16px; width: 16px">
                                    <div style="font-size: 16p; font-weight: 600">Pilih Semua</div>
                                </label>
                            </div> --}}
                            {{-- <div class="row">
                                @foreach ($permission as $access)
                                    <div class="col-4 mb-2">
                                        <label class="d-flex align-items-center" style="gap: 12px">
                                            <input type="checkbox" name="permission[]" style="height: 16px; width: 16px"
                                                value="{{ $access->name }}"
                                                {{($data ?? null) ? $data->hasPermissionTo($access->name) ? "checked" : "" : ''}}>
                                            <div style="font-size: 16p; font-weight: 600">
                                                {{ str_replace(['.', '_'], ' ', $access->name) }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div> --}}
                            <div>
                                <div class="d-flex align-items-center" style="gap: 20px">
                                    <div class="" style="display: inline-block">
                                        <label class="d-flex align-items-center" style="gap: 10px; cursor: pointer">
                                            <input class="checked-all" style="height: 15px; width: 15px; border: 1px solid" type="checkbox">
                                                Pilih semua </label>
                                    </div>
                                    <div style="cursor: pointer">
                                        <div class="btn btn-primary btn-sm collapse-all">
                                            Buka semua <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        @php
                                            $last = "";
                                            $no = 0;
                                        @endphp
                                        @foreach ($permission as $item)
                                        @php
                                            $prefix = explode(".",$item->name);
                                        @endphp
                                        @if ($last != $prefix[0])
                                            @if ($no != 0)
                                                </table>
                                                </div>
                                            @endif
                                    <div class="col-md-4">
                                        <table class="table table-hover table-permission">
                                            @php
                                            $last = $prefix[0];
                                            @endphp
                                            <thead>
                                                <tr>
                                                    <th class="d-flex align-items-center justify-content-between">
                                                        <label class="d-flex align-items-center" style="gap: 10px">
                                                            <input class="check-all" style="height: 15px; width: 15px; border: 1px solid" type="checkbox" name="{{$last}}"
                                                                id="{{str_replace(' ', '', $last)}}" data-permission="{{str_replace(' ', '', $last)}}">
                                                                Pilih semua {{ $last }}</label>
                                                        <div class="btn hader-toggle btn-sm btn-primary px-2 py-1" style="cursor: pointer; border-radius: 4px">
                                                            <i class="fas fa-chevron-down" style="color: white"></i>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            @endif
                                            <tr class="collapse collapse{{ str_replace(' ', '', $last) }}">
                                                @php
                                                    $has_permission = false;
                                                    try {
                                                         $has_permission = $data->hasPermissionTo($item->name);
                                                    } catch (\Throwable $th) {
                                                        $has_permission = false;
                                                    }
                                                @endphp
                                                <td>
                                                    <label class="d-flex align-items-center" style="gap: 10px">
                                                        <input type="checkbox" class="check-all-permission" style="height: 15px; width: 15px; border: 1px solid" name="permission[]" id={{$item->name}}"
                                                        value="{{$item->id}}" data-permission="{{str_replace(' ', '', $last)}}"
                                                        {{($data ?? null) ? ($has_permission)? "checked" : "" : ''}} >
                                                        @php
                                                        $name = explode(".",$item->name);
                                                        @endphp
                                                        {{$name[1] ?? 'List'}}
                                                    </label>
                                                </td>
                                            </tr>
                                            @php
                                            $no++;
                                            @endphp
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <a href="{{ route('role-permission.index') }}" type="button" class="btn btn-sm btn-danger btn-delete-all"
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
