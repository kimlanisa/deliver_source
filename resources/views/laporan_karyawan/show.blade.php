<div class="block block-rounded">
    <div class="block-header block-header-default">
        <div>
            <h3 class="block-title">
                DETAIL LAPORAN KARYAWAN
            </h3>
            <div class="text-black" style="font-size: 15px;">
                No Laporan Karyawan : {{ $data->no_laporan }}
            </div>
        </div>
        <div data-bs-dismiss="modal" style="cursor: pointer">
            <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="block-content block-content-full">
        @include('laporan_karyawan.show_body')
    </div>
</div>

<style>
    .content_pekerjaan {
        max-width: 100%;
    }

    .content_pekerjaan img {
        max-width: 100%;
    }
</style>
