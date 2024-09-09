<div class="mb-4">
    <div class="d-flex align-items-start justify-content-between">
        <div>
            <label for="tanggal" style="font-weight: bold; font-size: 14px">Tanggal</label>
            <div class="mt-1" style="font-size: 15px">{{ date('d F Y', strtotime($data->date)) }}</div>
        </div>
        <div>
            <label for="tanggal" style="font-weight: bold; font-size: 14px">PIC Laporan</label>
            <div class="mt-1" style="font-size: 15px">{{ $data->picReport->name ?? '-'}}</div>
        </div>
        <div>
            <label for="tanggal" style="font-weight: bold; font-size: 14px">Dibuat Pada</label>
            <div class="mt-1" style="font-size: 15px">{{ date('d F Y H:i:s', strtotime($data->created_at)) }}</div>
            <div class="mt-1" style="font-size: 15px"><span style="font-weight: bold">Oleh</span>: {{ $data->createdBy->name ?? '' }}</div>
        </div>
        <div>
            <label for="tanggal" style="font-weight: bold; font-size: 14px">Diupdate Pada</label>
            <div class="mt-1" style="font-size: 15px">{{ $data->updated_by_id ? date('d F Y H:i:s', strtotime($data->updated_at)) : '' }}</div>
            <div class="mt-1" style="font-size: 15px"><span style="font-weight: bold">
                @if ($data->updatedBy)
                Oleh</span>: {{ $data->updatedBy->name ?? '' }}</div>
                @else
                -
                @endif
        </div>
    </div>
</div>


<h6 class="mt-4">
    Laporan Pekerjaan :
</h6>
<hr/>
@php
$status_laporan = [
    ['value' => 'pending', 'label' => 'Pending', 'badge' => 'warning'],
    ['value' => 'in_progress', 'label' => 'In Progress', 'badge' => 'info'],
    ['value' => 'completed', 'label' => 'Completed', 'badge' => 'success' ],
];
@endphp
@foreach ($data->laporanKaryawanDetail ?? [] as $key => $itemReport)
<div class="row g-4 py-3"
    style="{{ ($data->laporanKaryawanDetail->last()->id ?? 0) !== $itemReport->id ? 'border-bottom: 1px solid #dadada' : '' }} ">
    <div class="col-md-8">
        <label for="pekerjaan" style="font-weight: bold; font-size: 14px">Pekerjaan {{ $key + 1 }}</label>
        <div class="content_pekerjaan">{!! $itemReport->pekerjaan !!}</div>
    </div>
    <div class="col-md-4">
        <label for="status" style="font-weight: bold; font-size: 14px">Status {{ $key + 1 }}</label>
        <div class="mt-1">
            @php
                $stl = collect($status_laporan)->where('value', $itemReport->status)->first();
            @endphp
            @if($stl)
            <div class="badge bg-{{ $stl['badge'] }}" style="font-size: 14px">
                {{ $stl['label'] }}
            </div>
            @endif
        </div>
    </div>
    @if (count(json_decode($itemReport->images ?? '[]') ) > 0)
    <div class="mt-5">
        Attachment
    </div>
    <div class="d-flex align-items-center gap-3 mt-2 attachment_list" style="flex-wrap: wrap">
        @foreach (json_decode($itemReport->images ?? '[]') as $keyimg => $img)
            @php
                $img = (array) $img;
            @endphp
            <div class="show-detail" style="cursor: pointer" data-id="{{ $itemReport->id }}"
                data-key="{{ $keyimg }}">
                <label class="btn bg-white text-black border btn-sm d-flex align-items-center gap-2">
                    <i class="fa fa-file"></i>
                    <div
                        style="max-width: 140px;
                    -webkit-line-clamp: 1;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    overflow: hidden;">
                        {{ $img['name'] ?? '' }}
                    </div>
                    <div>
                        {{ $img['extension'] ?? '' }}
                    </div>
                    <div>
                    {{ ($img['size'] ?? 0) / 1000 }} KB
                    </div>
                    <div>
                    <div data-url="{{ asset($img['url'] ?? '') }}" class="download-file">
                            <i class="fa fa-download text-success"></i>
                    </div>
                    </div>
                </label>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endforeach

<div class="modal" id="modalDetailFile" tabindex="-1" role="dialog" aria-labelledby="modal-block-small"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
        <div class="block block-rounded block-transparent mb-0">
            <div class="block-header block-header-default">
                <div>
                    <div style="cursor: pointer" data-url="" type="button" class="btn btn-primary btn-sm download-file" data-bs-dismiss="modal" aria-label="Close">
                        Download
                        <i class="fa fa-fw fa-download"></i>
                    </div>
                </div>
                <div class="block-options">
                    <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa fa-fw fa-times"></i>
                </button>
                </div>
            </div>
            <div class="block-content fs-sm" id="bodyDetailFile">

            </div>
        </div>
    </div>
</div>
</div>


<style>
    .content_pekerjaan {
        max-width: 100%;
    }

    .content_pekerjaan img {
        max-width: 100%;
    }
</style>

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>


    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var SITEURL = {!! json_encode(url('/')) !!};
    </script>

<script>
    let files_input = []
    let base_url = '{{ url('') }}'
    @foreach ($data->laporanKaryawanDetail ?? [] as $key => $itemReport)
        files_input[{{ $itemReport->id }}] = {!! json_encode(json_decode($itemReport->images ?? '[]')) !!}
    @endforeach
    console.log(files_input);
    $('.show-detail').on('click', function() {
            const id = $(this).data('id');
            const key = $(this).data('key');
            const file = files_input[id][key];
            console.log(files_input);
            const open = file.url ? `${base_url}/${file?.url}` : URL.createObjectURL(file);
            $('.download-file').data('url', open);
            if(!file.url) {
                window.open(open, '_blank');
                return
            }
            let url = ''
            if (file.url?.includes('pdf')) {
                url = `https://docs.google.com/gview?url=${base_url}/${file.url}&embedded=true`
            }else if (file.url?.includes('doc') || file.url?.includes('docx') || file.url?.includes('ppt') || file.url?.includes('pptx') || file.url?.includes('xls') || file.url?.includes('xlsx')) {
                url = `https://view.officeapps.live.com/op/embed.aspx?src=${base_url}/${file.url}`
            }  else {
                url = `${base_url}/${file.url}`
            }

            $('#modalDetailFile').modal('show');
            $('#bodyDetailFile').html(`
            <iframe src="${url}" style="width: 100%; height: 100vh"></iframe>
            `);
            // window.open(open, '_blank');
        });

        $('.download-file').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('download');
            var link = $(this).data('url');
            var element = document.createElement('a');
            element.setAttribute('href', link);
            element.setAttribute('target', '_blank');
            element.setAttribute('download', '');
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        });
</script>

@endsection
