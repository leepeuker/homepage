// On page load +++++++++++++++++++++++++++++++++++++++++++++++++++++

let domain = $(location).attr('protocol') + '//' + $(location).attr('hostname');

ajaxCall();

// Events +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$("#input_searchTerm").on('input', function() {
    ajaxCall(true);
});

$("#select_searchTerm").on('change', function(e) {
    ajaxCall(true);
});

$("#btn_ajax").click(function() {
    ajaxCall();
});

$("#select_searchColumn").on('change', function(e) {

    if (this.value == 'tags') {
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

    ajaxCall(true);
});

// Functions ++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function ajaxCall(reload = false, page = 1) {
    
    $.ajax({
        url: "/bookmarks/getMany?page="+ page,
        type: "POST",
        data: {
            searchTerm: $("#input_searchTerm").val(),
            tags: $("#select_searchTerm").val(),
            searchColumn: $("#select_searchColumn").val()
        },
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "JSON",
        success: function (bookmarks) {

            if (reload) {
                $("#bookmark_list").empty();
            }

            $("#resultManaging").remove();

            for (i = 0; i < bookmarks.data.length; i++) { 
                $("#bookmark_list").append(generateBookmark(bookmarks.data[i]));
            }

            let moreBtn = '';

            if (page < bookmarks.last_page) {
                moreBtn = `
                <button type="button" class="btn btn-dark" title="Load more bookmarks" onclick="ajaxCall(false, ${page+1})" id="btn_more">
                    <img src="${domain}/images/expand.png" style="color:white;height:20px;width:20px">
                </button>`;
            }

            $("#bookmark_list").append(`
            <div class="container" id="resultManaging">
                <div class="row">
                    <div class="col-sm" style="padding-left:2px">
                        <small class="float-left">Displaying ${bookmarks.to} from ${bookmarks.total} bookmarks</small>
                    </div>
                    <div class="col-sm text-center">
                        ${moreBtn} 
                    </div>
                    <div class="col-sm"></div>
                </div>
            </div>`);
            
            markResults();
        }
    });
}

function generateBookmark(data) {

    let tagString = '';
    let tags = [];

    // Generate tags string
    if (data.tags.length > 0) {
        
        tagString += '<p style="margin-bottom:0px;margin-top:8px;">';

        data.tags.forEach(function(tag) {
            tags.push(tag.text);
        });

        tags.sort();

        tags.forEach(function(tag) {
            tagString += `<span class="index-tag">${tag}</span>`
        });

        tagString += '</p>';
    }
    
    //Generate favicon link
    let favicon_tag = '';
    
    if (data.favicon) {
        favicon_tag = `${domain}/storage/favicons/${data.favicon}`;
    } else {
        favicon_tag = `${domain}/images/default-icon.png`;
    }

    return bookmarkCard = `
    <div class="card" style="background-color: rgba(255,255,255,0.5);margin-bottom:15px">
        <div class="card-body" style="display:inline">
            <div class="dropdown">
                <button class="btn btn-light btn-sm float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border:1px solid rgba(0, 0, 0, 0.125);">
                    <img src="${domain}/images/menu.png" style="height:18px">
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="${domain}/bookmarks/${data.id}/edit">Edit</a>
                    <form method="POST" action="${domain}/bookmarks/${data.id}"">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="dropdown-item" type="submit" style="cursor:pointer">Delete</button>
                    </form>
                </div>
            </div>
            <h4 class="bookmark-title"><img src="${favicon_tag}" style="height:17px;margin-bottom:4px"> <a href="${data.url}" target="_blank" >${data.title}</a></h4>
            <small class="bookmark-url">${data.url}</small>
            ${tagString}
        </div>
    </div>`;
}



function markResults() {

    var search = $("input[name='search']").val();
    var searchColumn = $("#select_searchColumn").val();

    switch (searchColumn) {
        
        case 'title':

            $(".bookmark-title").unmark({
                done: function() {
                    $(".bookmark-title").mark(search);
                }
            });

            break;

        case 'url':

            $(".bookmark-url").unmark({
                done: function() {
                    $(".bookmark-url").mark(search);
                }
            });
            
            break;
    
        default:
            break;
    }
};