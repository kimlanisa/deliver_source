@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap4-datetimepicker@5.2.3/build/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select-option {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
    </style>
@endsection

@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>

    <script>
        One.helpersOnLoad(['js-flatpickr', 'js-summernote', 'js-select2']);
        let summernote_menu = [];
        const uploadUrl = '{{ route('uploadPhoto') }}';
        const deleteUrl = '{{ route('deletePhoto') }}';
        const heightRow = 200;
    </script>

    <script src="{{ asset('js/summernote-upload.js') }}"></script>

    <!-- Page JS Code -->
    <script>
        const base_url = '{{ url('/') }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const input_container = $('#input-container');

        let allData = [];

        function addInput() {
            let id = input_container.children().last()?.attr('id')?.split('_')?.[1] ?? 0;
            const new_id = parseInt(id) + 1;
            input_container.append(createInput(new_id, '', '', ''));
            summernote_menu.push(`#input-container .summernote_${new_id}`);
            initializeSummernote()
            $('.select2').select2();
        }

        let status_pekerjaan = [{
                value: 'pending',
                label: 'Pending'
            },
            {
                value: 'in_progress',
                label: 'In Progress'
            },
            {
                value: 'completed',
                label: 'Completed'
            }
        ];

        let id_delete = [];
        let files_input = [];
        const data_edit = @json($data ?? null);
        console.log(data_edit, data_edit?.laporan_karyawan_detail)
        if (data_edit) {
            data_edit?.laporan_karyawan_detail?.forEach((item, index) => {
                input_container.append(createInput(item.id, item.pekerjaan, item.status, item.id));
                summernote_menu.push(`#input-container .summernote_${item.id}`);
                const element_attachment = createFileAttachment(item.id, JSON.parse(item.images));
                files_input[item.id] = JSON.parse(item.images);
                $(`.attachment_list_${item.id}`).html(element_attachment);
            });
        } else {
            input_container.append(createInput(1, '', '', ''));
            summernote_menu.push(`#input-container .summernote_1`);
        }

        function createInput(id, pekerjaan, status, id_pekerjaan) {
            let input = `<div class="item_list_input" id="input_${id}">
                            <div class="row g-4 mb-4">
                                <div class="col-md-8">
                                    <label for="pekerjaan">Pekerjaan</label>
                                    <textarea class="form-control summernote_${id}" name="pekerjaan[${id}]" rows="4">${pekerjaan}</textarea>
                                    <input type="hidden" name="ids[${id}]" value="${id_pekerjaan}">
                                </div>
                                <div class="col-md-3">
                                    <label for="status">Status</label>
                                    <div>
                                        <select class="form-control select2" name="status[${id}]">
                                            <option value="">Pilih Status</option>
                                            ${status_pekerjaan.map(item => `<option value="${item.value}" ${item.value === status ? 'selected' : ''}>${item.label}</option>`)}
                                        </select>
                                    </div>
                                    <div class="mt-4">
                                        <label for="status">Attachment</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <label class="btn btn-primary btn-sm" for="attachment_${id}">
                                                    <input type="file" class="attachment_file" multiple id="attachment_${id}" name="file[${id}][]" style="display: none;">
                                                    <i class="fa fa-paperclip"></i>
                                                </label>
                                                <div style="font-size: 14px">
                                                    File
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="status">Aksi</label>
                                    <button type="button" class="btn btn-danger btn-sm removeRow" data-id="${id_pekerjaan}" data-row="${id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3 attachment_list_${id}" style="flex-wrap: wrap">

                            </div>
                        </div>`;

            return input;
        }

        function collectData() {
            $('#input-container').find('.item_list_input').each(function() {
                let pekerjaan = $(this).find('textarea').val();
                let status = $(this).find('select').val();
                let id = $(this).attr('id').split('_')[1];
                let id_pekerjaan = $(this).find('input[type="hidden"]').val();
                let data = {
                    pekerjaan,
                    status,
                    id,
                    id_pekerjaan
                };
                let index = allData.findIndex(item => item.id === id);
                if (index !== -1) {
                    allData[index] = data;
                } else {
                    allData.push(data);
                }
            });
            return allData;
        }

        $(document).ready(function() {
            collectData();
            initializeSummernote()
            $('.select2').select2();
        }).on('click', '.removeRow', function() {
            let id = $(this).data('id');
            let dataRow = $(this).data('row');
            $(`#input_${dataRow}`).remove();

            if (id !== '') {
                allData = allData.filter(item => parseInt(item.id) !== parseInt(id));
                id_delete.push(id);
                $('input[name="id_delete"]').val(id_delete.join(','));
            }
        }).on('change', '.attachment_file', function() {
            const id = $(this).attr('id').split('_')?.[1];
            const files = Object.values(this.files)

            if (!files_input[id]) {
                files_input[id] = []
            }

            const last_key = Object.keys(files_input[id]).length;
            files.forEach((file, index) => {
                files_input[id][last_key + index] = file;
            });

            const files_value = Object.values(files_input[id])
            const attachment_file = createFileAttachment(id, files_value);
            $(`.attachment_list_${id}`).html(attachment_file);

        }).on('click', '.cancel-input', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const id = $(this).data('id');
            const key = $(this).data('key');
            $(this).parent().remove();
            files_input[id] = Object.values(files_input[id]).filter((item, index) => index !== key);
            const files_value = Object.values(files_input[id])
            const attachment_file = createFileAttachment(id, files_value);
            $(`.attachment_list_${id}`).html(attachment_file);
        }).on('click', '.show-detail', function() {
            const id = $(this).data('id');
            const key = $(this).data('key');
            const file = files_input[id][key];
            const open = file.url ? `${base_url}/${file?.url}` : URL.createObjectURL(file);
            if(!file.url) {
                window.open(open, '_blank');
                return
            }
            let url = ''
            if (file.url?.includes('pdf')) {
                url = `https://docs.google.com/gview?url=${base_url}/${file.url}&embedded=true`
            }else if (file.url?.includes('doc') || file.url?.includes('docx') || file.url?.includes('ppt') || file.url?.includes('pptx') || file.url?.includes('xls') || file.url?.includes('xlsx')) {
                url = `https://view.officeapps.live.com/op/embed.aspx?src=${base_url}/${file.url}`
            }  else {
                url = `${base_url}/${file.url}`
            }

            $('#modalDetailFile').modal('show');
            $('#bodyDetailFile').html(`
            <iframe src="${url}" style="width: 100%; height: 100vh"></iframe>
            `);
            // window.open(open, '_blank');
        });

        $('#submitBtn').on('click', function() {
            collectData();

            const input_date = $('input[name="date"]').val();

            if (allData.filter(item => item.pekerjaan === '' || item.status === '' || input_date === '').length >
                0 || allData.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ada data yang belum diisi!',
                });
                return;
            }

            const formData = new FormData();
            formData.append('pekerjaan', JSON.stringify(allData));
            formData.append('id', $('input[name="id"]').val());
            formData.append('date', input_date);
            formData.append('pic_id', $('select[name="pic_id"]').val());
            formData.append('id_delete', id_delete.join(','));
            Object.keys(files_input).forEach((key) => {
                Object.values(files_input[key]).forEach((file) => {
                    if(file.url) {
                        formData.append(`attachment_file[${key}][]`, JSON.stringify(file));
                    } else {
                        formData.append(`attachment_file[${key}][]`, file);
                    }
                });
            });

            $.ajax({
                url: '{{ route('laporan-karyawan.store') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('laporan-karyawan.index') }}';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        });
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan!',
                    });
                }
            });


            // $('#formSubmit').submit();
        })

        function createFileAttachment(id, files) {
            const element =  files.map((file, key) => {
                return ` <div class="show-detail" style="cursor: pointer" data-id="${id}"  data-key="${key}">
                                <label class="btn bg-white text-black shadow border btn-sm d-flex align-items-center gap-2">
                                    <i class="fa fa-file"></i>
                                    <div style="max-width: 140px;
                                                -webkit-line-clamp: 1;
                                                text-overflow: ellipsis;
                                                white-space: nowrap;
                                                overflow: hidden;">
                                        ${file?.name}
                                    </div>
                                    <div>
                                        .${file?.name?.split('.')?.pop()}
                                    </div>
                                    <div>
                                        ${file?.size / 1000} KB
                                    </div>
                                    <i class="fa fa-times ml-2 text-danger cancel-input" data-id="${id}" data-key="${key}"></i>
                                </label>
                            </div>`
            })

            return element.join('');
        }


        $('[name="pic"]').select2({
            tags: true,
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
                        {{ $data ?? null ? 'Edit' : 'Buat' }} Laporan Karyawan
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Laporan Karyawan
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <form action="{{ route('laporan-karyawan.store') }}" method="post" id="formSubmit" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
            <input type="hidden" name="id_delete" value="">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Laporan Karyawan</h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="row g-4 mb-4">
                        <div class="col-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date"
                                value="{{ old('date', $data->date ?? null ? date('Y-m-d', strtotime($data->date)) : '') }}"
                                class="form-control datetimepicker  @error('date') is-invalid @enderror" name="date"
                                placeholder="Masukan tanggal" required />
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label class="form-label">PIC</label>
                            @php
                                $pic_report = App\Models\PicReport::get();
                            @endphp
                            <select class="form-control select2 @error('pic_id') is-invalid @enderror" name="pic_id" required>
                                <option value="">Pilih PIC</option>
                                @foreach ($pic_report as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('pic_id', $data->pic_id ?? null) == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pic_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div id="input-container">

                    </div>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addInput()">
                        <i class="fa fa-plus me-1"></i> Tambah Laporan
                    </button>
                </div>
            </div>

            <div class="push">
                <a href="{{ route('laporan-karyawan.index') }}" type="button"
                    class="btn btn-sm btn-danger btn-delete-all">
                    <i class="fa fa-minus me-1"></i> Batalkan
                </a>
                <button type="button" id="submitBtn" class="btn btn-sm btn-success btn-save">
                    <i class="fa fa-check me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
    <!-- END Page Content -->
    <div class="modal" id="modalDetailFile" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <div class="block-options">
                            <button type="button" class="btn-block-option float-right" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm" id="bodyDetailFile">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
