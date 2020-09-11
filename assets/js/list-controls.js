$(document).ready(function(){
	
    $('i.delete').click(function(e){
        e.preventDefault();
		var table            = $('#table').val();
		var permission       = $('#permission').val();
		var directon         = $('#page').val();
		var id               = $(this).attr('id').replace("item_","");
        var page             = "settings_js.php?do=delete";
		if (id != 0)
		{
			$.ajax( {
				async :true,
				type :"POST",
				url :page,
				data: "&id=" + id + "&permission="+permission+"&table="+table,
				success : function(responce) {
                    if(responce == 100)
                     {
						 if(typeof  directon == "undefined")
						 {
							$("#tr_"+id).animate({height: 'auto', opacity: '0.2'}, "slow");
							$("#tr_"+id).animate({width: 'auto', opacity: '0.9'}, "slow");
							$("#tr_"+id).animate({height: 'auto', opacity: '0.2'}, "slow");
							$("#tr_"+id).animate({width: 'auto', opacity: '1'}, "slow");
							$("#tr_"+id).fadeTo(400, 0, function () { $("#tr_" + id).slideUp(400);}); 
						 }else
						 {
							 window.location = directon+".php";
						 }
						
                      }
				},
				error : function() {
					return true;
				}
			});
		}
	});
	
$('.add_other').click(function() {
        $('[name="add_other"]').val('1');
    });	

	
});
