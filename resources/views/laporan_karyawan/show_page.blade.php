@extends('layouts.backend')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
            <div class="flex-grow-1">
                <h2 class="h3 fw-bold mb-2">
                    Detail Laporan Karyawan
                </h2>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                  No Laporan : <span
                        class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info fs-sm">
                        {{ $data->no_laporan }}
                    </span>
                </h2>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="">Laporan Karyawan </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Detail
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <div class="block block-rounded">
        <div class="block-content block-content-full">
           @include('laporan_karyawan.show_body')
        </div>
    </div>
</div>


@endsection
