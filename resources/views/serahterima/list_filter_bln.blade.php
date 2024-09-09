@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    {{-- <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css')}}"> --}}
@endsection
    
@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    
    <!-- Page JS Plugins -->
    
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.colVis.min.js') }}"></script>

    
    <!-- Page JS Code -->
    <script>One.helpersOnLoad(['js-flatpickr']);</script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
    </script>

    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>

    <script>
            $(document).ready(function() {
                $("#filter-btn").click(function(){
                    $("#filter").toggle();
                });
            });

        //DELETE
            $('body').on("click",".btn-delete",function(){
                var id = $(this).attr("id");
                $(".btn-destroy").attr("id",id);
                
                $("#modal-delete").modal("show");
            });

            $(".btn-destroy").on("click",function(){
                var id = $(this).attr("id")
                console.log(id);
                $.ajax({
                    url: "/deliver/serahterima/"+id,
                    method : 'DELETE',
                    success:function(){
                        $("#modal-delete").modal("hide")
                        One.helpers('jq-notify', 
                        {type: 'danger', icon: 'fa fa-check me-1', message: 'Success Delete!'});
                        window.location.reload();
                    },
                    error: function(xhr, status, error){
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    alert('Error!!');
                    },
                });
            })
            //END DELETE

            $('body').on("click",".btn-delete-anggota",function(){
                var id = $(this).attr("id");
                $(".btn-destroy-anggota").attr("id",id);
                
                $("#modal-delete-anggota").modal("show");
            });

           
    </script>


@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
      <div class="content content-full">
          <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
              <div class="flex-grow-1">
                  <h2 class="h3 fw-bold mb-2">
                      Serah Terima
                  </h2>
                  <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                      Daftar Serah Terima Paket
                  </h2>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                  <ol class="breadcrumb breadcrumb-alt">
                      <li class="breadcrumb-item">
                          <a class="link-fx" href="javascript:void(0)">Paket</a>
                      </li>
                      <li class="breadcrumb-item" aria-current="page">
                          Serah Terima
                      </li>
                  </ol>
              </nav>
          </div>
      </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    LIST DAFTAR SERAH TERIMA
                </h3>
                <a href="{{route('cariResi')}}" class="btn btn-sm btn-alt-secondary"><i class="fa fa-search"></i> Cari No Resi</a>
                <button type="button" class="btn btn-sm btn-alt-secondary" id="filter-btn"  aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-flask"></i>
                    Filters
                </button>
                <a href="{{route('serahterima.create')}}" class="btn btn-sm btn-alt-secondary"><i class="fa fa-plus text-info me-1"></i>Serah Terima Baru</a>
            </div>
            
               
            


            <div class="block-content block-content-full">
                <div id="filter">
                    <div class="row mt-3">
                        <div class="col-lg-3 text-end" style="padding-top: 5px;">
                            Filter Data :
                        </div>
                        <div class="col-lg-9 col-xl-8">
                            <form method="GET" action="{{route('listFilter')}}">
                            @csrf
                            <div class="row">
                                <div class="col-xl-3 mb-4">
                                    <input type="text" class="js-flatpickr form-control" id="start_date" name="start_date" placeholder="dari..">
                                </div> 
                                <div class="col-xl-3 mb-4">
                                    <input type="text" class="js-flatpickr form-control" id="end_date" name="end_date" placeholder="sampai..">
                                </div>
                                <div class="col-xl-3 mb-4">
                                    <button type="submit" class="btn btn-alt-primary  btn-md">Filter Data</button>
                                    <a href="{{ route('export') }}" class="btn btn-alt-success  btn-md">Excel</a>
                                </div>
                                
                            </div>
                            </form>
                        </div>
                    </div>
    
                    <div class="row mt-3">
                        <div class="col-lg-3 text-end" style="padding-top: 5px;">
                            Filter Data :
                        </div>
                        <div class="col-lg-9 col-xl-8">
                            <form method="GET" action="{{route('listFilterBulan')}}">
                            @csrf
                            <div class="row">
                                <div class="col-xl-3 mb-4">
                                    <select class="form-control" id="bulan" name="bulan">
                                        <option value="{{$bulan}}">
                                            @if ($bulan == '1')
                                                Januari
                                            @endif
                                            @if ($bulan == '2')
                                                Februari
                                            @endif
                                            @if ($bulan == '3')
                                                Maret
                                            @endif
                                            @if ($bulan == '4')
                                                April
                                            @endif
                                            @if ($bulan == '5')
                                                Mei
                                            @endif
                                            @if ($bulan == '6')
                                                Juni
                                            @endif
                                            @if ($bulan == '7')
                                                Juli
                                            @endif
                                            @if ($bulan == '8')
                                                Agustus
                                            @endif
                                            @if ($bulan == '9')
                                                September
                                            @endif
                                            @if ($bulan == '10')
                                                Oktober
                                            @endif
                                            @if ($bulan == '11')
                                                November
                                            @endif
                                            @if ($bulan == '12')
                                                Desember
                                            @endif
                                        </option>

                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div> 
                                <div class="col-xl-3 mb-4">
                                    <select class="form-control" id="tahun" name="tahun">
                                        <option value="{{$tahun}}">{{$tahun}}</option>
                                        @for ($i = date('Y'); $i >= 2022; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-xl-3 mb-4">
                                    <button type="submit" class="btn btn-alt-primary  btn-md">Filter Data</button>
                                    <a href="{{ route('exportByBulan', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-alt-success  btn-md">Excel</a>
                                </div>
                                
                            </div>
                            </form>
                        </div>
                    </div>
                </div>


                @include('layouts._message')
              
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">#</th>
                                <th style="width: 200px;">No Tanda Terima</th>
                                <th>Jumlah Paket</th>
                                <th>Nama Expedisi</th>
                                <th>Waktu Dibuat</th>
                                <th>Catatan</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach ($serahterima as $st)
                            <tr>
                                <td class="text-center">{{$no++}}</td>
                                <td class="text-center">
                                    <a class="fw-semibold" href="javascript:void(0)">
                                        {{$st->no_tanda_terima}}</a>
                                </td>
                                <td class="text-center">
                                    <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info fs-sm">{{$st->totalpaket}}</span>
                                </td>
                                <td class="text-center">{{$st->expedisi}}</td>
                                <td class="text-center">{{$st->created_at}}</td>
                                <td>{{$st->catatan}}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{route('serahterima.show',$st->id)}}" id="{{$st->id}}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-show"><i class="fa fa-fw fa-eye"></i></a>
                                        <a href="{{route('printTandaTerima',$st->id)}}" target="_blank" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print"
                                                data-bs-toggle="tooltip" title="Print"><i class="fa fa-fw fa-print"></i>
                                                </a>
                                        @if (Auth::user()->role == 'admin')
                                        <a href="javascript:void(0)" id="{{$st->id}}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                        data-bs-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>




    <!-- modal delete -->
    <div class="modal" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header block-header-default">
                <h3 class="block-title">Hapus Data?</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
                </div>
                <div class="block-content fs-sm">
                    <h5>Yakin akan menghapus data ini?</h5>
                </div>
                <div class="block-content block-content-full text-end bg-body">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-danger btn-destroy">Hapus</button>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- END Small Block Modal -->

 
@endsection


