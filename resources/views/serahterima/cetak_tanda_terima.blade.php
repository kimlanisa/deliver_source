<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tanda Terima</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;

        }

        .logo {
            flex-basis: 30%;
            text-align: right;
        }

        .title {
            flex-basis: 80%;
            text-align: left;
        }


        .customer-info {
            float: left;
            width: 50%;
            padding: 20px;
        }

        .invoice-info {
            float: right;
            width: 50%;
            padding: 20px;
        }

        .right-total {
            float: right;
            width: 42%;
            padding-top: 1px;
        }

        .catatan {
            float: left;
            width: 55%;
            padding-top: 10px;
        }

        p {
            line-height: 0.4;
        }


        .title h1 {
            margin: 0;
            font-size: 25px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 6px;
            border: 1px solid #ccc;
        }

        th {
            text-align: left;
            background-color: #f7f7f7;
        }

        .total {
            /* font-size: 12px; */
            margin-top: 0px;
            text-align: right;
            font-style: bold;
            float: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">
            <h1>Tanda Terima</h1>
        </div>
        <div class="logo">
            <p>User : {{ Auth::user()->name }}</p>
            <p>No Tanda Terima : {{ $serahterima->no_tanda_terima }}</p>
            <p>Tanggal Cetak : {{ formatDate($date) }}</p>
        </div>


    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="text-align: center;">Expedisi</th>
                <th style="text-align: center; width:15%;">Jumlah Paket</th>
                <th style="text-align: center;">Tanggal</th>
                <th style="text-align: center;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">{{ $serahterima->expedisi }}</td>
                <td style="text-align: center;">{{ $count }}</td>
                <td style="text-align: center;">{{ formatDate($serahterima->created_at) }}</td>
                <td style="text-align: center;"></td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 30px;font-style:bold;">List No Resi:</p>
    <table class="table" style="line-height: 1;">
        @foreach ($detail->chunk(4) as $chunk)
            <tr>
                @foreach ($chunk as $idx => $item)
                    <td style="width: 4%;font-size:12px;font-weight:600;border:none">{{ $idx + 1 }}.</td>
                    <td style="border:none;">{{ $item->no_resi }}</td>
                @endforeach
            </tr>
        @endforeach
    </table>


</body>

</html>
