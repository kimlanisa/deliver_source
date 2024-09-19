@extends('layouts.katalog')

@section('content')
    @include('layouts._message')
    <div class="container" style="background-color: #fff; padding: 20px; border-radius: 8px;">
        <div class="row">
            <div class="col-md-12 d-flex">
                <div class="main-image" style="flex: 1;">
                    <img id="mainImage" src="{{ $thumbnailImage->file_name }}" alt="Main Thumbnail"
                        class="img-fluid main-thumbnail">

                    <div class="photo-thumbnails mt-2 d-flex flex-wrap">
                        @foreach ($mainImages as $image)
                            <div class="photo-thumbnail mr-2">
                                <img src="{{ $image->file_name }}" alt="{{ $photo->name }}" class="img-fluid thumbnail-img"
                                    data-large="{{ $image->file_name }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="photo-details" style="flex: 1; padding-left: 0px;">
                    <h3>{{ $photo->name }}</h3>
                    <p>{{ $photo->description }}</p>

                    <div class="variasi-container mt-2">
                        @foreach ($variasi as $item)
                            <span class="variasi-item">{{ $item->name }}</span>
                        @endforeach
                    </div>
                    <p class="mt-2">
                        <a href="{{ $photo->link_url }}" target="_blank" rel="noopener noreferrer">
                            {{ $photo->link_url }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button id="editButton" class="btn btn-primary me-md-2" type="button">Edit</button>
            <button id="deleteButton" class="btn btn-danger" type="button">Delete</button>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-photo" tabindex="-1" aria-labelledby="modal-edit-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-edit-label">Edit Photo</h5>
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

                    <form id="editPhotoForm" method="POST" action="{{ route('katalog.updateDetailPhoto', $photo->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="photo_id" id="edit-photo-id">

                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="variasi-edit" class="form-label">Variasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="variasi-edit" name="variasi[]">
                                <button type="button" class="btn btn-outline-light" id="add-variasi-edit">+</button>
                            </div>
                            <div id="variasi-list-edit" class="mt-2">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-description" name="description" rows="3" maxlength="255" required></textarea>
                            <div id="charCount" class="form-text text-end">0/255 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-link_url" class="form-label">Link Url</label>
                            <input type="text" class="form-control" id="edit-link_url" name="link_url" required>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        Dropzone.autoDiscover = false;
        var uploadedDocumentMap = {};

        document.addEventListener('DOMContentLoaded', function() {
            const photoDropzone = new Dropzone('#photoDropzone', {
                url: '{{ route('projects.storeMedia') }}',
                maxFilesize: 2, // MB
                paramName: "file_name[]",
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                init: function() {
                    const myDropzone = this;

                    myDropzone.on("removedfile", function(file) {
                        if (file.id) {
                            fetch(`/photos/${file.id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        console.log('File deleted successfully:', file
                                            .name);
                                    } else {
                                        console.error('Failed to delete file:', file.name);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error deleting file:', error);
                                });
                        }
                    });

                    document.getElementById('editButton').addEventListener('click', function() {
                        const photoId = {{ $photo->id }};

                        fetch(`/photos/${photoId}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(
                                        `HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    const photoData = data.data;


                                    myDropzone.removeAllFiles(true);


                                    photoData.files.forEach((file) => {
                                        const mockFile = {
                                            name: file.file_name,
                                            id: file.id
                                        };

                                        myDropzone.emit('addedfile', mockFile);
                                        myDropzone.emit('thumbnail', mockFile, file
                                            .file_name);
                                        myDropzone.emit('complete', mockFile);
                                    });


                                    document.getElementById('edit-name').value = photoData
                                        .name;
                                    document.getElementById('edit-description').value =
                                        photoData.description;
                                    document.getElementById('edit-link_url').value =
                                        photoData.link_url;


                                    const variasiContainer = document.getElementById(
                                        'variasi-list-edit');
                                    variasiContainer.innerHTML =
                                        '';


                                    photoData.variasi.forEach((variasi) => {
                                        const name = variasi.name || '';
                                        const div = document.createElement('div');
                                        div.classList.add('input-group', 'mb-2');
                                        div.innerHTML = `
                                            <input type="text" class="form-control" name="variasi[]" value="${name}" readonly>
                                            <button type="button" class="btn btn-outline-danger remove-variasi" data-id="${variasi.id}">-</button>
                                        `;
                                        variasiContainer.appendChild(div);


                                        const removeButton = div.querySelector(
                                            '.remove-variasi');
                                        removeButton.addEventListener('click',
                                            function() {

                                                variasiContainer.removeChild(
                                                    div);


                                                if (variasi.id) {
                                                    fetch(`/variations/${variasi.id}`, {
                                                            method: 'DELETE',
                                                            headers: {
                                                                'X-CSRF-TOKEN': document
                                                                    .querySelector(
                                                                        'meta[name="csrf-token"]'
                                                                    )
                                                                    .getAttribute(
                                                                        'content'
                                                                    )
                                                            }
                                                        })
                                                        .then(response =>
                                                            response.json())
                                                        .then(data => {
                                                            if (data
                                                                .success) {
                                                                console.log(
                                                                    'Variasi deleted successfully:',
                                                                    name
                                                                );
                                                            } else {
                                                                console
                                                                    .error(
                                                                        'Failed to delete variasi:',
                                                                        name
                                                                    );
                                                            }
                                                        })
                                                        .catch(error => {
                                                            console.error(
                                                                'Error deleting variasi:',
                                                                error);
                                                        });
                                                }
                                            });
                                    });
                                } else {
                                    console.error('Failed to fetch photo data:', data
                                        .message);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching photo data:', error);
                            });
                    });
                },
                success: function(file, response) {
                    $('#editPhotoForm').append('<input type="hidden" name="file_name[]" value="' +
                        response.files[0]
                        .file_name + '">');
                    uploadedDocumentMap[file.name] = response.files[0].file_name;

                    var fileNameLink = document.createElement("a");
                    fileNameLink.textContent = file.name;
                    fileNameLink.href = '{{ asset('uploads/file/photos') }}/' + response.files[0]
                        .file_name;
                    fileNameLink.target = "_blank";
                    fileNameLink.className = "link-style";

                    file.previewElement.querySelector(".dz-filename").innerHTML = '';
                    file.previewElement.querySelector(".dz-filename").appendChild(fileNameLink);
                }
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const thumbnails = document.querySelectorAll('.thumbnail-img');
            const mainImage = document.getElementById('mainImage');

            let lastThumbnailSrc = mainImage.src;

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const currentThumbnailSrc = this.src;
                    const previousMainImageSrc = mainImage.src;

                    mainImage.src = currentThumbnailSrc;

                    this.src = previousMainImageSrc;

                    lastThumbnailSrc = currentThumbnailSrc;

                    thumbnails.forEach(img => img.classList.remove('active'));

                    this.classList.add('active');
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButton = document.getElementById('editButton');
            const deleteButton = document.getElementById('deleteButton');
            const editModal = new bootstrap.Modal(document.getElementById('modal-edit-photo'));

            editButton.addEventListener('click', function() {
                const photoId = "{{ $photo->id }}";
                document.getElementById('edit-photo-id').value = photoId;
                document.getElementById('edit-name').value = "{{ $photo->name }}";
                document.getElementById('edit-description').value = "{{ $photo->description }}";
                document.getElementById('edit-link_url').value = "{{ $photo->link_url }}";


                const variations =
                    @json($photo->variasi);
                const variationsList = document.getElementById('variasi-list-edit');
                variationsList.innerHTML = '';

                variations.forEach((variasi, index) => {
                    const name = variasi.name || '';
                    const div = document.createElement('div');
                    div.classList.add('input-group', 'mb-2');
                    div.innerHTML = `
                    <input type="text" class="form-control" name="variasi[]" value="${name}" readonly>
                    <button type="button" class="btn btn-outline-danger remove-variasi" data-id="${index}">-</button>
                `;
                    variationsList.appendChild(div);
                });


                editModal.show();
            });

           
            document.getElementById('add-variasi-edit').addEventListener('click', function() {
                const input = document.getElementById('variasi-edit');
                const value = input.value.trim();
                if (value) {
                    const variationsList = document.getElementById('variasi-list-edit');
                    const div = document.createElement('div');
                    div.classList.add('input-group', 'mb-2');
                    div.innerHTML = `
                    <input type="text" class="form-control" name="variasi[]" value="${value}" readonly>
                    <button type="button" class="btn btn-outline-danger remove-variasi">-</button>
                `;
                    variationsList.appendChild(div);
                    input.value = ''; 
                }
            });

          
            document.getElementById('variasi-list-edit').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-variasi')) {
                    event.target.parentElement.remove();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const description = document.getElementById('edit-description');
            const charCount = document.getElementById('charCount');

            function updateCharCount() {
                const count = description.value.length;
                charCount.textContent = `${count}/255 characters`;
            }

            if (description && charCount) {
                description.addEventListener('input', updateCharCount);
            }

            const modal = document.getElementById('modal-edit-photo');
            modal.addEventListener('shown.bs.modal', function() {
            });
        });
    </script>

    <script>
        document.getElementById('deleteButton').addEventListener('click', function() {
            const photoId = {{ $photo->id }};

            if (confirm('Are you sure you want to delete this photo?')) {
                fetch(`/photos/${photoId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = document.referrer;
                        } else {
                            alert("Failed to delete photo: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting photo:', error);
                        alert("An error occurred while deleting the photo.");
                    });
            }
        });
    </script>
@endsection
