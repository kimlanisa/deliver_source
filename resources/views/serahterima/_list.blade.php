@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">

    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    {{-- <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css')}}"> --}}

    <style>
        .db-fltr {
            cursor: pointer;
        }

        .db-fltr:hover {
            padding: 7px;
            border-radius: 10px;
            background: #eff6ff;
            font-weight: normal;
        }

        .active-fl {
            padding: 7px;
            border-radius: 10px;
            background: #eff6ff;
            font-weight: bold;
        }

        .searchData:focus {
            border-color: #dfe3ea !important;
            box-shadow: none !important;
        }

        .parent-loading {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            background: rgba(27, 27, 27, .541);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        .parent-loading .loading-custom {
            position: relative;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 20px;
            padding-right: 20px;
            border-radius: 10px;
            background: white;
            left: 50%;
            transform: translate(-50%, 0);
            z-index: 99999;
        }

        div[data-notify="container"] {
            z-index: 999999 !important;
        }
    </style>
@endsection

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>


    <!-- Page JS Plugins -->

    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.colVis.min.js') }}"></script>
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
            $("#initDataSerahTerima").DataTable({
                searching: false
            })

            $("#initDetailSerahTterima").DataTable({
                "lengthMenu": [
                    [-1],
                    ['all']
                ]
            })

            const lastFiltered = getDataLocalStorage();
            if (Object.keys(lastFiltered).length > 0) {
                if (lastFiltered.keywordSearch !== null) {

                    $("#searchBy").val(lastFiltered.searchBy).trigger('change');
                    const valueSearch = document.querySelector("#valueSearch");
                    valueSearch.value = lastFiltered.keywordSearch;
                    const event = new Event("keydown");
                    valueSearch.dispatchEvent(event);

                    handlerSetFilter(lastFiltered)
                } else {
                    handlerSetFilter(lastFiltered)
                }
            } else {
                handlerFilter('all');
            }
        });

        const handlerSetFilter = (lastFiltered) => {
            if (lastFiltered.type !== "range") {
                handlerFilter(lastFiltered.type);
            } else {
                if (lastFiltered.dateRange !== null) {
                    setTimeout(() => {
                        const fp = flatpickr("#rangeTanggal", {
                            onChange: function(selectedDates, dateStr, instance) {
                                if (dateStr.includes("to")) {
                                    ajaxRequestGetData(lastFiltered.type, dateStr,
                                        lastFiltered.keywordSearch, lastFiltered
                                        .searchBy)
                                    localStorage.setItem("items", JSON.stringify({
                                        type: lastFiltered.type,
                                        dateRange: dateStr.split(' to '),
                                        keywordSearch: lastFiltered
                                            .keywordSearch,
                                        searchBy: lastFiltered.searchBy
                                    }));
                                }
                            },
                        });

                        fp.setDate(lastFiltered.dateRange);
                        if (fp.input.value.includes("to")) {
                            ajaxRequestGetData(lastFiltered.type, fp.input.value, lastFiltered
                                .keywordSearch, lastFiltered.searchBy)
                        }
                    }, 800)
                    handlerFilter(lastFiltered.type);
                } else {
                    handlerFilter(lastFiltered.type);
                }
            }
        }

        const handlerFilter = (type) => {
            if (type === 'all') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filterAll").addClass("active-fl")
            }

            if (type === 'now') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filterNow").addClass("active-fl")
            }

            if (type === 'yesterday') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filterYesterday").addClass("active-fl")
            }

            if (type === '7') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filter7Days").addClass("active-fl")
            }

            if (type === '30') {
                removeClass();
                $("#rangeTanggalFl").hide('slow');
                $(".filter30Days").addClass("active-fl")
            }

            if (type === 'range') {
                removeClass();
                $("#rangeTanggalFl").show('slow');
                $(".filterRange").addClass("active-fl")
            }

            const keywordSearch = $("#valueSearch").val() == "" ? null : $("#valueSearch").val();
            const searchBy = $("#valueSearch").val() == "" ? null : $("#searchBy").val();
            // const searchType = $("#valueSearch").val() == "" ? null : $("#searchType").val();

            if (type !== 'range') {
                ajaxRequestGetData(type, null, keywordSearch, searchBy)
                localStorage.setItem("items", JSON.stringify({
                    type,
                    dateRange: null,
                    keywordSearch,
                    searchBy
                }));
            } else {
                flatpickr("#rangeTanggal", {
                    onChange: function(selectedDates, dateStr, instance) {
                        if (dateStr.includes("to")) {
                            ajaxRequestGetData(type, dateStr, keywordSearch, searchBy)
                            localStorage.setItem("items", JSON.stringify({
                                type,
                                dateRange: dateStr.split(' to '),
                                keywordSearch,
                                searchBy
                            }));
                        }
                    },
                });
            }
        }

        let expedisi_id = null;
        const ajaxRequestGetData = (type, dateStr, keyword, searchBy) => {
            $("#initDataSerahTerima > tbody").empty();
            $("#initDataSerahTerima > tbody").append(`
                <tr class="text-center">
                    <td colspan="7">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="spinner-border spinner-border-sm me-3" role="status" aria-hidden="true"></div>
                            <strong>Loading...</strong>
                        </div>
                    </td>
                </tr>
            `);
            setTimeout(() => {
                $.ajax({
                    url: "{{ route('getDataSerahTerima') }}",
                    method: "POST",
                    data: {
                        type,
                        dateStr,
                        keyword,
                        searchBy,
                        expedisi_id
                    },
                    beforeSend: function() {
                        $('#totalPaket').html(` <div class="d-flex align-items-center justify-content-center">
                                        <div class="spinner-border spinner-border-sm me-3" role="status" aria-hidden="true"></div>
                                        <strong>Loading...</strong>
                                    </div>`);
                        $("#initDataSerahTerima > tbody").empty();
                        $("#initDataSerahTerima > tbody").append(`
                        <tr class="text-center">
                                <td colspan="7">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="spinner-border spinner-border-sm me-3" role="status" aria-hidden="true"></div>
                                        <strong>Loading...</strong>
                                    </div>
                                </td>
                            </tr>
                        `);
                    },
                    dataType: "json",
                    success: function(response) {
                        initDataSerahTerima(response);
                        $('#totalPaket').text(response?.total_all_paket);
                    },
                    complete: function(response) {
                        if (response.responseJSON.data.length == 0) {
                            // response.responseJSON.data.total_all_paket
                            if ($.fn.DataTable.isDataTable('#initDataSerahTerima')) {
                                $('#initDataSerahTerima').DataTable().destroy();
                            }
                            $("#initDataSerahTerima > tbody").empty();
                            $("#initDataSerahTerima").DataTable({
                                searching: false
                            })
                        }
                    },
                });
            }, 1000);
        }

        const handlerSearchDataByClick = (event) => {
            const keyword = $("#valueSearch").val();
            const searchBy = $("#searchBy").val();
            // const searchType = $("#searchType").val();
            const classTime = document.querySelectorAll(".db-fltr");
            let isActiveTime = ""
            classTime.forEach(function(element, index) {
                if (element.classList.contains('active-fl')) {
                    isActiveTime += element.getAttribute('data-search')
                }
            })

            const dateRange = $("#rangeTanggal").val() == '' ? null : $("#rangeTanggal").val();

            ajaxRequestGetData(isActiveTime, dateRange, keyword, searchBy)
            localStorage.setItem("items", JSON.stringify({
                type: isActiveTime,
                dateRange: dateRange,
                keywordSearch: keyword,
                searchBy
            }));

        }


        const handlerSearchDataByKeyword = (event) => {
            const keyword = event.currentTarget.value;
            const searchBy = $("#searchBy").val();
            // const searchType = $("#searchType").val();
            const classTime = document.querySelectorAll(".db-fltr");
            let isActiveTime = ""
            classTime.forEach(function(element, index) {
                if (element.classList.contains('active-fl')) {
                    isActiveTime += element.getAttribute('data-search')
                }
            })

            const dateRange = $("#rangeTanggal").val() == '' ? null : $("#rangeTanggal").val();

            if (event.keyCode === 13) {
                event.preventDefault();
                ajaxRequestGetData(isActiveTime, dateRange, keyword, searchBy)
                localStorage.setItem("items", JSON.stringify({
                    type: isActiveTime,
                    dateRange: dateRange,
                    keywordSearch: keyword,
                    searchBy
                }));
            }

        }

        $('select[name="expedisi_id"]').on('change', function() {
            console.log('test');
            const keyword = $("#valueSearch").val();
            const searchBy = $("#searchBy").val();
            // const searchType = $("#searchType").val();
            const classTime = document.querySelectorAll(".db-fltr");
            let isActiveTime = ""
            classTime.forEach(function(element, index) {
                if (element.classList.contains('active-fl')) {
                    isActiveTime += element.getAttribute('data-search')
                }
            })

            const dateRange = $("#rangeTanggal").val() == '' ? null : $("#rangeTanggal").val();

            expedisi_id = $(this).val();

            event.preventDefault();
            ajaxRequestGetData(isActiveTime, dateRange, keyword, searchBy)
            localStorage.setItem("items", JSON.stringify({
                type: isActiveTime,
                dateRange: dateRange,
                keywordSearch: keyword,
                searchBy,
                expedisi_id: expedisi_id
            }));

        })

        const getCurrentDateTime = () => {
            const currentdate = new Date();
            return `${currentdate.getFullYear()}-${(((currentdate.getMonth() + 1) < 10) ? "0" : "") + (currentdate.getMonth() + 1)}-${(currentdate.getDate() < 10 ? "0" : "") + currentdate.getDate()}`;
        }

        const initDataSerahTerima = (response) => {

            if (response.data.length > 0) {

                if ($.fn.DataTable.isDataTable('#initDataSerahTerima')) {
                    $('#initDataSerahTerima').DataTable().destroy();
                }
                $("#initDataSerahTerima > tbody").empty();

                response.data.map((item, idx) => {
                    let str = "";
                    let str2 = "";
                    let str3 = "";
                    let str4 = "";
                    let str5 = "";
                    let str6 = "";
                    const isAkses = parseInt("{{ canPermission('Daftar Serah Terima.Full_Akses', true) ? 1 : 0 }}")
                    const peran = "{{ Auth::user()->role }}"
                    const dateNow = '{{ date("Y-m-d") }}';

                    // console.log({
                    //     isAkses,
                    //     dateNow: getCurrentDateTime(),
                    //     dateDb: item.created_at.split(' ')[0]
                    // })

                    let urlSerahTerimaShow = `{{ route('serahterima.show', ':id') }}`;
                    let urlSerahTerimaCreate = `{{ route('serahterima.create', ':id') }}`;
                    let urlPrintThermal = `{{ route('printTandaTerimaTerm', ':id') }}`;
                    let urlPrintA4 = `{{ route('printTandaTerima', ':id') }}`;
                    let exportExcel = `{{ route('exportById', ':id') }}`;

                    urlSerahTerimaShow = urlSerahTerimaShow.replace(':id', item.id);
                    urlSerahTerimaCreate = urlSerahTerimaCreate.replace(':id', `id=${item.id}`);
                    urlPrintThermal = urlPrintThermal.replace(':id', item.id);
                    urlPrintA4 = urlPrintA4.replace(':id', item.id);
                    exportExcel = exportExcel.replace(':id', item.id);

                    // if ($("#roleLogin").val() == 'admin') {
                    //     str += `<a href="javascript:void(0)" id="${item.id}"
                    //                 class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                    //                 data-bs-toggle="tooltip" title="Delete"><i
                    //                     class="fa fa-fw fa-times"></i>
                    //             </a>`;

                    //     str2 += `<a href="#" onclick="handlerUpdateCatatan(event, '${item.id}', '${item.catatan}')" class="dropdown-item">
                    //                 Edit Catatan
                    //             </a>`;

                    //     str3 += `<a href="#" onclick="handlerUpdateDetail(event, '${item.id}', '${item.no_tanda_terima}')" class="dropdown-item">
                    //                 Edit Detail Resi
                    //             </a>`;

                    //     str4 += `<a href="${urlSerahTerimaCreate}" class="dropdown-item" target="_BLANK">
                    //                 Tambah Detail
                    //             </a>`;
                    //     str5 += `<button class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-fw fa-pen"></i>
                    //             </button>`;
                    //     str6 +=
                    //         `<a href="${exportExcel}" id="${item.id}" target="_BLANK" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-show"><i class="fa fa-fw fa-file-excel"></i></a>`;

                    // } else {
                    //     str += `<a href="javascript:void(0)" id="${item.id}"
                    //                 class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                    //                 data-bs-toggle="tooltip" title="${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? 'Delete' : 'data is lock'}" style="${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? '' : 'pointer-events: none;background: #fca5a5;color:white; display: none;'}"><i
                    //                     class="fa fa-fw ${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? 'fa-times' : 'fa-lock'}"></i>
                    //             </a>`;

                    //     str2 += `<a href="#" onclick="handlerUpdateCatatan(event, '${item.id}', '${item.catatan}')" class="dropdown-item" style="${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? '' : 'pointer-events: none;background: #fca5a5;color:white; display: none;'}">
                    //                 ${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? 'Edit Catatan' : '<i class="fa fa-lock"></i> Edit Catatan is Lock'}
                    //             </a>`;

                    //     str3 += `<a href="#" onclick="handlerUpdateDetail(event, '${item.id}', '${item.no_tanda_terima}')" class="dropdown-item" style="${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? '' : 'pointer-events: none;background: #fca5a5;color:white'}">
                    //                 ${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? 'Edit Detail Resi' : '<i class="fa fa-lock"></i> Edit Detail Resi is Lock'}
                    //             </a>`;

                    //     str4 += `<a href="${urlSerahTerimaCreate}" class="dropdown-item" target="_BLANK" style="${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? '' : 'pointer-events: none;background: #fca5a5;color:white'}">
                    //                 ${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? 'Tambah Detail' : '<i class="fa fa-lock"></i> Tambah Detail is Lock'}
                    //             </a>`;
                    //     str5 += `<button style="${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? '' : 'pointer-events: none;background: #fca5a5;color:white; display:none;'}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-fw fa-pen"></i>
                    //             </button>`;
                    //     str6 +=
                    //         `<a href="${exportExcel}" id="${item.id}" style="${isAkses == 1 && item.created_at.split(' ')[0] == getCurrentDateTime() ? '' : 'pointer-events: none;background: #fca5a5;color:white; display:none;'} target="_BLANK" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-show"><i class="fa fa-fw fa-file-excel"></i></a>`;

                    // }

                    $("#initDataSerahTerima > tbody").append(`
                        <tr>
                            <td class="text-center">${idx + 1}</td>
                            <td class="text-center">
                                <a class="fw-semibold" href="${urlSerahTerimaShow}">${item.no_tanda_terima}</a>
                            </td>
                            <td class="text-center">
                                <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info fs-sm">${item.totalpaket}</span>
                            </td>
                            <td class="text-center">
                                <a href="${urlSerahTerimaShow}"><span class="badge" style="background: ${item.color == null ? '#2B4C99' : item.color};color:white">${item.expedisi}</span></a>
                            </td>
							<td>${item.name}</td>
                            <td class="text-center">${item.created_at}</td>
                            <td>${item.catatan == null ? '' : item.catatan}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="${urlSerahTerimaShow}" id="${item.id}"
                                        class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-show"><i
                                            class="fa fa-fw fa-eye"></i></a>
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-fw fa-print"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="${urlPrintThermal}"
                                                    target="_BLANK">Thermal</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="${urlPrintA4}"
                                                    target="_BLANK">A4</a>
                                            </li>
                                        </ul>
                                    </div>
                                    ${
                                        (isAkses == 1 || peran == 'admin' || ((peran == 'user') || isAkses == 0) && item?.created_at?.split(' ')?.[0] == dateNow) ? `<div class="dropdown">
                                            <button class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-fw fa-pen"></i></button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="${urlSerahTerimaCreate}" class="dropdown-item" target="_BLANK">
                                                        Tambah Detail
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" onclick="handlerUpdateCatatan(event, '${item.id}', '${item.catatan}')" class="dropdown-item">
                                                        Edit Catatan
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>


                                        <a href="${exportExcel}" id="${item.id}" target="_BLANK" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-show"><i class="fa fa-fw fa-file-excel"></i></a>
                                        <a href="javascript:void(0)" id="${item.id}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                            data-bs-toggle="tooltip" title="Delete"><i
                                                class="fa fa-fw fa-times"></i>
                                        </a>`
                                        : ''
                                    }
                                </div>
                            </td>
                        </tr>
                    `);
                })

                $("#initDataSerahTerima").DataTable({
                    searching: false
                })
            }
        }

        const handlerUpdateCatatan = (event, id, catatan) => {
            $("#modal-updateCatatan").modal('show');
            $("#update_id").val(id);
            $("#catatanEdit").html(catatan == 'null' ? '' : catatan);
        }

        const handlerUpdateDetail = (event, serahTerimaId, noTandaTerima) => {
            $("#modal-editDetail").modal('show');
            $("#modal-editDetail .modal-title").html(`Edit Detail No Tanda Terima ${noTandaTerima}`);
            $(".btn-update-detail").attr('data-id', serahTerimaId);

            let urlGetDetailSerahTerima = `{{ route('getDetailSerahTerimaById', ':id') }}`;
            urlGetDetailSerahTerima = urlGetDetailSerahTerima.replace(':id', serahTerimaId);

            $.ajax({
                url: `${urlGetDetailSerahTerima}`,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    $("#initDetailSerahTterima > tbody").empty();
                    if (response.length > 0) {
                        if ($.fn.DataTable.isDataTable('#initDetailSerahTterima')) {
                            $('#initDetailSerahTterima').DataTable().destroy();
                        }
                        response.map((item, index) => {
                            const colorExpedisi = item.color == null ? '#2B4C99' : item.color;

                            $("#initDetailSerahTterima > tbody").append(`
                                <tr class="text-center">
                                    <td>
                                        <input type='checkbox' class='check-item' name='check-item' id='check-item' width='20' onchange="handlerChangeByOne(event)" style="transform: scale(1.5)" value="${item.no_resi}"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="noResiTerbaru" id="noResiTerbaru${item.no_resi}" value="${item.no_resi}" disabled/>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: ${colorExpedisi};color:white">${item.expedisi}</span>
                                    </td>
                                    <td>${item.created_at}</td>
                                </tr>
                            `);
                        })

                        $("#initDetailSerahTterima").DataTable({
                            "lengthMenu": [
                                [-1],
                                ['all']
                            ]
                        })
                    } else {
                        $("#initDetailSerahTterima > tbody").append(`
                            <tr>
                                <td colspan="4">
                                    <div class="alert alert-danger text-center"><h1>Data Kosong</h1></div
                                </td>
                            </tr>
                        `);
                    }
                },
            });

        }

        function checkAll(e) {
            var checkboxes = $("input[name='check-item']");
            if (e.checked) {
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
                        checkboxes[i].checked = true;
                        $(`#noResiTerbaru${checkboxes[i].value}`).prop('disabled', false)
                    }
                }
            } else {
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
                        checkboxes[i].checked = false;
                        $(`#noResiTerbaru${checkboxes[i].value}`).prop('disabled', true)
                    }
                }
            }
        }

        const handlerChangeByOne = (event) => {
            const value = event.currentTarget.value;
            if (event.currentTarget.checked) {
                $(`#noResiTerbaru${value}`).prop('disabled', false)
            } else {
                $(`#noResiTerbaru${value}`).prop('disabled', true)
            }
        }

        const handlerRequestUpdateDataDetail = (event) => {
            const dataChecked = $('input[name*="check-item"]').map(function() {
                if (this.checked == true && !this.disabled) {
                    return this.value
                }
            }).get()

            const dataNoResi = $('input[name*="noResiTerbaru"]').map(function() {
                if (!this.disabled) {
                    return this.value
                }
            }).get()

            if (dataChecked.length == 0) {
                One.helpers('jq-notify', {
                    type: 'danger',
                    icon: 'fa fa-check me-1',
                    message: 'Pilih minimal 1 data yang akan diupdate!'
                });
                return false;
            }

            const fixData = dataChecked.map((item, index) => {
                return {
                    resiId: item,
                    newResi: dataNoResi[index]
                }
            })

            $.ajax({
                url: "{{ route('updateDetail') }}",
                method: "POST",
                data: {
                    data: fixData
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        $("#modal-editDetail").modal('hide');
                        $("#initDetailSerahTterima > tbody").empty();
                        One.helpers('jq-notify', {
                            type: 'success',
                            icon: 'fa fa-check me-1',
                            message: 'Success update!'
                        });
                        setTimeout(() => window.location.reload(), 500)
                    } else {
                        One.helpers('jq-notify', {
                            type: 'danger',
                            icon: 'fa fa-check me-1',
                            message: 'Gagal update!'
                        });
                    }
                },
            });
        }


        const handlerRequestUpdateCatatan = (event) => {
            const id = $("#update_id").val();
            const catatan = $("#catatanEdit").val();

            $.ajax({
                url: "{{ route('updateCatatan') }}",
                method: "POST",
                data: {
                    id,
                    catatan
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        One.helpers('jq-notify', {
                            type: 'success',
                            icon: 'fa fa-check me-1',
                            message: 'Success update!'
                        });
                        window.location.reload();
                    } else {
                        One.helpers('jq-notify', {
                            type: 'danger',
                            icon: 'fa fa-check me-1',
                            message: 'Gagal update!'
                        });
                    }
                },
            });
        }

        const removeClass = () => {
            $(".filterAll").removeClass("active-fl")
            $(".filterNow").removeClass("active-fl")
            $(".filterYesterday").removeClass("active-fl")
            $(".filter7Days").removeClass("active-fl")
            $(".filter30Days").removeClass("active-fl")
            $(".filterRange").removeClass("active-fl")
            $("#rangeTanggal").val("")
        }

        const handlerExportToExcel = (event) => {
            const keyword = $("#valueSearch").val() == "" ? null : $("#valueSearch").val()
            const searchBy = $("#valueSearch").val() == "" ? null : $("#searchBy").val();
            const searchType = $("#valueSearch").val() == "" ? null : $("#searchType").val();
            const dateRange = $("#rangeTanggal").val() == '' ? null : $("#rangeTanggal").val();

            const classTime = document.querySelectorAll(".db-fltr");
            let isActiveTime = ""
            classTime.forEach(function(element, index) {
                if (element.classList.contains('active-fl')) {
                    isActiveTime += element.getAttribute('data-search')
                }
            })

            const baseUrl = "{{ url('/') }}";


            window.open(baseUrl +
                `/export?type=${isActiveTime}&dateStr=${dateRange}&keyword=${keyword}&searchBy=${searchBy}&searchType=${searchType}`,
                '_blank');

            // $.ajax({
            //     url: "{{ route('export') }}",
            //     method: "POST",
            //     data: {
            //         type: isActiveTime,
            //         dateStr: dateRange,
            //         keyword,
            //         searchBy,
            //         searchType
            //     },
            //     beforeSend: function() {
            //         $(".parent-loading").show();
            //     },
            //     dataType: "json",
            //     success: function(response) {
            //         console.log(response);
            //     },
            //     complete: function(response) {
            //         $(".parent-loading").hide();
            //     },
            // });
        }


        $('body').on("click", ".btn-delete", function() {
            var id = $(this).attr("id");
            $(".btn-destroy").attr("id", id);

            $("#modal-delete").modal("show");
        });

        $(".btn-destroy").on("click", function() {
            var id = $(this).attr("id")
            console.log(id);
            $.ajax({
                url: `{{ url('/serahterima/${id}') }}`,
                method: 'DELETE',
                success: function() {
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

        $('body').on("click", ".btn-delete-anggota", function() {
            var id = $(this).attr("id");
            $(".btn-destroy-anggota").attr("id", id);

            $("#modal-delete-anggota").modal("show");
        });

        const getDataLocalStorage = () => {
            let items = [];

            if (localStorage.getItem("items")) {
                items = JSON.parse(localStorage.getItem("items"));
            }
            return Object.assign({}, items);
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
                        Serah Terima
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Daftar Serah Terima Paket
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Serah Terima
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

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Filter
                </h3>
                <input type="hidden" name="roleLogin" id="roleLogin" value="{{ Auth::user()->role }}">
                @if (canPermission('Daftar Serah Terima.Create'))
                    <a href="{{ route('serahterima.create') }}" class="btn btn-sm btn-alt-secondary"><i
                            class="fa fa-plus text-info me-1"></i>Serah Terima Baru</a>
                @endif
            </div>
            <div class="block-content block-content-full">
                <div class="container">
                    <div class="d-flex align-items-center justify-content-start mb-3">
                        <div style="width: 15%" class="fw-bold">Waktu</div>
                        <div style="width: 85%">
                            <div class="d-flex align-items-center mt-3">
                                <span class="db-fltr fs-sm me-4 filterAll" data-search="all"
                                    onclick="handlerFilter('all')">Semua</span>
                                <span class="db-fltr fs-sm me-4 filterNow" data-search="now"
                                    onclick="handlerFilter('now')">Hari
                                    Ini</span>
                                <span class="db-fltr fs-sm me-4 filterYesterday" data-search="yesterday"
                                    onclick="handlerFilter('yesterday')">Kemarin</span>
                                <span class="db-fltr fs-sm me-4 filter7Days" data-search="7" onclick="handlerFilter('7')">7
                                    Hari</span>
                                <span class="db-fltr fs-sm me-4 filter30Days" data-search="30"
                                    onclick="handlerFilter('30')">30
                                    Hari</span>
                                <span class="db-fltr fs-sm me-4 filterRange" data-search="range"
                                    onclick="handlerFilter('range')">Range
                                    Tanggal</span>
                                <div id="rangeTanggalFl" style="display: none">
                                    <input type="text" class="js-flatpickr form-control" id="rangeTanggal"
                                        name="rangeTanggal" placeholder="Select Date Range" data-mode="range"
                                        data-date-format="Y-m-d">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="d-flex align-items-center justify-content-start mt-3">
                        <div style="width: 15%" class="fw-bold">Nama Toko</div>
                        <div style="width: 85%">
                            <div class="d-flex align-items-center">
                                <div style="width: 60%">
                                    <select class="form-select" name="expedisi_id">
                                        <option value="">Pilih Nama Ekspedisi</option>
                                        @php
                                            $shops = \App\Models\Expedisi::orderBy('expedisi', 'asc')->get();
                                        @endphp
                                        @foreach ($shops as $item)
                                            <option value="{{ $item->id }}">{{ $item->expedisi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="d-flex align-items-center justify-content-start mt-3">
                        <div style="width: 15%" class="fw-bold">Pencarian</div>
                        <div style="width: 85%">
                            <div class="d-flex align-items-center">
                                <div style="width: 20%">
                                    <select name="searchBy" id="searchBy" class="form-control searchData"
                                        style="border-right: 0;border-top-right-radius:0;border-bottom-right-radius:0;cursor:pointer">
                                        <option value="1">No Tanda Terima</option>
                                        <option value="2">Expedisi</option>
                                        <option value="3">No Resi</option>
                                    </select>
                                </div>
                                <div style="width: 30%">
                                    <input type="text" name="valueSearch" id="valueSearch"
                                        class="form-control searchData w-100"
                                        style="border-left: 0;border-top-left-radius:0;border-bottom-left-radius:0;"
                                        placeholder="Search keywoard data..."
                                        onkeydown="handlerSearchDataByKeyword(event)">
                                </div>
                                <div style="width: 20%">
                                    <button type="submit" data-toggle="layout-cari"
                                        onclick="handlerSearchDataByClick(event)">
                                        <i class="fa fa-fw fa-search"></i></button>
                                </div>
                                {{-- <div style="width: 20%">
                                    <select name="searchType" id="searchType" class="form-control searchData"
                                        style="border-left: 0;border-top-left-radius:0;border-bottom-left-radius:0;cursor:pointer">
                                        <option value="1">Spesifik</option>
                                        <option value="2">Samar</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-rounded mt-4">
            <div class="block-header block-header-default">
                <div class="d-flex align-items-center" style="gap: 10px">
                    <h3 class="block-title">
                        DAFTAR SERAH TERIMA
                    </h3>
                    <div style="margin-left 50px">
                        <span style="font-weight: 500">Total Paket :</span>
                        <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info fs-sm" id="totalPaket">0</span>
                    </div>
                </div>


                <button type="button" onclick="handlerExportToExcel(event)" class="btn btn-alt-success btn-md"><i
                        class="fas fa-file-excel"></i>
                    Excel</button>

            </div>


            <div class="block-content block-content-full">
                @include('layouts._message')

                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter fs-sm" id="initDataSerahTerima">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">#</th>
                                <th style="width: 200px;">No Tanda Terima</th>
                                <th>Jumlah Paket</th>
                                <th>Nama Expedisi</th>
                                <th>Created By</th>
                                <th>Waktu Dibuat</th>
                                <th>Catatan</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>



    <!--modal update catatan-->
    <div class="modal fade" id="modal-updateCatatan" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Update Catatan</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <span id="form_result"></span>
                        <div class="form-floating mb-4">
                            <input hidden required="" autocomplete="off" type="text" class="form-control"
                                id="update_id" name="update_id">
                            <textarea class="form-control" id="catatanEdit" name="catatanEdit" rows="5" cols="3"></textarea>
                            <label for="example-text-input-floating">Catatan</label>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-primary"
                            onclick="handlerRequestUpdateCatatan(event)">Update</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--modal edit detail-->
    <div class="modal fade" id="modal-editDetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="table-responsive">
                            <table class="table table-striped" width="100%" id="initDetailSerahTterima">
                                <thead>
                                    <tr class="bg-primary text-white text-center">
                                        <td width="10%">
                                            <input type="checkbox" id="check-all-pilih" width="20"
                                                style="transform: scale(1.5)" onchange="checkAll(this)" />
                                        </td>
                                        <td width="20%"><strong>No Resi</strong></td>
                                        <td width="10%"><strong>Nama Expedisi</strong></td>
                                        <td width="10%"><strong>Waktu Scan</strong></td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-update-detail"
                        onclick="handlerRequestUpdateDataDetail(event)">Update</button>
                </div>
            </div>
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
                        <button type="button" class="btn btn-sm btn-danger btn-destroy">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Small Block Modal -->
@endsection
