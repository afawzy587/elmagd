$(document).ready( function () {
    $('#departmentTable').DataTable({
        "searching": false,
        "ordering": false,
        "lengthChange": false,
        "info": false,
        "language": {
            "paginate": {
              "next": ">",
              "previous": "<"
            }
          }
    });



    // Search input name=>>  "departmentsearchInput"
  //   $('input[name="departmentsearchInput"]').keypress(function (e) {
  //     var key = e.which;
  //     if (key == 13) {
  //         // search input value =>> $(this)[0].value
  //         console.log($(this)[0].value);
  //         $('#departmentSearchform').submit();

  //         return false;
  //     }
  // });
} );
