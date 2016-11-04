$(document).ready(function() {
    /* Search */
    $('.blog-button-search').bind('click', function() {
        url = $('base').prop('href') + 'index.php?route=mblog/search';

        var search = $('input[name=\'blog-search\']').prop('value');

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        location = url;
    });

    $('.blog_search input[name=\'blog-search\']').bind('keydown', function(e) {
        if (e.keyCode == 13) {
            url = $('base').prop('href') + 'index.php?route=mblog/search';

            var search = $('input[name=\'blog-search\']').prop('value');

            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }

            location = url;
        }
    });
    $('.blog-button-search-mb').bind('click', function() {
        url = $('base').prop('href') + 'index.php?route=mblog/search';

        var search = $('input[name=\'blog_search_mb\']').prop('value');

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        location = url;
    });

    $('.blog-search-mb input[name=\'blog_search_mb\']').bind('keydown', function(e) {
        if (e.keyCode == 13) {
            url = $('base').prop('href') + 'index.php?route=mblog/search';

            var search = $('input[name=\'blog_search_mb\']').prop('value');

            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }

            location = url;
        }
    });
    $('.breadcrumb a').last().addClass('last');
    
});