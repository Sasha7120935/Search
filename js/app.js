jQuery(document).ready(function ($) {
    $('#search_keyword').keyup(function () {
        let inputValue = $(this).val();
        if (inputValue !== '') {
            GetSuggestion(inputValue);
        }
    })

    function GetSuggestion(keyword) {
        let formData = {
            'action': 'affordance_search_data',
            'keyword': keyword
        }
        $.ajax({
            type: "post",
            url: ajax_search.ajax_url,
            data: formData,
            success: function (data) {
                setTimeout(function () {
                    $('.search-suggestion').html(data);
                }, 1000);
            }
        });
    }
});