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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document" data-bs-dismiss="false">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-label">Upload Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="dropzone" id="photoDropzone" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="dz-message" data-dz-message><span>Drag & drop or click to upload your photos</span>
                        </div>
                        <div class="fallback">
                            <input name="file_name[]" type="file" multiple />
                        </div>
                    </form>

                    <form id="photoDetailsForm" method="POST" action="{{ route('katalog.storeDetailPhoto') }}">
                        @csrf
                        <input type="hidden" name="parents_id" id="hidden-parents-id" value="{{ $parents->id }}">
                        <div class="mb-3 mt-4">
                            <label for="name" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="variasi" class="form-label">Variasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="variasi" name="variasi[]" required>
                                <button type="button" class="btn btn-outline-light" id="add-variasi">+</button>
                            </div>
                            <div id="variasi-list" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label for="link_url" class="form-label">Link Url</label>
                            <input type="text" class="form-control" id="link_url" name="link_url" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" maxlength="255" required></textarea>
                            <div id="charCount" class="form-text text-end">0/255 characters</div>
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
                    <h5 class="modal-title" id="modal-add-label">Create Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('katalog.storeChild') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="parents_id" value="{{ $parents->id }}">
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" required>
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
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts._message')
    <h4 class="mb-4">{{ $parents->name }}</h4>
    @if ($parents->childs->isNotEmpty())
        <div class="grid"
            data-masonry='{ "itemSelector": ".grid-item", "columnWidth": ".grid-sizer", "percentPosition": true }'>
            <div class="grid-sizer"></div>
            @foreach ($parents->childs as $item)
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
                                    <form action="{{ route('katalog.destroyChild', ['id' => $item->id]) }}" method="POST"
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
                                            <form action="{{ route('katalog.updateChild', ['id' => $item->id]) }}"
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
                                if (strlen($item->name) > 21) {
                                    echo substr($item->name, 0, 21) . '...';
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
                            onclick="window.location.href='{{ route('katalog.detail', ['parentId' => $parents->id, 'childId' => $item->id]) }}';">
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

    @if ($dataPhotos->isNotEmpty())
        <p class="mb-4">Photo Terkait</p>
        <div class="grid"
            data-masonry='{ "itemSelector": ".grid-item", "columnWidth": ".grid-sizer", "percentPosition": true }'>
            <div class="grid-sizer"></div>
            @foreach ($dataPhotos as $data)
                @php
                    $filePhoto = $photos->where('photo_id', $data->id)->first();
                @endphp
                @if ($filePhoto)
                    <div class="grid-item card"
                        style="background-color: white; border-radius: 8px; overflow: hidden; margin-bottom: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 180px; padding: 8px; position: relative;">
                        <div class="d-flex justify-content-between">
                            <div class="dropdown" style="position: absolute; top: 8px; right: 8px;">
                                <a href="javascript:void(0);" id="dropdownMenuLink{{ $data->id }}"
                                    data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;">
                                    <i class="bx bx-dots-vertical-rounded" style="font-size: 1.5rem;"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $data->id }}">
                                    <li>
                                        <a class="dropdown-item" data-bs-target="#modal-edit-photo-{{ $data->id }}"
                                            data-bs-toggle="modal">Edit</a>
                                    </li>

                                    <li>
                                        <form action="{{ route('katalog.destroyPhoto', ['id' => $data->id]) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item">Hapus</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                            <h5 class="card-title"
                                style="font-size: 0.9rem; font-weight: 600; margin-bottom: 6px; text-align: left;">
                                {{ Str::limit($data->name, 18) }}
                            </h5>
                        </div>

                        <div style="width: 100%; height: 150px; overflow: hidden; border-radius: 6px;">
                            <img src="{{ asset($filePhoto->file_name) }}" alt="{{ $data->name }}"
                                style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;"
                                onmouseenter="showSmallModal('{{ asset($filePhoto->file_name) }}', '{{ $data->name }}', '{{ $data->description }}', this)"
                                onmouseleave="hideSmallModal()"
                                onclick="window.location.href='{{ route('katalog.photoDetail', ['photoId' => $data->id]) }}';">
                        </div>

                        <div class="card-body" style="padding: 8px;">
                            <p class="card-text"
                                style="color: #6c757d; font-size: 0.8rem; text-align: left; margin-top: 6px;">
                                {{ Str::limit($data->description, 18) }}
                            </p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <div id="smallImageModal" onmouseenter="keepSmallModalVisible()" onmouseleave="hideSmallModal()"
        style="position: absolute; display: none;">
        <img id="smallModalImage"
            style="width: 100%; height: auto; object-fit: contain; border-radius: 8px; margin-bottom: 8px;">
        <div class="card-body">
            <h5 id="smallModalTitle" style="text-align: justify;"></h5>
            <p id="smallModalDescription" class="card-text" style="text-align: justify; color: #6c757d;">
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

        let modalVisible = false;

        function showSmallModal(image, title, description, element) {
            const modal = document.getElementById('smallImageModal');

            document.getElementById('smallModalImage').src = image;
            document.getElementById('smallModalTitle').innerText = title;
            document.getElementById('smallModalDescription').innerText = description;

            const rect = element.getBoundingClientRect();
            modal.style.top = (rect.bottom + window.scrollY) + 'px';
            modal.style.left = rect.left + 'px';

            modal.style.display = 'block';
            modalVisible = true;
        }

        function keepSmallModalVisible() {
            modalVisible = true;
        }

        function hideSmallModal() {
            if (!modalVisible) {
                document.getElementById('smallImageModal').style.display = 'none';
            }
        }

        document.querySelectorAll('.grid-item img').forEach((img) => {
            img.addEventListener('mouseenter', function() {
                modalVisible = true;
            });

            img.addEventListener('mouseleave', function() {
                modalVisible = false;
                setTimeout(() => {
                    hideSmallModal();
                }, 200);
            });
        });
    </script>

    <script>
        var uploadedDocumentMap = {};

        // Dropzone.options.photoDropzone = {
        //     url: '{{ route('projects.storeMedia') }}',
        //     paramName: "file_name[]",
        //     maxFilesize: 2,
        //     acceptedFiles: "image/*",
        //     dictDefaultMessage: "Drop files here or click to upload",
        //     addRemoveLinks: true,
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     },
        //     success: function(file, response) {
        //         $('form').append('<input type="hidden" name="file_name[]" value="' + response.file_name + '">');
        //         uploadedDocumentMap[file.name] = response.file_name;

        //         var fileNameLink = document.createElement("a");
        //         fileNameLink.textContent = file.name;
        //         fileNameLink.href = '{{ asset('uploads/file/dataPhotos') }}/' + response.file_name;
        //         fileNameLink.target = "_blank";
        //         fileNameLink.className = "link-style";

        //         file.previewElement.querySelector(".dz-filename").innerHTML = '';
        //         file.previewElement.querySelector(".dz-filename").appendChild(fileNameLink);
        //     },

        //     // success: function(file, response) {
        //     //     $('form').append('<input type="hidden" name="file_name[]" value="' + response.name + '">');
        //     //     uploadedDocumentMap[file.name] = response.name;

        //     //     var fileNameLink = document.createElement("a");
        //     //     fileNameLink.textContent = file.name;
        //     //     fileNameLink.href = '{{ asset('uploads/file/photos') }}/' + response.name;
        //     //     fileNameLink.target = "_blank";
        //     //     fileNameLink.className = "link-style";

        //     //     file.previewElement.querySelector(".dz-filename").innerHTML = '';
        //     //     file.previewElement.querySelector(".dz-filename").appendChild(fileNameLink);
        //     // },
        //     removedfile: function(file) {
        //         file.previewElement.remove();
        //         var name = '';
        //         if (typeof file.file_name !== 'undefined') {
        //             name = file.file_name;
        //         } else {
        //             name = uploadedDocumentMap[file.name];
        //         }
        //         $('form').find('input[name="file_name[]"][value="' + name + '"]').remove();
        //     },
        //     init: function() {
        //         @if (isset($project) && $project->file_name)
        //             var files = {!! json_encode($project->file_name) !!};
        //             for (var i in files) {
        //                 var file = files[i];
        //                 this.options.addedfile.call(this, file);
        //                 file.previewElement.classList.add('dz-complete');
        //                 $('form').append('<input type="hidden" name="file_name[]" value="' + file.file_name + '">');

        //                 var fileNameLink = document.createElement("a");
        //                 fileNameLink.textContent = file.file_name;
        //                 fileNameLink.href = '{{ asset('uploads/file/photos') }}/' + file.file_name;
        //                 fileNameLink.target = "_blank";
        //                 fileNameLink.className = "link-style";

        //                 file.previewElement.querySelector(".dz-filename").innerHTML = '';
        //                 file.previewElement.querySelector(".dz-filename").appendChild(fileNameLink);
        //             }
        //         @endif
        //     }
        // };
        Dropzone.options.photoDropzone = {
            url: '{{ route('projects.storeMedia') }}',
            paramName: "file_name[]",
            maxFilesize: 2,
            acceptedFiles: "image/*",
            dictDefaultMessage: "Drop files here or click to upload",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(file, response) {
                $('#photoDetailsForm').append('<input type="hidden" name="file_name[]" value="' + response.files[0]
                    .file_name + '">');
                uploadedDocumentMap[file.name] = response.files[0].file_name;

                var fileNameLink = document.createElement("a");
                fileNameLink.textContent = file.name;
                fileNameLink.href = '{{ asset('uploads/file/photos') }}/' + response.files[0].file_name;
                fileNameLink.target = "_blank";
                fileNameLink.className = "link-style";

                file.previewElement.querySelector(".dz-filename").innerHTML = '';
                file.previewElement.querySelector(".dz-filename").appendChild(fileNameLink);
            },
            removedfile: function(file) {
                file.previewElement.remove();
                var name = uploadedDocumentMap[file.name];
                $('form').find('input[name="file_name[]"][value="' + name + '"]').remove();
            },
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const description = document.getElementById('description');
            const charCount = document.getElementById('charCount');

            description.addEventListener('input', function() {
                charCount.textContent = `${description.value.length}/255 characters`;
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.getElementById('add-variasi');
            const variationsList = document.getElementById('variasi-list');
            let count = 1; 

            addButton.addEventListener('click', function() {
                const inputValue = document.getElementById('variasi').value;
                if (inputValue.trim() === '') {
                    alert('Masukkan variasi terlebih dahulu.');
                    return;
                }

                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                div.innerHTML = `
            <input type="text" class="form-control" name="variasi[]" value="${inputValue}" readonly>
            <button type="button" class="btn btn-outline-danger remove-variasi" data-id="${count}">-</button>
        `;
                variationsList.appendChild(div);

                document.getElementById('variasi').value = '';
                count++;
            });

            variationsList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-variasi')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
@endsection
