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
                    url: "/serahterima/"+id,
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
                      Cari No Resi
                  </h2>
                  <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                      Detail Data No Resi
                  </h2>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                  <ol class="breadcrumb breadcrumb-alt">
                      <li class="breadcrumb-item">
                          <a class="link-fx" href="javascript:void(0)">Serah Terima</a>
                      </li>
                      <li class="breadcrumb-item" aria-current="page">
                          No Resi
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
                    DETAIL NO RESI
                </h3>
                <a href="javascript:history.back()" class="btn btn-alt-primary btn-sm">
                    <i class="fa fa-arrow-alt-circle-left text-info me-1"></i>Kembali
                </a>
            </div>
            
                    
            


            <div class="block-content block-content-full">
                @include('layouts._message')
              
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 80px;">#</th>
                                <th style="width: 200px;">No Tanda Terima</th>
                                <th>No Resi</th>
                                <th>Nama Expedisi</th>
                                <th>Waktu Dibuat</th>
                       
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach ($serahterima as $st)
                            <tr>
                                <td class="text-center">{{$no++}}</td>
                                <td class="text-center">
                                    <a class="fw-semibold" href="{{route('serahterima.show',$st->id)}}">
                                        {{$st->no_tanda_terima}}</a>
                                </td>
                                <td class="text-center">
                                    {{$st->no_resi}}
                                </td>
                                <td class="text-center">{{$st->expedisi}}</td>
                                <td class="text-center">{{$st->created_at}}</td>

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


