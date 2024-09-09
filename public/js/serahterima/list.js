$(document).ready(function() {
    handlerFilter('now');
});

const handlerFilter = (type) => {
    if (type === 'all') {
        removeClass();
        $("#rangeTanggalFl").hide('slow');
        $(".filterAll").addClass("active-fl")
    }

    if (type === 'now') {
        removeClass();
        $("#rangeTanggalFl").hide('slow');
        $(".filterNow").addClass("active-fl")
    }

    if (type === 'yesterday') {
        removeClass();
        $("#rangeTanggalFl").hide('slow');
        $(".filterYesterday").addClass("active-fl")
    }

    if (type === '7') {
        removeClass();
        $("#rangeTanggalFl").hide('slow');
        $(".filter7Days").addClass("active-fl")
    }

    if (type === '30') {
        removeClass();
        $("#rangeTanggalFl").hide('slow');
        $(".filter30Days").addClass("active-fl")
    }

    if (type === 'range') {
        removeClass();
        $("#rangeTanggalFl").show('slow');
        $(".filterRange").addClass("active-fl")
    }

    if (type !== 'range') {
        ajaxRequestGetData(type, null)
    } else {
        flatpickr("#rangeTanggal", {
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr.includes("to")) {
                    ajaxRequestGetData(type, dateStr)
                }
            },
        });
    }
}

const ajaxRequestGetData = (type, dateStr) => {
    setTimeout(() => {
        $.ajax({
            url: "{{ route('getDataSerahTerima') }}",
            method: "POST",
            data: {
                type,
                dateStr
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
            },
        });
    }, 1000);
}

const initDataSerahTerima = (response) => {

}

const removeClass = () => {
    $(".filterAll").removeClass("active-fl")
    $(".filterNow").removeClass("active-fl")
    $(".filterYesterday").removeClass("active-fl")
    $(".filter7Days").removeClass("active-fl")
    $(".filter30Days").removeClass("active-fl")
    $(".filterRange").removeClass("active-fl")
    $("#rangeTanggal").val("")
}


$('body').on("click",".btn-delete",function(){
    var id = $(this).attr("id");
    $(".btn-destroy").attr("id",id);
    
    $("#modal-delete").modal("show");
});

$(".btn-destroy").on("click",function(){
    var id = $(this).attr("id")
    console.log(id);
    $.ajax({
        url: `{{ url('/serahterima/${id}') }}`,
        method : 'DELETE',
        success:function(){
            $("#modal-delete").modal("hide")
            One.helpers('jq-notify', 
            {type: 'danger', icon: 'fa fa-check me-1', message: 'Success Delete!'});
            window.location.reload();
        },
        error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        alert('Error!!');
        },
    });
})
//END DELETE

$('body').on("click",".btn-delete-anggota",function(){
    var id = $(this).attr("id");
    $(".btn-destroy-anggota").attr("id",id);
    
    $("#modal-delete-anggota").modal("show");
});



    