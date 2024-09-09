@extends('layouts.backend')
@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Page JS Code -->
 
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
    </script>

@endsection



@section('content')
  <!-- Main Container -->
      <main id="main-container">
        <!-- Hero -->
        <div class="bg-image" style="background-image: url({{asset('/media/photos/photo37.jpg')}});">
        <!-- <div class="bg-image" style=""> -->
          <div class="bg-black-50">
            <div class="content content-full text-center">
              <div class="my-3">
                
              </div>
              <h1 class="h2 text-white mb-0"></h1>
              <span class="text-white-75"></span>
              <!-- <a class="btn btn-alt-secondary" href="be_pages_generic_profile.html">
                <i class="fa fa-fw fa-arrow-left text-danger"></i> Back to Profile
              </a> -->
            </div>
          </div>
        </div>
        <!-- END Hero -->

        <!-- Stats -->
        <div class="bg-body-extra-light">
          <div class="content content-boxed">
            <div class="row items-push text-center">
              <div class="col-6 col-md-3">
                <div class="fs-sm fw-semibold text-muted text-uppercase">Referal Langsung</div>
                <a class="link-fx fs-3" href="javascript:void(0)"></a>
              </div>
              <div class="col-6 col-md-3">
                <div class="fs-sm fw-semibold text-muted text-uppercase">Referal Tidak Langsung</div>
                <a class="link-fx fs-3" href="javascript:void(0)"></a>
              </div>
              <div class="col-6 col-md-3">
                <div class="fs-sm fw-semibold text-muted text-uppercase">Total Referal</div>
                <a class="link-fx fs-3" href="javascript:void(0)"></a>
              </div>
              <div class="col-6 col-md-3">
                <div class="fs-sm fw-semibold text-muted text-uppercase mb-2">Kode Referal Anda</div>
                <a class="link-fx fs-3" href="javascript:void(0)"></a>
              </div>
            </div>
          </div>
        </div>
        <!-- END Stats -->

        <!-- Page Content -->
        <div class="content content-boxed">
        
          <div class="block block-rounded overflow-hidden">
            <ul class="nav nav-tabs nav-tabs-block" role="tablist">
              <li class="nav-item">
                <button type="button" class="nav-link active" id="search-projects-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="search-projects" aria-selected="true">Info Umum</button>
              </li>
              <li class="nav-item">
                <button type="button" class="nav-link" id="search-users-tab" data-bs-toggle="tab" data-bs-target="#kaderku" role="tab" aria-controls="search-users" aria-selected="false">Kaderku</button>
              </li>
            </ul>


            
            
          </div>
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->

     
@endsection

