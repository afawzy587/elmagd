$(document).ready( function () {
    $('#usersTable').DataTable({
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



    // Search input name=>>  "usersSearch"

} );
