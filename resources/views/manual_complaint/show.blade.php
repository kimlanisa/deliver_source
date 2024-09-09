<div class="block block-rounded">
    <div class="block-header block-header-default">
        <div>
            <h3 class="block-title">
                DETAIL KOMPLAIN MANUAL
            </h3>
            <div class="text-black" style="font-size: 15px;">
                No Komplain Manual : {{ $data->no_trx }}
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
                <input  class="form-control" value="{{ date('d-m-Y', strtotime($data->date_time)) }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">Toko</label>
                <input  class="form-control" value="{{ $data->shop->name }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">No Pesanan</label>
                <input  class="form-control" value="{{ $data->no_pesanan }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">Customer_Id</label>
                <input  class="form-control" value="{{ $data->customer }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">No Whatsapp</label>
                <input  class="form-control" value="{{ $data->no_whatsapp }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">No Resi Inbound</label>
                <input  class="form-control" value="{{ $data->no_resi }}" readonly/>
            </div>
            <div class="col-4">
                <label class="form-label">Alasan</label>
                <input  class="form-control" value="{{ $data->alasan }}" readonly/>
            </div>
           @if ($data->alasan === 'Salah Packing')
           <div class="col-6">
                <label class="form-label">Remark</label>
                <textarea
                    class="form-control"  readonly>{{ $data->remark ?? '' }}</textarea>
            </div>
           @endif
            <div class="col-4">
                <label class="form-label">Solusi</label>
                <input  class="form-control" value="{{ $data->solution }}" readonly/>
            </div>
           @if($data->sku_jumlah)
           @foreach(json_decode($data->sku_jumlah) as $key => $value)
           <div class="row mt-3">
               <div class="col-4">
                   <label class="form-label">SKU Bermasalah {{ ++$key }}</label>
                   <input class="form-control" value="{{ $value->sku }}" readonly />
               </div>
               <div class="col-4">
                   <label class="form-label">Jumlah</label>
                   <input class="form-control" value="{{ $value->jumlah }}" readonly />
               </div>
           </div>
           @endforeach
           @endif
            <div class="col-12">
                <label class="form-label">Keteragan Bermasalah</label>
                <textarea
                    class="form-control"  readonly>{{ $data->keterangan ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>
