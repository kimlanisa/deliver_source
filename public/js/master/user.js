//Edit & Update
$('body').on("click",".btn-edit",function(){
    var id = $(this).attr("id");
    console.log(id);
    $('#form_result').html('');
    $.ajax({
        url: baseUrl + `/user/${id}/edit`,
        method: "GET",
        dataType : "json",
        success:function(html){
            $("#modal-edit").modal("show")
            $("#id").val(html.data.id)
            $("#editname").val(html.data.name)
            $("#editemail").val(html.data.email)
            $("#editrole").val(html.data.role)
            $("#edit_is_akses").val(html.data.is_akses)
        }
    });
});


$("#editForm").on("submit",function(e){
    e.preventDefault()
    var id = $("#id").val()
    var dt = $(this).serialize();
    $.ajax({
        url: baseUrl + `/user/${id}`,
        method: "PATCH",
        data: $(this).serialize(),
        success:function(){
            $('.js-dataTable').DataTable().ajax.reload();
            $("#modal-edit").modal("hide")
            One.helpers('jq-notify', 
                {type: 'success', icon: 'fa fa-check me-1', message: 'Berhasil diupdate!'});
        }
    })
})

//Edit & Update

//reset
$('body').on("click",".btn-reset",function(){
    var id = $(this).attr("id");
    $('#form_result').html('');
    $.ajax({
        url: baseUrl + `/user/${id}/edit`,
        method: "GET",
        dataType : "json",
        success:function(html){
            $("#modal-reset").modal("show")
            $("#reset_id").val(html.data.id)
            $("#reset_password").val('')
        }
    });
});


$("#resetForm").on("submit",function(e){
    e.preventDefault()
    var id = $("#reset_id").val()
    var dt = $(this).serialize();
    $.ajax({
        url: baseUrl + `/reset/${id}`,
        method: "PATCH",
        data: $(this).serialize(),
        success:function(){
            $('.js-dataTable').DataTable().ajax.reload();
            $("#modal-reset").modal("hide")
            One.helpers('jq-notify', 
                {type: 'success', icon: 'fa fa-check me-1', message: 'Berhasil direset!'});
        }
    })
})

//end reset

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
        url: baseUrl + `/user/${id}`,
        method : 'DELETE',
        success:function(){
            $("#modal-delete").modal("hide")
            $('.js-dataTable').DataTable().ajax.reload();
            One.helpers('jq-notify', 
            {type: 'danger', icon: 'fa fa-check me-1', message: 'Berhasil dihapus!'});
        },
        error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        // alert('Berhasil dihapus!');
        },
    });
})
//END DELETE


const handlerResetPassword = (event) => {
    $("#resetPassword").modal('show');
}

$("#resetPasswordForm").on("submit",function(e){
    e.preventDefault()
    var id = $("#reset_id").val()
    var dt = $(this).serialize();
    $.ajax({
        url: baseUrl + `/reset/${id}`,
        method: "PATCH",
        data: $(this).serialize(),
        success:function(){
            $("#modalresetPassword").modal("hide")
            alert('Berhasil reset password!!');
            setTimeout(() => location.reload(), 1000);
            
            // One.helpers('jq-notify', 
            //     {type: 'success', icon: 'fa fa-check me-1', message: 'Berhasil direset!'});
        }
    })
})