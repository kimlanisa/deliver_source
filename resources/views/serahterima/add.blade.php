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

        function scanTemp() {
            $.ajax({
                url: "{{ route('listTemp') }}",
                method: "GET",
                dataType: "json",
                async: false,
                success: function(response) {
                    var tbody = $('#tabel-data tbody');
                    tbody.empty(); // hapus data sebelumnya

                    $.each(response.data, function(index, item) {
                        var rows = `<tr class="">
                                        <td class="text-center">${index+1}</td>
                                        <td class="fs-sm no_resi_item">
                                            ${item.no_resi}
                                        </td>
                                        <td hidden class="expedisi_id_item">${item.expedisi_id}</td>
                                        <td class="d-none d-sm-table-cell">
                                            <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">${item.expedisi}</span>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                        <em class="fs-sm text-muted">${item.created_at}</em>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" data-id="${item.no_resi}" class="btn btn-sm btn-alt-secondary btn-delete" onclick="handlerDeleteDataById('${item.no_resi}')" id="${item.no_resi}">
                                                <i class="fa fa-fw fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>`

                        tbody.append(rows);
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function countItem() {

            const totScan = $('#tabel-data tbody tr').length;
            $("#count-item").text(totScan);
            $("#count-item1").text(totScan);

            // $.ajax({
            //     url: "/deliver/countItem",
            //     method: 'GET',
            //     dataType: 'json',
            //     async: false,
            //     success: function(response) {
            //         $("#count-item").text(response.total);
            //         $("#count-item1").text(response.total);
            //     },
            //     error: function(xhr, status, error) {
            //         var errorMessage = xhr.status + ': ' + xhr.statusText

            //     },
            // });


        }

        function deleteTemp() {
            $.ajax({
                url: "{{ url('/deleteTempAll') }}",
                method: 'DELETE',
                success: function() {
                    scanTemp();
                    countItem();
                    $('#expedisi_id').val('');
                    $('#no_resi').val('');
                },

            });
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
            const expedisiData = @json($expedisi);
            const expedisi_find = expedisiData.find((item) => item.id == expedisi_id);
            expedisi_find.prefix = JSON.parse(expedisi_find.prefix);

            if (expedisi_id == "") {
                initMessage('danger', 'fa fa-times', 'Pilih expedisi terlebih dahulu',1)
                return false;
            }

            if (no_resi == "") {
                initMessage('danger', 'fa fa-times', 'Masukan no resi!',1)
                return false;
            }

            if(expedisi_find.prefix?.length > 0) {
                if(!expedisi_find.prefix?.some(prefix => no_resi?.toLowerCase().includes(prefix?.toLowerCase()))) {
                    initMessage('danger', 'fa fa-times', 'No resi tidak sesuai dengan expedisi yang dipilih', 3)
                    return;
                }
            }

            if (arrDataResiTemp.includes(no_resi.toLowerCase())) {
                initMessage('danger', 'fa fa-times', 'No resi sudah ada!',1)
                return false;
            }

            $.ajax({
                url: "{{ route('scanBarcode') }}",
                method: "POST",
                data: {
                    no_resi
                },
                dataType: "json",
                async: false,
                success: function(response) {
                    if (response.success) {
                        // alert(response.message);
                        successSound();
                        $("#no_resi").val('');
                        $("#no_resi").focus();
                        // scanTemp();

                        arrDataResiTemp.push(no_resi?.toLowerCase())

                        const idxData = $('#tabel-data tbody tr').length;

                        $('#tabel-data tbody').prepend(`<tr id="${idxData}">
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
                                        <button type="button" class="btn btn-sm btn-alt-secondary btn-delete" onclick="handlerDeleteDataById('${idxData}', '${no_resi}')">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>`)

                        countItem();
                    } else {
                        initMessage('danger', 'fa fa-times', response.message, response.code)
                        return false;
                    }
                },
                error: function(xhr, status, error) {
                    initMessage('danger', 'fa fa-times', 'kesalahan pada server', 1)
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
                switch (code) {
                    case 0:
                        successSound();
                        break;
                    case 1:
                        errorSound();
                        break;
                    case 2:
                        blacklistSound();
                        break;
                    case 3:
                        salahExpedisiSound();
                        break;
                    default:
                        errorSound();
                        break;
                }
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
            var audioPath = "{{ asset('media/mp3/Sukses.mp3') }}";

            // Membuat objek audio
            var audio = new Audio(audioPath);

            // Memainkan suara notifikasi
            audio.play();
        }
        //end suara scan

        function salahExpedisiSound(){
            // Menentukan path file suara notifikasi
            var audioPath = "{{ asset('media/mp3/Salahexpedisi.mp3') }}";

            // Membuat objek audio
            var audio = new Audio(audioPath);

            // Memainkan suara notifikasi
            audio.play();
        }

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
            $(".btn-destroy").attr("data-id", idx);
            $(".btn-destroy").attr("data-resi", noResi);
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

            // $.ajax({
            //     url: `{{ url('/destroyTemp/${id}') }}`,
            //     method: 'DELETE',
            //     success: function() {
            //         $("#modal-delete").modal("hide")
            //         scanTemp();
            //         countItem();
            //         One.helpers('jq-notify', {
            //             type: 'danger',
            //             icon: 'fa fa-check me-1',
            //             message: 'Berhasil dihapus!'
            //         });
            //     },
            //     error: function(xhr, status, error) {
            //         var errorMessage = xhr.status + ': ' + xhr.statusText
            //         alert('gagal hapus!');
            //     },
            // });
        }

        const handlerModalDeleteDataAll = () => {

            $('#tabel-data tbody').empty()
            arrDataResiTemp = [];

            countItem();

            $("#modal-delete-all").modal("hide")
            initMessage('success', 'fa fa-check', 'Semua Data Berhasil dihapus!', 0)

            window.location.href = "{{ url('/serahterima') }}"

            // $.ajax({
            //     url: "{{ url('/deleteTempAll') }}",
            //     method: 'DELETE',
            //     success: function() {
            //         $("#modal-delete-all").modal("hide")
            //         scanTemp();
            //         countItem();
            //         $('#expedisi_id').val('');
            //         $('#no_resi').val('');
            //         One.helpers('jq-notify', {
            //             type: 'danger',
            //             icon: 'fa fa-check me-1',
            //             message: 'Semua Data Berhasil dihapus!'
            //         });
            //     },
            //     error: function(xhr, status, error) {
            //         var errorMessage = xhr.status + ': ' + xhr.statusText
            //         alert('gagal hapus!');
            //     },
            // });
        }

        // window.addEventListener("beforeunload", function(e) {
        //     // Cancel the event
        //     e.preventDefault();
        //     // Chrome requires the following statement to be returned.
        //     const chkBtn = $(".btn-store-all").attr('data-store');
        //     if (chkBtn == "false") {
        //         e.returnValue = '';
        //     }
        // });

        const handlerModalSaveData = () => {
            $(".btn-store-all").html('<option> Loading ...</option>');
            $(".btn-store-all").prop('disabled', true); // disable button

            const splitExpedisi = $('#expedisi_id').val().split(',')
            const expedisi_id = splitExpedisi[0];
            const catatan = $('#catatan').val();
            const serahTerimaId = $('#serahTerimaId').val();

            let scanDetails = [];
            console.log($('#tabel-data tbody tr').length);
            $('#tabel-data tbody tr').each(function() {
                const tr = $(this);
                scanDetails.push({
                    no_resi: tr.find('td:eq(1)').text(),
                    expedisi_id: tr.find('td:eq(2)').text(),
                    created_at: tr.find('td:eq(4) em').text()
                });
            });

            if(scanDetails.length == 0) {
                initMessage('danger', 'fa fa-times', 'Data resi kosong, silahkan isi data minimal 1 data', 1)
                return false;
            }

            // mengirimkan data ke server untuk disimpan
            $.ajax({
                type: 'POST',
                url: "{{ url('/serahterima') }}",
                data: {
                    expedisi_id: expedisi_id,
                    catatan: catatan,
                    serahTerimaId,
                    scanDetails: JSON.stringify(scanDetails),
                },
                beforeSend: function() {

                },
                success: function(response) {
                    if (response.status == 'success') {
                        $(".btn-store-all").attr('data-store', true);
                        initMessage('success', 'fa fa-check', response.message, 0)
                        setTimeout(() => location.href = "{{ url('/serahterima') }}", 200);
                    } else {
                        initMessage('danger', 'fa fa-times', response.message, 1)
                    }


                    // window.open('/tanda_terima_therm/'+ response.id, '_blank');
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
                        {{ $dataSerahTerima !== "" ? 'Tambah Serah Terima Paket ke Tanda Terima ' . $dataSerahTerima->no_tanda_terima : 'Buat Serah Terima Paket'}}
                    </h2>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Scan dan Kirim Paket
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="">Paket</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Scan dan Kirim
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

            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-4">
                        <label class="form-label">Expedisi</label>
                        <input type="hidden" name="serahTerimaId" id="serahTerimaId" value="{{ $dataSerahTerima == "" ? '' : $dataSerahTerima->id }}">
                        <select type="text" class="form-select" name="expedisi_id" id="expedisi_id" {{ $dataSerahTerima == "" ? '' : 'disabled' }}>
                            <option value="">--</option>
                            @foreach ($expedisi as $exp)
                                @if ($dataSerahTerima !== "")
                                    @if ($exp->id == $dataSerahTerima->expedisi_id)
                                        <option value="{{ $exp->id }},{{ $exp->color }}" selected>{{ $exp->expedisi }}</option>
                                    @else
                                        <option value="{{ $exp->id }},{{ $exp->color }}">{{ $exp->expedisi }}</option>
                                    @endif
                                @else
                                    <option value="{{ $exp->id }},{{ $exp->color }}">{{ $exp->expedisi }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5">
                        <label class="form-label fw-bolder">Scan Barcode</label>
                        <input type="text" class="form-control form-control-alt" id="no_resi" name="no_resi"
                            placeholder="Scan barcode/enter no resi.." onkeydown="handlerScanBarcode(event, this.value)">
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


                    <div class="col-9" style="margin-top:-40px;">
                        <label class="form-label">Catatan :</label>
                        <input type="text" class="form-control" id="catatan" name="catatan">
                    </div>
                </div>

            </div>
        </div>

         <div class="text-end push">
            <button type="button" class="btn btn-sm btn-danger btn-delete-all" onclick="handlerDeleteDataAll()">
                <i class="fa fa-minus me-1"></i> Batalkan
            </button>
            <button type="submit" class="btn btn-sm btn-success btn-save-all" onclick="handlerSaveDataAll()">
                <i class="fa fa-check me-1"></i> {{ $dataSerahTerima !== "" ? 'Update & Simpan Data' : 'Simpan Data & Kirim'}}
            </button>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Daftar Scan & Siap Kirim</h3>
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
                            <th class="d-none d-sm-table-cell" style="width: 15%;">EXPEDISI</th>
                            <th class="d-none d-sm-table-cell" style="width: 20%;">WAKTU SCAN</th>
                            <th style="width:15%;" class="text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr class="">
                                <td class="text-center">1</td>
                                <td class="fs-sm">
                                    <p class="fw-semibold mb-1">
                                        <a href="javascript:void(0);">00987UJJTR4</a>
                                    </p>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">JNT</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                <em class="fs-sm text-muted">November 28, 2018 08:43</em>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Remove Client" data-bs-original-title="Remove Client">
                                        <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-end push">
            <button type="button" class="btn btn-sm btn-danger btn-delete-all" onclick="handlerDeleteDataAll()">
                <i class="fa fa-minus me-1"></i> Batalkan
            </button>
            <button type="submit" class="btn btn-sm btn-success btn-save-all" onclick="handlerSaveDataAll()">
                <i class="fa fa-check me-1"></i> {{ $dataSerahTerima !== "" ? 'Update & Simpan Data' : 'Simpan Data & Kirim'}}
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
                        <button type="button" class="btn btn-sm btn-danger btn-destroy"
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
                        <button type="button" class="btn btn-sm btn-danger btn-destroy-all"
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
