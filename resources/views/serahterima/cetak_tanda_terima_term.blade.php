<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tanda Terima</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            /* line-height: 1; */
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
            font-size: 18px;
        }

        .table,
        .table2 {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            padding: 6px;
            border-bottom: 1px solid #ccc;
            /* border: 1px solid #ccc; */
        }

        .table2 td {
            padding: 6px;
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
            <h1 style="text-align: center">Tanda Terima</h1>
        </div>
    </div>

    <table class="table">
        <tbody>
            <tr>
                <td style="width: 40%;vertical-align:baseline;font-size:12px">Expedisi</td>
                <td style="width: 2%;vertical-align:baseline"></td>
                <td style="font-weight: 600;font-size:12px;text-align:right">{{ $serahterima->expedisi }}</td>
            </tr>
            <tr>
                <td style="width: 40%;vertical-align:baseline;font-size:12px">Catatan</td>
                <td style="width: 2%;vertical-align:baseline"></td>
                <td style="font-weight: 600;font-size:12px;text-align:right">{{ $serahterima->catatan }}</td>
            </tr>
            <tr>
                <td style="width: 40%;vertical-align:baseline;font-size:12px">No Tanda Terima</td>
                <td style="width: 2%;vertical-align:baseline"></td>
                <td style="font-weight: 600;font-size:12px;text-align:right">{{ $serahterima->no_tanda_terima }} |
                    {{ formatDate($serahterima->created_at) }}</td>
            </tr>
            <tr>
                <td style="width: 40%;vertical-align:baseline;font-size:12px">Waktu Cetak</td>
                <td style="width: 2%;vertical-align:baseline"></td>
                <td style="font-weight: 600;font-size:12px;text-align:right">{{ formatDate($date) }}</td>
            </tr>
            <tr>
                <td style="width: 40%;vertical-align:baseline;font-size:12px">Jumlah Paket</td>
                <td style="width: 2%;vertical-align:baseline"></td>
                <td style="font-weight: 600;font-size:12px;text-align:right">{{ $count }} Resi</td>
            </tr>
            <tr>
                <td style="width: 40%;vertical-align:baseline;font-size:12px">User</td>
                <td style="width: 2%;vertical-align:baseline"></td>
                <td style="font-weight: 600;font-size:12px;text-align:right">{{ Auth::user()->name }}</td>
            </tr>
        </tbody>
    </table>

    <h4 style="padding:6px;border-bottom: 1px solid black;width:100px">List No Resi</h4>

    <table class="table2">
        <tbody>
            @foreach ($detail->chunk(2) as $chunk)
                <tr>
                    @foreach ($chunk as $idx => $item)
                        <td style="width: 4%;font-size:12px;font-weight:600">{{ $idx + 1 }}.</td>
                        <td style="font-size:12px">{{ $item->no_resi }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    </div>
    {{-- @foreach ($detail->chunk(2) as $chunk)
        @foreach ($chunk as $item)
            <li>{{ $item->no_resi }}</li>
        @endforeach
    @endforeach --}}
    <hr style="border: dotted 1px;">

</body>

</html>
