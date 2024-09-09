//Edit & Update
$('body').on("click",".btn-edit-ex",function(){
    var id = $(this).attr("id");
    $('#form_result').html('');
    $.ajax({
        url: baseUrl + `/expedisi/${id}/edit`,
        method: "GET",
        dataType : "json",
        success:function(html){
            $("#modal-edit").modal("show")
            $("#id").val(html.data.id)
            $("#editexpedisi").val(html.data.expedisi)
            $("#editcolor").val(html.data.color)
            // $('.select-update').val(html.data.prefix).trigger('change')
            const selectUpdate = $('.select-update');
            html.data?.prefix?.forEach(function(option) {
                selectUpdate.append($('<option>', {
                    value: option,
                    text: option
                }));
            });
            
            selectUpdate.val(html.data.prefix ?? []).trigger('change')
        }
    });
});


$("#editFormEx").on("submit",function(e){
    e.preventDefault()
    var id = $("#id").val()
    var dt = $(this).serialize();
    const form = new FormData(this)
     var data = {
                expedisi: form.get('expedisi'),
                color: form.get('color'),
                prefix: form.getAll('prefix')
            }
    $.ajax({
        url: baseUrl + `/expedisi/${id}`,
        method: "PATCH",
        data: data,
        success:function(response){
            if (response.success) {
                $("#modal-edit").modal("hide")
                $('.js-dataTable').DataTable().ajax.reload();
                One.helpers('jq-notify', {
                    type: 'success',
                    icon: 'fa fa-check me-1',
                    message: response.message
                });
            } else {
                One.helpers('jq-notify', {
                    type: 'danger',
                    icon: 'fa fa-times me-1',
                    message: response.message
                });
            }
        }
    })
})



//DELETE
$('body').on("click",".btn-delete",function(){
    var id = $(this).attr("id");
    $(".btn-destroy").attr("id",id);
    $("#destroy-modalLabel").text("Yakin Hapus Data :" +id);
    $("#modal-delete").modal("show");
});

$(".btn-destroy").on("click",function(){
    var id = $(this).attr("id")
    console.log(id);
    $.ajax({
        url: baseUrl + `/expedisi/${id}`,
        method : 'DELETE',
        success:function(){
            $("#modal-delete").modal("hide")
            $('.js-dataTable').DataTable().ajax.reload();
            One.helpers('jq-notify', 
            {type: 'danger', icon: 'fa fa-check me-1', message: 'Berhasil dihapus!'});
        },
        error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        alert('Gagal menghapus!!');
        },
    });
})
//END DELETE