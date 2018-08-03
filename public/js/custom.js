$(document).ready(function() {

    ajaxCall();
    
    $("#input_searchTerm").on('input', function() {
        ajaxCall();
    });
    
    $("#select_searchTerm").on('change', function(e) {
        ajaxCall();
    });

    $("#select_searchColumn").on('change', function(e) {

        if (this.value == 'keywords') {
            $('#select_searchTerm').chosen();
            $('#select_searchTerm').css( "display", "none" );
            $('#input_searchTerm').css( "display", "none" );
            $('#input_searchTerm').css( "display", "none" );
        } else {
            $("#select_searchTerm").chosen("destroy");
            $('#select_searchTerm').css( "display", "none" );
            $('#input_searchTerm').css( "display", "" );
        }
        
        ajaxCall();
    });
});

function generateBookmark(data) {
    
    return bookmarkCard = `
    <div class="card" style="background-color: rgba(255,255,255,0.5);">
        <div class="card-body" style="display:inline">
            <a class="btn btn-info float-right" href="${$(location).attr('protocol')}//${$(location).attr('hostname')}/bookmarks/${data.id}/edit" style="color:white;margin-top:10px">Edit</a>
            <form method="POST" action="${$(location).attr('protocol')}//${$(location).attr('hostname')}/bookmarks/${data.id}"">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger float-right" style="color:white;margin-top:10px;margin-right:10px">Delete</button>
            </form>
            <h3><a href="${data.url}" target="_blank">${data.title}</a></h3>
            <small>${data.url}</small>
            <p style="margin-bottom:0px;margin-top:5px;cursor:pointer">github &nbsp nodejs &nbsp rest-api</p>
        </div>
    </div>
    <br>`;
}

function ajaxCall() {
    $.ajax({
        url: "/bookmarks/getMany",
        type: "POST",
        data: {
            searchTerm: $("#input_searchTerm").val(),
            keywords: $("#select_searchTerm").val(),
            searchColumn: $("#select_searchColumn").val()
        },
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "JSON",
        success: function (bookmarks) {
            $("#test1").empty();
            for (i = 0; i < bookmarks.data.length; i++) { 
                $("#test1").append( generateBookmark(bookmarks.data[i]));
            }
        }
    });
}