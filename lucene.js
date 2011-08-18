(function ($) {
    $(function () {
        $('form').submit(function (e) {
            $result = $('#result');
            $result.html('');
            $.getJSON(
                'find.php',
                $(this).serializeArray(),
                function (json) {
                    var html = '<ul>';
                    for (var i = 0; i < json.length; i ++) {
                        html += '<li><a href="html/';
                        html += json[i].filename + '">';
                        html += json[i].id + ' - ';
                        html += json[i].score + ' - ';
                        html += json[i].title + '</a></li>';
                    }
                    html += '</ul>';
                    $result.html(html);
                }
            );
            e.preventDefault();
        });
    });
})(jQuery);