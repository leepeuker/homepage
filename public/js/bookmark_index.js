let current_page = 1;

ajaxCall();

$("#input_searchTerm").on('input', function() {
    current_page = 1;
    $("#test1").empty();
    ajaxCall();
});

$("#select_searchTerm").on('change', function(e) {
    current_page = 1;
    $("#test1").empty();
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
    $("#test1").empty();
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
            console.log(bookmarks.last_page);
            $("#btn_more").remove();

            for (i = 0; i < bookmarks.data.length; i++) { 
                $("#test1").append( generateBookmark(bookmarks.data[i]));
            }

            if (current_page < bookmarks.last_page) {
                
                $("#test1").append('<button type="button" class="btn btn-dark float-right" onclick="ajaxCall()" id="btn_more"><img src="http://blog.local/images/expand.png" style="color:white; width:20px"></img></button>');
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
            <a class="btn btn-info float-right" href="${$(location).attr('protocol')}//${$(location).attr('hostname')}/bookmarks/${data.id}/edit" style="color:white;margin-top:10px">Edit</a>
            <form method="POST" action="${$(location).attr('protocol')}//${$(location).attr('hostname')}/bookmarks/${data.id}"">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger float-right" style="color:white;margin-top:10px;margin-right:10px">Delete</button>
            </form>
            <h3><a href="${data.url}" target="_blank">${data.title}</a></h3>
            <small>${data.url}</small>
            <p style="margin-bottom:0px;margin-top:10px;">${keywordString}</p>
        </div>
    </div>
    <br>`;
}