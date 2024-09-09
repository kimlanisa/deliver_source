@extends('layouts.katalog')

@section('content')
    <div class="dropdown">
        <button class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center"
            style="position: fixed; bottom: 50px; right: 50px; width: 60px; height: 60px; font-size: 24px; z-index: 1000;"
            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="tf-icons bx bx-plus text-white"></i>
        </button>

        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="z-index: 9999;">
            <li>
                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                    data-bs-target="#modal-create-folder">
                    <i class="tf-icons bx bx-folder-plus"></i> Create Folder
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                    data-bs-target="#modal-upload-photo">
                    <i class="tf-icons bx bx-image-add"></i> Upload Foto
                </a>
            </li>
        </ul>
    </div>

    <div class="modal fade" id="modal-upload-photo" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-label">Upload New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('katalog.storePhoto') }}" method="POST" enctype="multipart/form-data">
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

    <div class="modal fade" id="modal-create-folder" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                <div class="grid-item card"
                    style="background-color: white; border-radius: 8px; overflow: hidden; margin-bottom: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 180px; padding: 8px; position: relative;">

                    <div class="d-flex justify-content-between">
                        <div class="dropdown" style="position: absolute; top: 8px; right: 8px;">
                            <a href="javascript:void(0);" id="dropdownMenuLink{{ $item->id }}"
                                data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;">
                                <i class="bx bx-dots-vertical-rounded" style="font-size: 1.5rem;"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $item->id }}">
                                <li>
                                    <a class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#modal-edit-folder-{{ $item->id }}">Edit</a>
                                </li>
                                <li>
                                    <form action="{{ route('katalog.destroy', ['id' => $item->id]) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">Hapus</button>
                                    </form>
                                </li>
                            </ul>

                            <div class="modal fade" id="modal-edit-folder-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="modal-edit-label" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal-edit-label">Edit {{ $item->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('katalog.update', ['id' => $item->id]) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="thumbnail-{{ $item->id }}"
                                                        class="form-label">Thumbnail</label>
                                                    <input type="file" class="form-control"
                                                        id="thumbnail-{{ $item->id }}" name="thumbnail">
                                                    @if ($item->thumbnail)
                                                        <img id="thumbnail-preview-{{ $item->id }}"
                                                            src="{{ asset($item->thumbnail) }}" alt="Preview"
                                                            style="margin-top:10px; max-width:100px;" />
                                                    @endif
                                                </div>

                                                <div class="mb-3">
                                                    <label for="name-{{ $item->id }}"
                                                        class="form-label">Judul</label>
                                                    <input type="text" class="form-control"
                                                        id="name-{{ $item->id }}" name="name"
                                                        value="{{ $item->name }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="description-{{ $item->id }}"
                                                        class="form-label">Description</label>
                                                    <textarea class="form-control" id="description-{{ $item->id }}" name="description" rows="3" required>{{ $item->description }}</textarea>
                                                </div>

                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-title"
                            style="font-size: 0.9rem; font-weight: 600; margin-bottom: 6px; text-align: left;">
                            @php
                                if (strlen($item->name) > 15) {
                                    echo substr($item->name, 0, 15) . '...';
                                } else {
                                    echo $item->name;
                                }
                            @endphp
                        </h5>
                    </div>

                    <div style="width: 100%; height: 150px; overflow: hidden; border-radius: 6px;">
                        <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->name }}"
                            style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;"
                            onmouseenter="showSmallModal('{{ asset($item->thumbnail) }}', '{{ $item->name }}', '{{ $item->description }}', this)"
                            onmouseleave="hideSmallModal()"
                            onclick="window.location.href='{{ route('katalog.show', ['id' => $item->id]) }}';">
                    </div>

                    <div class="card-body" style="padding: 8px;">
                        <p class="card-text"
                            style="color: #6c757d; font-size: 0.8rem; text-align: left; margin-top: 6px;">
                            @php
                                if (strlen($item->description) > 21) {
                                    echo substr($item->description, 0, 21) . '...';
                                } else {
                                    echo $item->description;
                                }
                            @endphp
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- <div id="smallImageModal">
        <div class="grid-item card"
            style="background-color: white; border-radius: 8px; overflow: hidden; margin-bottom: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 180px; padding: 8px; position: relative;">
            <div style="width: 100%; height: 150px; overflow: hidden; border-radius: 6px;">
                <h5 id="smallModalTitle"></h5>
                <img id="smallModalImage">
            </div>
            <div class="card-body" style="padding: 8px;">
                <p id="smallModalDescription" class="card-text" style="color: #6c757d; font-size: 0.8rem; text-align: left; margin-top: 6px;">
                </p>
            </div>
        </div>
    </div> --}}

    <div id="smallImageModal">
        <img id="smallModalImage" style="width: 100%; height: auto; object-fit: contain; border-radius: 8px;">
        <div class="card-body" style="padding: 10px;">
            <h5 id="smallModalTitle" style="padding: 5px 0; text-align: justify;"></h5>
            <p id="smallModalDescription" class="card-text" style="font-size: 0.9rem; text-align: justify; color: #6c757d;">
            </p>
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
    </script>

@endsection
