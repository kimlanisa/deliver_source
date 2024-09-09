@extends('layouts.katalog')

@section('content')
    <button class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center"
        style="position: fixed; bottom: 50px; right: 50px; width: 60px; height: 60px; font-size: 24px;" data-bs-toggle="modal"
        data-bs-target="#modal-upload">
        <i class="tf-icons bx bx-plus text-white"></i>
    </button>

    <div class="modal fade" id="modal-upload" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-label">Create Data {{ $childs->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('katalog.storeGrandChild', ['childs_id' => $childs->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="childs_id" value="{{ $childs->id }}">
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                            <img id="thumbnail-preview" src="#" alt="Preview"
                                style="display:none; margin-top:10px; max-width:100px;" />
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($childs->grand_childs->isNotEmpty())
        <div class="grid"
            data-masonry='{ "itemSelector": ".grid-item", "columnWidth": ".grid-sizer", "percentPosition": true }'>
            <div class="grid-sizer"></div>
            @foreach ($childs->grand_childs as $grandChild)
                <div class="grid-item" style="border: none; background: none; box-shadow: none;">
                    <img src="{{ asset($grandChild->thumbnail) }}" alt="{{ $grandChild->name }}"
                        style="width: 100%; max-height: 150px; object-fit: contain; margin-bottom: 16px; cursor: pointer;"
                        onmouseenter="showSmallModal('{{ asset($grandChild->thumbnail) }}', '{{ $grandChild->name }}', '{{ $grandChild->description }}', this)"
                        onmouseleave="hideSmallModal()">
                </div>
            @endforeach
        </div>
    @endif

    <div class="small-modal" id="smallImageModal">
        <div class="modal-content">
            <img id="smallModalImage" src="" alt="" class="img-fluid mb-2">
            <h6 id="smallModalTitle"></h6>
            <p id="smallModalDescription" class="small"></p>
        </div>
    </div>

    <script>
        function showSmallModal(image, title, description, element) {
            document.getElementById('smallModalImage').src = image;
            document.getElementById('smallModalTitle').innerText = title;
            document.getElementById('smallModalDescription').innerText = description;

            const rect = element.getBoundingClientRect();
            const modal = document.getElementById('smallImageModal');
            modal.style.top = (rect.bottom + window.scrollY) + 'px';
            modal.style.left = rect.left + 'px';
            modal.style.display = 'block';
        }

        function hideSmallModal() {
            document.getElementById('smallImageModal').style.display = 'none';
        }

        window.onload = function() {
            var elem = document.querySelector('.grid');
            var msnry = new Masonry(elem, {
                itemSelector: '.grid-item',
                columnWidth: '.grid-sizer',
                percentPosition: true
            });
        };
    </script>
@endsection
