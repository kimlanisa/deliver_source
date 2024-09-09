<div class="block block-rounded">
    <div class="block-header block-header-default">
        <div>
            <h3 class="block-title">
                DETAIL PERMINTAAN REFUND
            </h3>
            <div class="text-black" style="font-size: 15px;">
                No Permintaan Refund : {{ $data->no_trx }}
            </div>
        </div>
        <div data-bs-dismiss="modal" style="cursor: pointer">
            <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="block-content block-content-full">
        <div class="row g-4">
            <div class="col-4">
                <label class="form-label">Tanggal</label>
                <input  class="form-control" value="{{ date('d-m-Y', strtotime($data->date)) }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">Toko</label>
                <input  class="form-control" value="{{ $data->shop->name ?? '-' }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">No Pesanan</label>
                <input  class="form-control" value="{{ $data->no_pesanan ?? '-' }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">Customer_Id</label>
                <input  class="form-control" value="{{ $data->customer ?? '-' }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">Nominal Refund</label>
                <input  class="form-control" value="{{ $data->nominal_refund ?? '-' }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">No Rekening</label>
                <input  class="form-control" value="{{ $data->no_rekening ?? '-' }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">Nama Bank</label>
                <input  class="form-control" value="{{ $data->nama_bank ?? '-' }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">Nama Pemilik Rekening</label>
                <input  class="form-control" value="{{ $data->nama_pemilik_rekening ?? '-' }}" readonly/>
            </div>
            <div class="col-12">
                <label class="form-label">Alasan Refund</label>
                <textarea
                    class="form-control" readonly
                    placeholder="Masukan alasan retur">{{ $data->alasan_refund ?? '' }}</textarea>
            </div>
            <div class="col-4">
                <label class="form-label">Lampiran Refund</label>
                <img
                    class="img-fluid"
                    src="{{ asset($data->lampiran_refund) }}"
                />
            </div>
        </div>
    </div>
</div>
