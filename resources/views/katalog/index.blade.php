@extends('layouts.katalog')

@section('content')
    <button class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center"
        style="position: fixed; bottom: 50px; right: 50px; width: 60px; height: 60px; font-size: 24px;" data-bs-toggle="modal"
        data-bs-target="#modal-create-folder">
        <i class="tf-icons bx bx-plus text-white"></i>
    </button>

    <div class="modal fade" id="modal-create-folder" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
        <div class="modal-dialog modal-lg   " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-label">Create New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('katalog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" required>
                            <img id="thumbnail-preview" src="#" alt="Preview"
                                style="display:none; margin-top:10px; max-width:100px;" />
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($data->isNotEmpty())
        <div class="grid"
            data-masonry='{ "itemSelector": ".grid-item", "columnWidth": ".grid-sizer", "percentPosition": true }'>
            <div class="grid-sizer"></div>
            @foreach ($data as $item)
                <div class="grid-item" style="border: none; background: none; box-shadow: none;">
                    <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->name }}"
                        style="width: 100%; max-height: 150px; object-fit: contain; margin-bottom: 16px; cursor: pointer;"
                        onmouseenter="showSmallModal('{{ asset($item->thumbnail) }}', '{{ $item->name }}', '{{ $item->description }}', this)"
                        onmouseleave="hideSmallModal()"
                        onclick="window.location.href='{{ route('katalog.show', ['id' => $item->id]) }}';">
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
