$(document).ready(function() {
    $('.js-like-article').on('click', function(e) {
        e.preventDefault();
        var $link = $(e.currentTarget);
        $link.toggleClass('fa-star-o').toggleClass('fa-star');
        $.ajax({
           method: 'POST',
           url: $link.attr('href')
        }).done(function (data) {
            $('.js-like-article-count').html(data.likes);
        })

    });
});

