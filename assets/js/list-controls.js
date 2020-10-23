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


	function load_unseen_notification(view = '')
	 {
		  var page ="notification_js.php?do=fetch";
		  jQuery.ajax({
			async :true,
			type :"POST",
			dataType: "json",
			url :page,
			data: {view:'view'},
			success : function(responce) {
				$('.notifiDropdown').html(responce.notification);
				if(responce.unseen_notification > 0)
				{
				  $('.count').html(responce.unseen_notification);
				}
		   }
		  });
	 }
	load_unseen_notification();

	function load_seen_notification(view = '')
	 {
		  var page ="notification_js.php?do=view";
		  jQuery.ajax({
		    async :true,
			type :"POST",
			dataType: "json",
			url :page,
			data: {view:'view'},
			success : function(responce) {
//				$('.dropdown-menu').html(responce.notification);
//				if(responce.unseen_notification > 0)
//				{
//				 $('.count').attr({'data-count':responce.unseen_notification});
//				}
		   }
		  });
	 }

    $('.notifiDropdown').on('click','a.read',function ()
	 {
         var id = $(this).attr('id');
		  var page ="notification_js.php?do=read";
		  jQuery.ajax({
		    async :true,
			type :"POST",
			dataType: "json",
			url :page,
			data: {id:id},
			success : function(responce) {

		   }
		  });
    });


	$('.notificationIcon').click(function(){
		  $('.count').empty();
		  load_seen_notification('yes');
	 });

	 setInterval(function(){
	  load_unseen_notification();;
	 }, 5000);

	$('.notifiDropdown').on('click','a.delete_reminders',function ()
	 {
         var id = $(this).attr('id');
		  var page ="notification_js.php?do=delete";
		  jQuery.ajax({
		    async :true,
			type :"POST",
			dataType: "json",
			url :page,
			data: {id:id},
			success : function(responce) {
				if(responce == 100)
                     {
							$("a#"+id).animate({height: 'auto', opacity: '0.2'}, "slow");
							$("a#"+id).animate({width: 'auto', opacity: '0.9'}, "slow");
							$("a#"+id).animate({height: 'auto', opacity: '0.2'}, "slow");
							$("a#"+id).animate({width: 'auto', opacity: '1'}, "slow");
							$("a#"+id).fadeTo(400, 0, function () { $("a#" + id).slideUp(400);});
                      }

		   }
		  });
    });
	 
});
