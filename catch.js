function loadSuggestions() {
    var data = $("#searchbar").val();

    $.ajax({
        type: 'POST',
        url: 'FindSuggestions.asmx/searchPrefix',
        contentType: "application/json; charset=utf-8",
        data: '{ prefix: "' + data + '"}',      
        dataType: 'json',
        success: function (msg) {
            $("#results").empty();

            if (data.length > 0) {
                var results = msg.d.split('%');
                var html = "";

                $.each(results, function (index, value) {
                    html = html + value + '<br>';
                });

                $("#results").html(html);
            }
        }
    });
}