$('#select_keywords').chosen({
    no_results_text: 'Press spacebar to add new keyword:',
    search_contains : true
});

$("#select_keywords_chosen").keydown(function(e) {
    
    if (e.keyCode == 32) {

        let newKeyword = $('.chosen-search-input').val().trim();
        let selectedKeywords = $("#select_keywords").val();

        if (newKeyword.trim() !== '') {

            let optionValue = optionTextExists(newKeyword);
            
            if (!optionValue) {
                
                selectedKeywords.push('__' + newKeyword);

                if($('#select_keywords option[value="__'+ newKeyword +'"]').length == 0) {

                    $("#select_keywords").append('<option value="__'+ newKeyword +'">'+ newKeyword +'</option>');
                }

            } else {
                
                selectedKeywords.push(optionValue);
            }

            $("#select_keywords").val(selectedKeywords);
            $("#select_keywords").trigger("chosen:updated");

            test = $("#select_keywords").val();

        }
    }
});

// Returns value of option with text matching the keyword or false if no match was found
function optionTextExists(keyword) {

    let optionExists = false;
    
    $('#select_keywords option').filter(function() {

        if ($(this).text() === keyword) {
            optionExists = $(this).val();
        }

    });

    return optionExists;
}