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

    <script>
        let arrDataResiTemp = [];

        $(document).ready(function() {
            // deleteTemp();
            arrDataResiTemp = [];
            // scanTemp();
            countItem();
        });

        function countItem() {

            const totScan = $('#tabel-data tbody tr').length;
            $("#count-item").text(totScan);
            $("#count-item1").text(totScan);

        }

        function handlerScanBarcode(event, value) {
            const no_resi = value;

            //keyCode === 13 when enter in keyboard, keyCode === 9 when tab scan using tool scan
            if (event.keyCode === 13 || event.keyCode === 9) {
                event.preventDefault();
                ajaxRequestScanBarcode(no_resi)
            }
        }

        const ajaxRequestScanBarcode = (no_resi) => {

            const splitExpedisi = $('#expedisi_id').val().split(',')
            const expedisi_id = splitExpedisi[0];
            const expedisi_txt = $('#expedisi_id').children("option").filter(":selected").text();
            // const splitShop = $('#shop_id').val().split(',')
            // const shop_id = splitShop[0];
            // const shop_txt = $('#shop_id').children("option").filter(":selected").text();

            if (expedisi_id == "") {
                initMessage('danger', 'fa fa-times', 'Pilih expedisi terlebih dahulu', 1)
                return false;
            }

            // if(shop_id == "") {
            //     initMessage('danger', 'fa fa-times', 'Pilih toko terlebih dahulu', 1)
            //     return false;
            // }

            if (no_resi == "") {
                initMessage('danger', 'fa fa-times', 'Masukan no resi!', 1)
                return false;
            }

            if (arrDataResiTemp.includes(no_resi)) {
                initMessage('danger', 'fa fa-times', 'No resi sudah ada!', 1)
                return false;
            }

            $.ajax({
                url: "{{ route('inbound-retur.scanBarcode') }}",
                method: "POST",
                data: {
                    no_resi
                },
                dataType: "json",
                async: false,
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        successSound();
                        $("#no_resi").val('');
                        $("#no_resi").focus();

                        arrDataResiTemp.push(no_resi)

                        const idxData = $('#tabel-data tbody tr').length;

                        // <td hidden class="shop_id_item">${shop_id}</td>
                        // <td class="d-none d-sm-table-cell">
                        //     <span class="badge" style="background: ${splitShop[1] == "" ? '#2B4C99' : splitShop[1]};color:white">${shop_txt}</span>
                        // </td>

                        $('#tabel-data tbody').append(`<tr id="${idxData}">
                                <td class="text-center">${idxData + 1}</td>
                                <td class="fs-sm no_resi_item">${no_resi}</td>
                                <td hidden class="expedisi_id_item">${expedisi_id}</td>
                                <td class="d-none d-sm-table-cell">
                                    <span class="badge" style="background: ${splitExpedisi[1] == "" ? '#2B4C99' : splitExpedisi[1]};color:white">${expedisi_txt}</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <em class="fs-sm text-muted">${getCurrentDateTime()}</em>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-alt-secondary btn-delete-blacklist" onclick="handlerDeleteDataById('${idxData}', '${no_resi}')">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>`)

                        countItem();
                    } else if(response.type === 'blacklist') {
                        initMessage('danger', 'fa fa-times', 'No resi sudah terdaftar ke blacklist!', 2)
                        return false;
                    } else {
                        initMessage('danger', 'fa fa-times', 'No resi sudah ada!', 1)
                        return false;
                    }
                },
                error: function(xhr, status, error) {
                    initMessage('danger', 'fa fa-times', 'Kegagalan pada server', 1)
                    return false;
                }
            });
        }

        const getCurrentDateTime = () => {
            const currentdate = new Date();
            return `${currentdate.getFullYear()}-${(((currentdate.getMonth() + 1) < 10) ? "0" : "") + (currentdate.getMonth() + 1)}-${(currentdate.getDate() < 10 ? "0" : "") + currentdate.getDate() } ${currentdate.getHours()}:${currentdate.getMinutes()}:${currentdate.getSeconds()}`;
        }

        const initMessage = (type, icon, message, code) => {
            if (type == 'danger') {
                code == 1 ? errorSound() : code == 2 ? blacklistSound() : successSound()
            }
            One.helpers('jq-notify', {
                type: type,
                icon: `${icon} me-1`,
                message: message
            });
            $("#no_resi").val('');
            $("#no_resi").focus();
        }

        //fungsi suara mp3 success scan
        function successSound() {
            // Menentukan path file suara notifikasi
            var audioPath = "{{ asset('media/mp3/beep-success.wav') }}";

            // Membuat objek audio
            var audio = new Audio(audioPath);

            // Memainkan suara notifikasi
            audio.play();
        }
        //end suara scan

        //fungsi suara mp3 success scan
        function errorSound() {
            // Menentukan path file suara notifikasi
            var audioPath = "{{ asset('media/mp3/beep-error.mp3') }}";

            // Membuat objek audio
            var audio = new Audio(audioPath);

            // Memainkan suara notifikasi
            audio.play();
        }
        //end suara scan

        //fungsi suara mp3 success scan
        function blacklistSound() {
            // Menentukan path file suara notifikasi
            var audioPath = "{{ asset('media/mp3/blacklist.mp3') }}";

            // Membuat objek audio
            var audio = new Audio(audioPath);

            // Memainkan suara notifikasi
            audio.play();
        }
        //end suara scan

        const handlerDeleteDataById = (idx, noResi) => {
            $(".btn-destroy-blacklist").attr("data-id", idx);
            $(".btn-destroy-blacklist").attr("data-resi", noResi);
            $("#modal-delete").modal("show");
        }

        const handlerDeleteDataAll = () => {
            $("#modal-delete-all").modal("show");
        }

        const handlerSaveDataAll = () => {
            $("#modal-save-all").modal("show");
        }

        const handlerModalDeleteDataById = (event) => {
            const idx = event.currentTarget.getAttribute('data-id');
            const noResi = event.currentTarget.getAttribute('data-resi');

            const filterData = arrDataResiTemp.filter((item) => item != noResi)
            arrDataResiTemp = [];
            filterData.map((data) => arrDataResiTemp.push(data))

            $(`table#tabel-data tbody tr#${idx}`).remove();

            $("#tabel-data tbody tr").each(function(i, v) {
                let currentIdx = $(this).find("td:eq(0)")
                let noResiTable = $(this).find("td:eq(1)").text();
                let btnDelete = $(this).find("td:eq(5) div button[type='button']")

                let trid = $(this).closest('tr');

                trid.attr('id', i)
                currentIdx.html(i + 1)
                btnDelete.attr('onclick', `handlerDeleteDataById('${i}', '${noResiTable}')`)
            });
            countItem();

            $("#modal-delete").modal("hide")
            initMessage('success', 'fa fa-check', 'Berhasil dihapus!', 0)
        }

        const handlerModalDeleteDataAll = () => {

            $('#tabel-data tbody').empty()
            arrDataResiTemp = [];

            countItem();

            $("#modal-delete-all").modal("hide")
            initMessage('success', 'fa fa-check', 'Semua Data Berhasil dihapus!', 0)
            window.location.href = "{{ route('inbound-retur.index') }}";
        }

        // window.addEventListener("beforeunload", function(e) {
        //     // Cancel the event
        //     e.preventDefault();
        //     // Chrome requires the following statement to be returned.
        //     const chkBtn = $(".btn-store-all").attr('data-store');
        //     console.log(chkBtn);
        //     if (chkBtn == "false") {
        //         e.returnValue = '';
        //     }
        // });

        const handlerModalSaveData = () => {
            const splitExpedisi = $('#expedisi_id').val().split(',')
            const expedisi_id = splitExpedisi[0];
            const catatan = $('#catatan').val();
            $(".btn-store-all").prop('disabled', true);
            $(".btn-store-all").html('Loading...');
            // const serahTerimaId = $('#serahTerimaId').val();

            let items = [];
            $('#tabel-data tbody tr').each(function() {
                const tr = $(this);
                items.push({
                    no_resi: tr.find('td:eq(1)').text(),
                    // shop_id: tr.find('td:eq(2)').text(),
                    expedisi_id: tr.find('td:eq(3)').text(),
                    created_at: tr.find('td:eq(4) em').text()
                });
            });

            if(items.length == 0) {
                initMessage('danger', 'fa fa-times', 'Data resi kosong, silahkan isi data minimal 1 data', 1)
                return false;
            }

            // mengirimkan data ke server untuk disimpan
            $.ajax({
                type: 'POST',
                url: "{{ route('inbound-retur.store') }}",
                data: {
                    expedisi_id: expedisi_id,
                    catatan: catatan,
                    // serahTerimaId,
                    items: items,
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $(".btn-store-all").attr('data-store', true);
                        initMessage('success', 'fa fa-check', response.message, 0)
                        setTimeout(() => location.href = "{{ route('inbound-retur.index') }}", 1000);
                    } else {
                        initMessage('danger', 'fa fa-times', response.message, 1)
                    }

                },
                error: function(xhr, status, error) {
                    initMessage('danger', 'fa fa-times', xhr.responseText, 1)
                }
            });
        }
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
                <div class="flex-grow-1">
                    <h2 class="h3 fw-bold mb-2">
                        Buat Inbound Retur
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Scan Inbound Retur
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Scan Inbound Retur
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
                    Scan
                </h3>
            </div>

            <div class="block-content block-content-full pb-5">
                <div class="row align-items-start">
                    <div class="row col-md-9">
                        <div class="col-6">
                            <label class="form-label">Expedisi</label>
                            <input type="hidden" name="serahTerimaId" id="serahTerimaId" value="">
                            <select type="text" class="form-select" name="expedisi_id" id="expedisi_id">
                                <option value="">--</option>
                                @foreach ($expedisi as $exp)
                                    <option value="{{ $exp->id }},{{ $exp->color }}">{{ $exp->expedisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bolder">Scan Barcode</label>
                            <input type="text" class="form-control form-control-alt" id="no_resi" name="no_resi"
                                placeholder="Scan barcode/enter no resi.." onkeydown="handlerScanBarcode(event, this.value)">
                        </div>
                        {{-- <div class="col-6 mt-4">
                            <label class="form-label">Toko</label>
                            <input type="hidden" name="serahTerimaId" id="serahTerimaId" value="">
                            <select type="text" class="form-select" name="shop_id" id="shop_id">
                                <option value="">--</option>
                                @foreach ($shop as $sp)
                                    <option value="{{ $sp->id }},{{ $sp->color }}">{{ $sp->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                    <div class="col-3 text-center">
                        <!--<label class="text-muted">Terscan</label>-->
                        <h1 class="fw-bold text-center rounded" id="count-item"
                            style="font-size: 6.25rem;
                            margin-bottom:-15px;
                            background-color: green;
                            font-family: auto;
                            color: white;">
                            0</h1>
                    </div>
                </div>

            </div>
        </div>

         <div class="text-end push">
            <button type="button" class="btn btn-sm btn-danger btn-delete-all" onclick="handlerDeleteDataAll()">
                <i class="fa fa-minus me-1"></i> Batalkan
            </button>
            <button type="submit" class="btn btn-sm btn-success btn-save-all" onclick="handlerSaveDataAll()">
                <i class="fa fa-check me-1"></i> Simpan Data
            </button>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Daftar Scan</h3>
                <div class="block-options">
                    <div class="block-options-item">
                        Jumlah Item : <span
                            class="fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                            id="count-item1">0</span>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <table class="js-table-checkable table table-hover table-vcenter js-table-checkable-enabled"
                    id="tabel-data">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 70px;">
                                #
                            </th>
                            <th>NO RESI</th>
                            <th hidden></th>
                            {{-- <th class="d-none d-sm-table-cell" style="width: 15%;">TOKO</th> --}}
                            <th class="d-none d-sm-table-cell" style="width: 15%;">EXPEDISI</th>
                            <th class="d-none d-sm-table-cell" style="width: 20%;">WAKTU SCAN</th>
                            <th style="width:15%;" class="text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="text-end push">
            <button type="button" class="btn btn-sm btn-danger btn-delete-all" onclick="handlerDeleteDataAll()">
                <i class="fa fa-minus me-1"></i> Batalkan
            </button>
            <button type="submit" class="btn btn-sm btn-success btn-save-all" onclick="handlerSaveDataAll()">
                <i class="fa fa-check me-1"></i> Simpan Data
            </button>
        </div>

    </div>
    <!-- END Page Content -->


    <!-- modal delete temp scan-->
    <div class="modal" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Hapus Data</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <h5>Yakin akan menghapus data?</h5>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-danger btn-destroy-blacklist"
                            onclick="handlerModalDeleteDataById(event)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Small Block Modal -->


    <!-- modal delete all temp scan-->
    <div class="modal" id="modal-delete-all" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Hapus Semua Data Scan</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <h5>Yakin akan membatalkan dan menghapus semua data scan?</h5>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-danger btn-destroy-all-blacklist"
                            onclick="handlerModalDeleteDataAll()">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Small Block Modal -->

    <!-- modal konfirmasi simpan data-->
    <div class="modal" id="modal-save-all" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Simpan semua data scan</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <h5>Yakin menyimpan semua data hasil scan?</h5>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-danger btn-store-all" data-store="false"
                            onclick="handlerModalSaveData()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Small Block Modal -->
@endsection
