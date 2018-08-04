let current_page = 1;

ajaxCall();

$("#input_searchTerm").on('input', function() {
    $("#bookmark_list").empty();
    current_page = 1;
    ajaxCall();
});

$("#select_searchTerm").on('change', function(e) {
    current_page = 1;
    $("#bookmark_list").empty();
    ajaxCall();
});

$("#btn_ajax").click(function() {
    alert();
    ajaxCall();
});


$("#select_searchColumn").on('change', function(e) {

    if (this.value == 'keywords') {
        $('#select_searchTerm').chosen({
            placeholder_text_multiple: ' '
        });
        $('#select_searchTerm').css("display", "none");
        $('#input_searchTerm').css("display", "none");
        $('#input_searchTerm').css("display", "none");
    } else {
        $("#select_searchTerm").chosen("destroy");
        $('#select_searchTerm').css("display", "none");
        $('#input_searchTerm').css("display", "");
    }
    
    current_page = 1;

    $("#bookmark_list").empty();
    ajaxCall();
});

function ajaxCall() {
    
    $.ajax({
        url: "/bookmarks/getMany?page="+ current_page,
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
            
            $("#btn_more").remove();

            for (i = 0; i < bookmarks.data.length; i++) { 
                $("#bookmark_list").append( generateBookmark(bookmarks.data[i]));
            }

            if (current_page < bookmarks.last_page) {
                
                $("#bookmark_list").append(`
                <div class="container">
                    <div class="row justify-content-center">
                        <button type="button" class="btn btn-dark float-right" title="Load more bookmarks" onclick="ajaxCall()" id="btn_more">
                            <img src="${$(location).attr('protocol')}//${$(location).attr('hostname')}/images/expand.png" style="color:white; width:20px">
                        </button>
                    </div>
                </div>`);
            }

            current_page++;
        }
    });
}

function generateBookmark(data) {

    let keywordString = '';
    let keywords = [];

    data.keywords.forEach(function(keyword) {
        keywords.push(keyword.word);
    });

    keywords.sort();

    keywords.forEach(function(keyword) {
        keywordString += `<span class="index-keyword">${keyword}</span>`
    });

    return bookmarkCard = `
    <div class="card" style="background-color: rgba(255,255,255,0.5);">
        <div class="card-body" style="display:inline">
            <a class="btn btn-light btn-sm float-right" title="Edit this bookmark" href="${$(location).attr('protocol')}//${$(location).attr('hostname')}/bookmarks/${data.id}/edit" style="border:1px solid rgba(0, 0, 0, 0.125);"><img src="${$(location).attr('protocol')}//${$(location).attr('hostname')}/images/menu.png" style="height:18px"></a>
            <form method="POST" action="${$(location).attr('protocol')}//${$(location).attr('hostname')}/bookmarks/${data.id}"">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" title="Delete this bookmark" class="btn btn-danger btn-sm float-right" style="color:white;margin-right:10px"><img src="${$(location).attr('protocol')}//${$(location).attr('hostname')}/images/delete-white.png" style="height:18px"></button>
            </form>
            <h3><a href="${data.url}" target="_blank">${data.title}</a></h3>
            <small>${data.url}</small>
            <p style="margin-bottom:0px;margin-top:8px;">${keywordString}</p>
        </div>
    </div>
    <br>`;
}