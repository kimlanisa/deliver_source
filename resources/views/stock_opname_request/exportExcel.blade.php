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
    <h1 class="bigheader">Laporan Stock Opname Request</h1>
    {{-- <h2 class="subheader"></h2> --}}
</center>
<table class="table">
    <thead>
        <tr>
            <th>No TRX</th>
            <th>Tanggal</th>
            <th>SKU</th>
            <th>Minus</th>
            <th>Tambah</th>
            <th>Status</th>
            <th>DIBUAT PADA</th>
            <th>DIBUAT OLEH</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->no_trx }}</td>
            <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
            <td>{{ $item->sku }}</td>
            <td>{{ $item->minus }}</td>
            <td>{{ $item->plus ?? '' }}</td>
            <td>{{ $item->status == 1? 'ON PROSES' : 'DONE' }}</td>
            <td>{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
            <td>{{ $item->user->name ?? '' }}</td>
        </tr>
        @endforeach
        @if (count($data) == 0)
        <tr>
            <td colspan="8" class="text-center">Tidak ada data</td>
        </tr>
        @endif
    </tbody>
</table>
