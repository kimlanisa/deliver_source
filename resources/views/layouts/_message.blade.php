@if (session('success'))
    <!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success</strong> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div> -->

    <div class="alert alert-success alert-dismissible" role="alert">
        <p class="mb-0">
        {{ session('success') }} <a class="alert-link" href="javascript:void(0)"></a>!
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('danger'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Peringatan : </strong> {{ session('danger') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif