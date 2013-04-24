$(function() {
  $('a[data-close-session]').click(function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
      type: "POST",
      url: url,
      data: { _METHOD: "DELETE" }
    }).done(function(data) {
      location.reload();
    });
  });
});