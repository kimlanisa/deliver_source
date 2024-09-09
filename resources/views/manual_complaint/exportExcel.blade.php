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
    <h1 class="bigheader">Laporan Pusat Komplain Manual</h1>
    {{-- <h2 class="subheader"></h2> --}}
</center>
<table class="table">
    <thead>
        <tr>
            <th>No TRX</th>
            <th>Tanggal & Jam</th>
            <th>Nomor Pesanan</th>
            <th>CUSTOMER_ID</th>
            <th>No WA Customer</th>
            <th>Nama TOKO</th>
            <th>Keterangan Bermasalah</th>
            <th>No Resi Inbound</th>
            <th>Solusi</th>
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
            <td>{{ date('d-m-Y', strtotime($item->date_time)) }}</td>
            <td>{{ $item->no_pesanan }}</td>
            <td>{{ $item->customer }}</td>
            <td>{{ $item->no_whatsapp }}</td>
            <td>{{ $item->shop->name ?? '' }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->no_resi }}</td>
            <td>{{ $item->solution }}</td>
            <td>{{ $item->status == 1? 'ON PROSES' : 'DONE' }}</td>
            <td>{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
            <td>{{ $item->createdBy->name ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
