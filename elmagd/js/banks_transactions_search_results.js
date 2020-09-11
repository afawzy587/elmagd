$(document).ready( function () {
    $('#operationsTable').DataTable({
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

$('i.checkStates').on('click', function(){
    console.log($(this).hasClass('notConfirmed') );
    if($(this).hasClass('notConfirmed')){
        $(this).removeClass('notConfirmed').addClass('confirmed')
    } else return false
})

} );
