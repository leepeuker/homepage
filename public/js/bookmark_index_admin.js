// On page load +++++++++++++++++++++++++++++++++++++++++++++++++++++

let domain = $(location).attr('protocol') + '//' + $(location).attr('hostname');

ajaxCall();

// Events +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// Reload bookmark data when the search input value changes
$("#input_searchTerm").on('input', function() {
    ajaxCall(true);
});

// Reload bookmark data when the search column select value changes
$("#select_searchColumn").on('change', function(e) {
    ajaxCall(true);
});

// Reload bookmark data when the tag chosen select value changes
$("#select_searchTag").on('change', function(e) {
    ajaxCall(true);
});

// Expand/contract the search section
$("#btn_expand").click(function() {
    
    if (!$('#select_searchTag_chosen').length) {

        $('#select_searchTag').chosen({
            placeholder_text_multiple: ' '
        });
        $('#select_searchTag').css("display", "none");
        $('#select_div').css("margin-top", "15px");
        $('#select_div').css("display", "");

    } else {

        $("#select_searchTag").chosen("destroy");
        $('#select_searchTag').css("display", "none");
        $('#select_div').css("margin-top", "0");
        $('#select_div').css("display", "none");
    }
});

// Functions ++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// Load the bookmark data from the server
function ajaxCall(reload = false, page = 1) {
    
    $.ajax({
        url: "/bookmarks/getMany?page="+ page,
        type: "POST",
        data: {
            searchTerm: $("#input_searchTerm").val(),
            searchColumn: $("#select_searchColumn").val(),
            tags: $("#select_searchTag").val()
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
                    <img src="${domain}/images/expand-more-white.png" style="color:white;height:20px;width:20px">
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
        },
        error: function () {
            $("#bookmark_list").empty();
            $("#bookmark_list").append(`Oh shit, bookmarks could not be loaded. Try to refresh the page.`);
        }
    });
}

// Generate a bookmark div
function generateBookmark(data) {

    let tagHTML = generateTagHTML(data.tags);
    
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
                    <form method="POST" action="${domain}/bookmarks/${data.id}">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="dropdown-item" type="submit" style="cursor:pointer">Delete</button>
                    </form>
                </div>
            </div>
            <h4 class="mark-title"><img src="${favicon_tag}" style="height:17px;margin-bottom:4px"> <a href="${data.url}" target="_blank" >${data.title}</a></h4>
            <small class="mark-url">${data.url}</small>
            ${tagHTML}
        </div>
    </div>`;
}

// Generate tag html for bookmark out of array of tags
function generateTagHTML(tags) {
    
    let html = '';
    let tag_names = [];

    if (tags.length > 0) {
        
        html += '<p style="margin-bottom:0px;margin-top:8px;">';

        tags.forEach(function(tag) {
            tag_names.push(tag.text);
        });

        tag_names.sort();

        tag_names.forEach(function(tag_name) {
            html += `<span class="index-tag mark-tag">${tag_name}</span>`
        });

        html += '</p>';
    }

    return html;
}

// Mark matching search string depending on which search column is selected
function markResults() {

    var search = $("input[name='search']").val();
    var searchColumn = $("#select_searchColumn").val();

    if (searchColumn == 'title') {

        $(".mark-title").unmark({
            done: function() {
                $(".mark-title").mark(search);
            }
        });

    } else {

        $(".mark-url").unmark({
            done: function() {
                $(".mark-url").mark(search);
            }
        });
    }
};