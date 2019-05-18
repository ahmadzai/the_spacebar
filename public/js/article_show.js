$(document).ready(function() {
  $('.js-like-article').on('click', function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);
    $link.toggleClass('fa-heart-o').toggleClass('fa-heart');

    $.ajax({
      method: 'Post',
      url: $link.attr('href')
    }).done(function(data) {
      $('.js-like-article-count').html(data.heart);
    });
  });
});
