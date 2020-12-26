$( '.js-click-post' ).click(function(e) {
    e.preventDefault; // Prevent the default behavior of the  element.
    var article = $(this).closest('.article'); // Find the parent .post element
    var articleId = article.attr('id'); // Get the post ID from our data attribute
    registerPostClick(articleId); // Pass that ID to our function.
});
function registerPostClick(articleId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })
    $.ajax({
        type: 'post',
        dataType: 'JSON',
        url: '/article/' + articleId + '/click',
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(JSON.stringify(xhr.responseText));
        }
    });
}
