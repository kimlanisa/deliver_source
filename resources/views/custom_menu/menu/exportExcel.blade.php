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
    <h1 class="bigheader">Laporan {{ $data->name }}</h1>
    {{-- <h2 class="subheader"></h2> --}}
</center>
<table class="table">
    <thead>
        <tr>
            <th>NO</th>
            @foreach ($data->label_show ?? [] as $item)
            <th style="min-width: 150px">{{ $item['label'] }}</th>
            @endforeach
        <tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data_value as $item)
            <tr>
                <td>{{ ++$no }}</td>
                @foreach ($data->label_show ?? [] as $item2)
                    <td>
                        {{ $item[$item2['name']] }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
