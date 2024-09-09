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
    </script>

    <!-- Page JS Code -->



    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const listMenuCustom = $('.listMenuCustom')

        let dataInput = []

        $('.addRow').on('click', function() {
            const last_child = listMenuCustom.find('.itemList').last()
            const last_child_id = last_child.find('.label_name').attr('id').split('_')?.[1] ?? 0

            var itemList = last_child.closest('.itemList'),
                clone = itemList.clone()

            valueInputLabel = itemList?.find('.label_name')
            valueInputType = itemList?.find('select')
            if (valueInputLabel.val() == '' || valueInputType.val() == '') {
                $.notify({
                    title: 'Error',
                    message: 'Isi inputan terlebih dahulu',
                    icon: 'fa fa-times'
                }, {
                    type: 'danger'
                });
                return;
            }

            collectDataValue()

            const clone_data = {
                id: `${parseInt(last_child_id) + 1}`,
                label: '',
                type_input: '',
                select_option: [{
                    id: 1
                }],
                editable: false
            }

            const last_id = dataInput.length
            dataInput.push(clone_data)

            listMenuCustom.append(createRowMenuCustom('', '', parseInt(last_child_id) + 1, [{
                id: 1
            }], false, {
                first: false,
                last: true
            }))


            // itemList.find('.addRow').html(`<i class="fas fa-minus"></>`).addClass('btn-danger removeRow')
            //     .removeClass('btn-primary addRow')
            // clone.addClass('mt-3')
            // clone.find('.label_name').val('').attr('id', `label_${parseInt(last_child_id) + 1}`).attr('name',
            //     `label[${parseInt(last_child_id) + 1}]`)
            // clone.find('input[type="hidden"].label_name_id').val(parseInt(last_child_id) + 1).attr('name',
            //     `label_id[${parseInt(last_child_id) + 1}]`)
            // clone.find('.select-option-list').hide()
            // clone.find('.select-option').children().each((key, item) => {
            //     if (key > 0)
            //         $(item).remove()
            // })
            // clone.find('.select-option').removeClass(`select-option-${parseInt(last_child_id)}`).addClass(
            //     `select-option-${parseInt(last_child_id) + 1}`)

            // clone.find('.select-option').find(`.pilihan`).val('').attr('id',
            //         `pilihan_${parseInt(last_child_id) + 1}`)
            //     .attr('name', `pilihan[${parseInt(last_child_id) + 1}][1]`)
            //     .removeClass(`pilihan_${parseInt(last_child_id)}`).addClass(
            //         `pilihan_${parseInt(last_child_id) + 1}`)
            // clone.find('.select-option').find(`.color_pilihan`).val('').attr('id',
            //         `color_pilihan_${parseInt(last_child_id) + 1}`)
            //     .attr('name', `color_pilihan[${parseInt(last_child_id) + 1}][1]`)
            //     .removeClass(`color_pilihan_${parseInt(last_child_id)}`).addClass(
            //         `color_pilihan_${parseInt(last_child_id) + 1}`)

            // clone.find('.editable_pilihan').prop('checked', false).attr('id',
            //         `editable_pilihan_${parseInt(last_child_id) + 1}`)
            //     .attr('name', `editable_pilihan[${parseInt(last_child_id) + 1}]`)
            //     .removeClass(`editable_pilihan_${parseInt(last_child_id)}`).addClass(
            //         `editable_pilihan_${parseInt(last_child_id) + 1}`)
            // clone.find('.editable_pilihan').val('')
            // clone.find('.label_editable_pilihan').attr('for', `editable_pilihan_${parseInt(last_child_id) + 1}`)

            // clone.find('.select-option').find(`input[type="hidden"]`).val(parseInt(last_child_id) + 1).attr('name',
            //     `pilihan_id[${parseInt(last_child_id) + 1}][1]`)

            // // clone.find('.select-option').


            // clone.find('select').attr('name', `type_input[${parseInt(last_child_id) + 1}]`).val('')


            // listMenuCustom.append(clone);
        });

        listMenuCustom.on('click', '.removeRow', function() {
            var itemList = $(this).closest('.itemList');
            const id = itemList.find('.label_name').attr('id')
            const show_menu_item = $('.show_menu_item')
            show_menu_item.find(`#show-menu_${id.split('_')[1]}`).remove()
            itemList.remove()
        });

        const listShowData = $('#list_show_data_menu')
        const checkAllPermission = $('.check-all-permission')

        checkAllPermission.on('click', function() {
            const isChecked = $(this).prop('checked')
            listShowData.find('input[type="checkbox"]').prop('checked', isChecked)
        })


        let show_menu_item_collection = []
        var debounceTimer;
        $('.listMenuCustom').on('keyup', '.label_name', function() {
            let id_split = $(this).attr('id').split('_')
            const value = $(this).val()

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const all_value = listMenuCustom.find('.label_name')
                    .not(this)
                    .map((key, item) => $(item).val()).get()

                if (all_value.includes(value)) {
                    $.notify({
                        title: 'Error',
                        message: 'Label sudah ada',
                        icon: 'fa fa-times'
                    }, {
                        type: 'danger'
                    });
                    $(this).val('')
                }

                generateShowMenuItem(id_split, value)
            }, 500);

        })

        $('.btn-save').on('click', function() {
            const all_value = listMenuCustom.find('.label_name')
                .map((key, item) => $(item).val()).get().filter(item => item === '')
            if (all_value.length > 0) {
                $.notify({
                    title: 'Error',
                    message: 'Label tidak boleh kosong',
                    icon: 'fa fa-times'
                }, {
                    type: 'danger'
                });
            } else {
                $('form').submit()
            }
        })

        function generateShowMenuItem(id_split, value) {
            const show_menu_item = $('.show_menu_item')
            const id_split_first = id_split[1] ?? id_split

            if (value == '') {
                show_menu_item_collection = show_menu_item_collection.filter(item => item != id_split[1])
                show_menu_item.find(`#show-menu_${id_split_first}`).closest('.show_menu_item').remove()
                return
            }

            if (!show_menu_item_collection.includes(id_split_first)) {
                listShowData.append(`
                    <tr class="show_menu_item">
                        <td>
                            <label id="show-menu_${id_split_first}" class="d-flex align-items-center" style="gap: 10px">
                                <input type="checkbox" style="height: 15px; width: 15px; border: 1px solid" name="label_show[]" id="label_show_${id_split_first}"
                                value="${value.replace(/\s+/g, '_')} ${id_split_first}">
                                <span>${value}</span>
                            </label>
                        </td>
                    </tr>
                `)

                show_menu_item_collection.push(id_split_first)
            } else {
                show_menu_item.find(`#show-menu_${id_split_first}`).find('span').text(value)
                show_menu_item.find(`#show-menu_${id_split_first}`).find('input').val(
                    `${value.replace(/\s+/g, '_')} ${id_split[1]}`)
            }
        }

        let label_show = @json(old('label_show', $data->label_show ?? []));
        let label_type_input = @json(old('label_type_input', $data->label_type_input ?? []));

        label_type_input.forEach((item, key) => {
            if (typeof label_show === 'object')
                label_show = Object.values(label_show)

            generateShowMenuItem(key + 1, item?.label)

            const check_show_data = label_show?.some((lbl, key) => parseInt(lbl?.label?.split(' ')?.[1]) ==
                parseInt(item?.id))

            if (check_show_data)
                listShowData.find(`input[id="label_show_${item.id}"]`).prop('checked', true)
        })

        @php
            $type_input_list = [
                'text' => 'Text',
                'number' => 'Number',
                'date' => 'Date',
                'textarea' => 'Textarea',
                'select' => 'Select',
            ];
        @endphp


        const type_input_list = @json($type_input_list);

        if (label_type_input.length > 0) {
            label_type_input.forEach((item, key) => {
                const last_id = label_type_input.length - 1
                item.select_option = item.select_option.length > 0 ? item.select_option : [{
                    id: 1
                }]
                listMenuCustom.append(createRowMenuCustom(item?.label, item.type_input, key + 1, item
                    ?.select_option ?? [1], item?.editable ?? false, {
                        first: key == 0,
                        last: key == last_id
                    }, item?.label, item?.filter ?? false))
            })

            const check_default = label_show.filter(item => ['created_at', 'updated_at', 'created_by', 'updated_by']
                .includes(item.label))
            check_default.forEach((item, key) => {
                listShowData.find(`input[id="label_${item.label}"]`).prop('checked', true)
            })

            const check_all = listShowData.find('input[type="checkbox"]').length == listShowData.find(
                'input[type="checkbox"]:checked').length
            checkAllPermission.prop('checked', check_all)

            show_menu_item_collection = label_type_input.map((item, key) => `${key + 1}`)

        } else {
            listMenuCustom.append(createRowMenuCustom('', type_input_list, 1, [{
                id: 1
            }], false, {
                first: true,
                last: true
            }))
        }

        function createRowMenuCustom(value, type_input, id, list_option, editable = false, first_last, old_value = '', filter = false) {
            let list_option_comp = ''
            const {first: is_first, last: is_last} = first_last

            const select_option = list_option?.map((item, key) => {
                return `<div class="row align-items-center">
                                <div class="col-4">
                                    <label class="form-label">Pilihan</label>
                                    <input
                                        type="text"
                                        value="${item.label ?? ''}"
                                        id="pilihan_${item.id}"
                                        name="pilihan[${id}][${item.id ?? 1}]"
                                        class="pilihan_${item.id} form-control pilihan"
                                        placeholder="Masukan label pilihan" />
                                    <input type="hidden" name="pilihan_id[${id}][${item.id ?? 1}]" value="${id}">
                                </div>
                                <div class="col-2">
                                    <label class="form-label">Color</label>
                                    <input
                                        type="color"
                                        value="${item.color ?? ''}"
                                        id="color_pilihan_${item.id}"
                                        name="color_pilihan[${id}][${item.id ?? 1}]"
                                        class="color_pilihan_${id} form-control color_pilihan"
                                        placeholder="Masukan label pilihan" />
                                </div>
                                <div class="col-1 mt-4">
                                    <button type="button" class="btn btn-danger removeOptionRow">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>`
            })
            list_option_comp = `<div class="select-option-list mt-3 ms-4" style="${type_input === 'select' ? '': 'display: none'}">
                                    <div class="d-flex align-items-center gap-4 mb-3">
                                        <div>
                                            <label for="editable_pilihan_${id}" class="form-label label_editable_pilihan">Editable</label>
                                            <div>
                                                <input
                                                type="checkbox"
                                                value="true"
                                                id="editable_pilihan_${id}"
                                                name="editable_pilihan[${id}]"
                                                class="editable_pilihan_${id} form-check-input editable_pilihan"
                                                ${editable ? 'checked' : ''}
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <label for="filter_pilihan${id}" class="form-label label_filter_pilihan">Filter</label>
                                            <div>
                                                <input
                                                type="checkbox"
                                                value="true"
                                                id="filter_pilihan${id}"
                                                name="filter_pilihan[${id}]"
                                                class="filter_pilihan${id} form-check-input filter_pilihan"
                                                ${filter ? 'checked' : ''}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="select-option select-option-${id}" >
                                        ${select_option.join('')}
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-success btn-sm addOption">
                                            <i class="fas fa-plus"></i>
                                            Tambah Option
                                        </button>
                                    </div>
                                </div> `



            return `<div class="itemList">
                        <div class="row align-items-center ">
                            <div class="col-4">
                                <label class="form-label">Label</label>
                                <input
                                    type="text"
                                    value="${value}"
                                    id="label_${id}"
                                    name="label[${id}]"
                                    class="label_name form-control"
                                    placeholder="Masukan label" />
                                <input class="old_label" type="hidden" name="old_label[${id}]" value="${old_value}">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Tipe Input</label>
                                <select class="form-select" name="type_input[${id}]">
                                    ${Object.keys(type_input_list).map((item, key) => {
                                        return `<option value="${item}" ${item === type_input ? 'selected' : ''}>${type_input_list[item]}</option>`
                                    })}
                                </select>
                            </div>
                            <div class="col-1 mt-4 d-flex align-items-center gap-3">
                                <button type="button" class="btn btn-danger removeRow">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <div class="up-down-row">
                                    ${
                                        is_first ? '' : `<button type="button" class="btn btn-primary btn-sm upRow">
                                        <i class="fas fa-arrow-up"></i>`}
                                     ${
                                        is_last ? '' : `<button type="button" class="btn btn-primary btn-sm downRow">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>`}
                                </div>
                            </div>
                        </div>
                        ${list_option_comp}
                    </div>`
        }

        $('.listMenuCustom').on('change', 'select', function() {
            const value = $(this).val()
            const itemList = $(this).closest('.itemList')
            const id = itemList.find('.label_name').attr('id').split('_')?.[1] ?? 0
            const select_option = itemList.find(`.select-option-list`)
            if (value === 'select') {
                select_option.show()
            } else {
                select_option.hide()
            }
        })

        $('.listMenuCustom').on('click', '.select-option-list .addOption', function() {
            const parent_id = $(this).closest('.itemList').find('.label_name').attr('id').split('_')?.[1] ?? 0
            const selectOptionList = $(this).closest('.select-option-list')
            const selectOption = selectOptionList.find('.select-option')
            const last_child = selectOption.children().last()

            const last_child_id = last_child.find('.pilihan').attr('id').split('_')?.[1] ?? 0

            var itemList = last_child,
                clone = itemList.clone()

            clone.find('.pilihan').val('').attr('id', `pilihan_${parseInt(last_child_id) + 1}`).attr('name',
                    `pilihan[${parent_id}][${parseInt(last_child_id) + 1}]`)
                .removeClass(`pilihan_${parseInt(last_child_id)}`).addClass(
                    `pilihan_${parseInt(last_child_id) + 1}`)
            clone.find('.color_pilihan').val('').attr('id', `color_pilihan_${parseInt(last_child_id) + 1}`)
                .attr(
                    'name',
                    `color_pilihan[${parent_id}][${parseInt(last_child_id) + 1}]`)
                .removeClass(`color_pilihan_${parseInt(last_child_id)}`).addClass(
                    `color_pilihan_${parseInt(last_child_id) + 1}`)
            clone.find(`input[type="hidden"]`).val(parseInt(last_child_id) + 1).attr('name',
                `pilihan_id[${parent_id}][${parseInt(last_child_id) + 1}]`)

            selectOption.append(clone);
        })

        $('.listMenuCustom').on('click', '.select-option-list .removeOptionRow', function() {
            var itemList = $(this).parent().parent()
            itemList.remove()
        })

        $('.listMenuCustom').on('click', '.upRow', function() {
            collectDataValue()
            const id = $(this).closest('.itemList').find('.label_name').attr('id').split('_')?.[1] ?? 0;
            const index = dataInput.findIndex(item => item.id == id);
            let result_data = []

            const temp = dataInput[index]
            dataInput[index] = dataInput[index - 1]
            dataInput[index - 1] = temp

            listMenuCustom.html('')
            dataInput.map((item, key) => {
                const last_id = dataInput.length - 1
                item.select_option = item.select_option.length > 0 ? item.select_option : [{id: 1}]
                listMenuCustom.append(createRowMenuCustom(item?.label ?? '', item.type_input, key + 1, item
                    ?.select_option ?? [1], item?.editable ?? false, {
                        first: key == 0,
                        last: key == last_id
                    }, item?.old_label ?? '', item?.filter ?? false))
            })
        })

        $('.listMenuCustom').on('click', '.downRow', function() {
            collectDataValue()
            const id = $(this).closest('.itemList').find('.label_name').attr('id').split('_')?.[1] ?? 0;
            const index = dataInput.findIndex(item => item.id == id);
            let result_data = []

            const temp = dataInput[index]
            dataInput[index] = dataInput[index + 1]
            dataInput[index + 1] = temp

            listMenuCustom.html('')
            dataInput.map((item, key) => {
                const last_id = dataInput.length - 1
                item.select_option = item.select_option.length > 0 ? item.select_option : [{id: 1}]
                listMenuCustom.append(createRowMenuCustom(item?.label ?? '', item.type_input, key + 1, item
                    ?.select_option ?? [1], item?.editable ?? false, {
                        first: key == 0,
                        last: key == last_id
                    }, item?.old_label ?? '', item?.filter ?? false))
            })
        })

        function collectDataValue() {
            const listMenuCustom = $('.listMenuCustom')
            const data = listMenuCustom.find('.itemList').map((key, item) => {
                const itemList = $(item)
                const label_name = itemList.find('.label_name').val()
                const type_input = itemList.find('select').val()
                const id = itemList.find('.label_name').attr('id').split('_')?.[1] ?? 0
                const editable_pilihan = itemList.find('.editable_pilihan').prop('checked')
                const select_option = itemList.find(`.select-option-list`).find('.select-option').children().map(
                    (key, item) => {
                        const select_option_item = $(item)
                        const pilihan = select_option_item.find('.pilihan').val()
                        const color_pilihan = select_option_item.find('.color_pilihan').val()
                        // const editable_pilihan = select_option_item.find('.editable_pilihan').prop('checked')
                        const id = select_option_item.find('.pilihan').attr('id').split('_')?.[1] ?? 0
                        return {
                            label: pilihan,
                            color: color_pilihan,
                            // editable: editable_pilihan ?? false,
                            id: id
                        }
                    }).get()

                return {
                    label: label_name,
                    type_input: type_input,
                    select_option: select_option,
                    editable: editable_pilihan,
                    old_label: itemList.find('.old_label').val(),
                    id: id
                }
            }).get()

            dataInput = data

            return data
        }

        collectDataValue()
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
                        {{ $data ?? null ? 'Edit' : 'Buat' }} Menu Kustom
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Menu Kustom
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <form action="{{ route('custom-menu.store') }}" method="post">
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
                        Menu Kustom
                    </h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="row g-4 mb-4">
                        <div class="col-4">
                            <label class="form-label">Nama Menu</label>
                            <input type="text" value="{{ old('nama_menu', $data->name ?? '') }}"
                                class="form-control @error('nama_menu') is-invalid @enderror" name="nama_menu"
                                placeholder="Masukan nama menu" />
                            @error('nama_menu')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-4"></div>
                        <div class="col-4"></div>
                        <div class="listMenuCustom mt-4" style="display: flex; flex-direction: column; gap: 10px">

                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-primary addRow">
                                <i class="fas fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                        <div>
                            <h6 class="form-label">
                                Data yang ditampilkan :
                            </h6>
                            <label class="pb-2 pt-1">
                                Pilih Semua
                                <input type="checkbox" class="check-all-permission"
                                    style="height: 15px; width: 15px; border: 1px solid" value="">
                            </label>
                            <div class="">
                                <table id="list_show_data_menu">
                                    <tr class="">
                                        <td>
                                            <label class="d-flex align-items-center" style="gap: 10px">
                                                <input type="checkbox" id="label_updated_by"
                                                    style="height: 15px; width: 15px; border: 1px solid" name="label_show[]"
                                                    value="updated_by">
                                                Updated By
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td>
                                            <label class="d-flex align-items-center" style="gap: 10px">
                                                <input type="checkbox" id="label_created_by"
                                                    style="height: 15px; width: 15px; border: 1px solid" name="label_show[]"
                                                    value="created_by">
                                                Created By
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td>
                                            <label class="d-flex align-items-center" style="gap: 10px">
                                                <input type="checkbox" id="label_created_at"
                                                    style="height: 15px; width: 15px; border: 1px solid" name="label_show[]"
                                                    value="created_at">
                                                Created At
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td>
                                            <label class="d-flex align-items-center" style="gap: 10px">
                                                <input type="checkbox" id="label_updated_at"
                                                    style="height: 15px; width: 15px; border: 1px solid" name="label_show[]"
                                                    value="updated_at">
                                                Updated At
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="push">
                <a href="{{ route('custom-menu.index') }}" type="button" class="btn btn-sm btn-danger btn-delete-all">
                    <i class="fa fa-minus me-1"></i> Batalkan
                </a>
                <button type="button" class="btn btn-sm btn-success btn-save">
                    <i class="fa fa-check me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
    <!-- END Page Content -->
@endsection
