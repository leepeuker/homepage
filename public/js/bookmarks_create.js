$('#select_tags').chosen({
    no_results_text: 'Press spacebar to add new tag:',
    search_contains : true
});

$("#select_tags_chosen").keydown(function(e) {
    
    if (e.keyCode == 32) {

        let newTag = $('.chosen-search-input').val().trim();
        let selectedTags = $("#select_tags").val();

        if (newTag.trim() !== '') {

            let optionValue = optionTextExists(newTag);
            
            if (!optionValue) {
                
                selectedTags.push('__' + newTag);

                if($('#select_tags option[value="__'+ newTag +'"]').length == 0) {

                    $("#select_tags").append('<option value="__'+ newTag +'">'+ newTag +'</option>');
                }

            } else {
                
                selectedTags.push(optionValue);
            }

            $("#select_tags").val(selectedTags);
            $("#select_tags").trigger("chosen:updated");

            test = $("#select_tags").val();

        }
    }
});

// Returns value of option with text matching the tag or false if no match was found
function optionTextExists(tag) {

    let optionExists = false;
    
    $('#select_tags option').filter(function() {

        if ($(this).text() === tag) {
            optionExists = $(this).val();
        }

    });

    return optionExists;
}