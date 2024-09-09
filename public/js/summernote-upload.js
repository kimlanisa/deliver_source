function initializeSummernote() {
    summernote_menu.map((target) => {
        $(target).summernote({
            height: heightRow,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['table', 'picture']],
                ['Misc', ['codeview', 'fullscreen']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    const id = target.split('_',)?.[1] ?? ''
                    for (let i = 0; i < files.length; i++) {
                        console.log(files)
                        $.upload(files[i], uploadUrl, target, id);
                    }
                },
                onMediaDelete: function(target) {
                    $.delete(target[0].src, deleteUrl);
                }
            },
        });
    });
}

 $.upload = function(file, url_tambah, target, id) {
    console.log('img')
    let out = new FormData();
    out.append('id', id)
    out.append('file', file, file.name);
    $.ajax({
        method: 'POST',
        url: url_tambah,
        contentType: false,
        cache: false,
        processData: false,
        data: out,
        success: function(img) {
            $(target).summernote('insertImage', img);
        },
        error: function(err) {
            alert('Gambar tidak di izinkan!');
        }
    });
};

$.delete = function(src, url_delete) {
    $.ajax({
        method: 'POST',
        url: url_delete,
        cache: false,
        data: {
            src: src,
        },
        success: function(response) {
            alert(response.message);
        },
    });
};

