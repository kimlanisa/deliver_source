<div class="block block-rounded">
    <div class="block-header block-header-default">
        <div>
            <h3 class="block-title">
                DETAIL RETURAN PAKET
            </h3>
            <div class="text-black" style="font-size: 15px;">
                No Returan Paket : {{ $data->no_trx }}
            </div>
        </div>
        <div data-bs-dismiss="modal" style="cursor: pointer">
            <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="block-content block-content-full">
        <div class="row g-4">
            <div class="col-4">
                <label class="form-label">Tanggal Input</label>
                <input class="form-control" value="{{ date('d-m-Y', strtotime($data->date)) }}" readonly />
            </div>
            <div class="col-4">
                <label class="form-label">Toko</label>
                <input class="form-control" value="{{ $data->shop->name }}" readonly />
            </div>
            <div class="col-4">
                <label class="form-label">No Pesanan</label>
                <input class="form-control" value="{{ $data->no_pesanan }}" readonly />
            </div>
            <div class="col-4">
                <label class="form-label">Customer_Id</label>
                <input class="form-control" value="{{ $data->customer }}" readonly />
            </div>
            <div class="col-4">
                <label class="form-label">No Whatsapp</label>
                <input class="form-control" value="{{ $data->no_whatsapp }}" readonly />
            </div>
            <div class="col-4">
                <label class="form-label">No Resi Inbound</label>
                <input class="form-control" value="{{ $data->no_resi }}" readonly />
            </div>
            <div class="col-4">
                <label class="form-label">Status</label>
                <input class="form-control" value="{{ $data->status }}" readonly />
            </div>
            <div class="col-4">
                <label class="form-label">Status 2</label>
                <input class="form-control" value="{{ $data->status2 }}" readonly />
            </div>
            @if($data->sku_jumlah)
            @foreach(json_decode($data->sku_jumlah) as $key => $value)
            <div class="row mt-3">
                <div class="col-4">
                    <label class="form-label">SKU</label>
                    <input class="form-control" value="{{ $value->sku }}" readonly />
                </div>
                <div class="col-4">
                    <label class="form-label">Jumlah</label>
                    <input class="form-control" value="{{ $value->jumlah }}" readonly />
                </div>
            </div>
            @endforeach
            @endif
            <div class="col-6">
                <label class="form-label">Alasan Returan</label>
                <textarea class="form-control" readonly>{{ $data->alasan_retur ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>
