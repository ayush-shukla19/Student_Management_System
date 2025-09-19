// Minimal jQuery-powered interactions. Ensure jQuery is loaded where used.
$(document).ready(function(){
  // Example: live search for students (requires an endpoint /admin/search_students.php)
  $('#student_search').on('input', function(){
    var q = $(this).val();
    $.get('admin/search_students.php', {q:q}, function(data){
      $('#student_table_body').html(data);
    });
  });
});
