<style>
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th,
    .table td {
        border: 1px solid #000;
        padding: 4px;
        white-space: nowrap;
    }

    .table thead tr {
        background: #d9e7f2;
    }

    .str {
        mso-number-format: \@;
    }

    .bigheader {
        font-size: 30px;
        font-weight: bold;
        color: black;
        margin: 0px;
    }

    .subheader {
        font-size: 20px;
        margin: 0px;
    }

    .text-center {
        text-align: center;
    }
</style>
<table width="100%">
    <tr>
        <td align="left">
            <table style="padding:0; border-collapse:collapse;" width="100%">
                <tr>
                    <td align="left" width="100%">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<center style="margin-top: 18px">
    <h1 class="bigheader">Laporan Returan Paket</h1>
    {{-- <h2 class="subheader"></h2> --}}
</center>
<table class="table">
    <thead>
        <tr>
            <th>NO TRX</th>
            <th>TANGGAL INPUT</th>
            <th>NAMA TOKO</th>
            <th>NO PESANAN</th>
            <th>Customer_Id</th>
            <th>NO WHATSAPP</th>
            <th>NO RESI</th>
            <th>SKU</th>
            <th>ALASAN RETURAN</th>
            <th>DIBUAT PADA</th>
            <th>DIBUAT OLEH</th>
        <tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->no_trx }}</td>
                <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                <td>{{ $item->shop->name ?? '' }}</td>
                <td>{{ $item->no_pesanan }}</td>
                <td>{{ $item->customer }}</td>
                <td>{{ $item->no_whatsapp }}</td>
                <td>{{ $item->no_resi }}</td>
                <td>
                    @if ($item->sku_jumlah)
                        <table style="table-collapse: collapse">
                            <tr>
                                <th>SKU</th>
                                <th>Jumlah</th>
                            </tr>
                            @foreach (json_decode($item->sku_jumlah) as $key => $value)
                                <tr>
                                    <td>{{ $value->sku }}</td>
                                    <td>
                                        {{ $value->jumlah }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </td>
                <td>{{ $item->alasan_retur }}</td>
                <td>{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
                <td>{{ $item->createdBy->name ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
