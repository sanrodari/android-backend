$(function() {
  $('form#edit-form').submit(function(e) {
    var password             = $('input#password').val();
    var passwordConfirmation = $('input#passwordConfirmation').val();
    
    if(password == passwordConfirmation) {
      return true;
    }
    else {
      alert('No coinciden las contraseñas.');
      return false;
    }
  });
});