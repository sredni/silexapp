$(document).ready(function () {
  $('#form form').submit(function (event) {
    window.location.href = '/' + $('#form form #query').val();
    
    event.preventDefault();
  });
  
  $('#form form #reset').click(function () {
    $('#form form #query').val(null);
    $('#form form').submit();
    
    event.preventDefault();
  });
});