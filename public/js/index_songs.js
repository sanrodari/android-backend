$(function() {
  $('a[data-method=DELETE]').click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
      type: "POST",
      url: url,
      data: { _METHOD: "DELETE" }
    }).done(function(data) {
      // TODO Se supone que nunca ocurre error.
      location.reload();
    });
  });
});