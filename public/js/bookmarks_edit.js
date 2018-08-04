$('#select_keywords').chosen();

$("#select_keywords_chosen").keydown(function(e) {

    if (e.keyCode == 32) {

        let newKeyword = $('.chosen-search-input').val().trim();
        let selectedKeywords = $("#select_keywords").val();
        
        selectedKeywords.push('__'+newKeyword);
        
        if($('#select_keywords option[value="__'+ newKeyword +'"]').length == 0) {

            $("#select_keywords").append('<option value="__'+ newKeyword +'">'+ newKeyword +'</option>');
        }

        $("#select_keywords").val(selectedKeywords);
        $("#select_keywords").trigger("chosen:updated");

        test = $("#select_keywords").val();
    }
});