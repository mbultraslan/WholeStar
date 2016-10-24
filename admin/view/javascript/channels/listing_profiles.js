$( document ).ready(function() {
	w = $('<div id="o"></div><div>Please wait...</div>');
	/******** SETTINGS DATA TABLE ********/
	oTable = $('#profile_list_table').dataTable( {
	  "bProcessing": true,
	  "bServerSide": true,
	  "sAjaxSource": $('#profile_list_table').attr('data-url'),
	  "sServerMethod": "POST",
	  "sPaginationType": "full_numbers",
	  
	  "oLanguage": {
	    "sLengthMenu": "Display _MENU_ profiles per page",
	    "sZeroRecords": "Nothing found - sorry",
	    "sInfo": "Showing _START_ to _END_ of _TOTAL_ profiles",
	    "sInfoEmpty": "Showing 0 to 0 of 0 products",
	    "sInfoFiltered": "(filtered from _MAX_ total profiles)"
	  },

	  "sDom": 'C<"clear">lfrtip',
	  
	  "aoColumns":
	  [
	    {
	      "sWidth": "20%",
	      "sClass": "left",
	      "fnRender" : function(o){
	    	return o.aData[0];
	       }
	    },
	    {
	      "sWidth": "55%",
	      "sClass": "left",
	      "bSortable": false ,
	      "fnRender" : function(o){
              return o.aData[1];
	      }
	    },
	    {
	    	"sWidth": "5%",
	    	"sClass": "center",
	    	"bSortable": true,
            "fnRender" : function(o){
                return o.aData[2];
            }
	    },
	    {
	    	"sWidth": "5%",
	    	"sClass": "center",
	    	"bSortable": false ,
	    	"fnRender" : function(o){
	    		if(o.aData[3] > 0) {
	    			return '<img style="height: 32px !important;" src="view/image/channels/indicators_miicard_tick.png" alt="">';
	    		} 
	    		return '';
	    	}	
	    },
	    {
	      "sWidth": "15%",
	      "bSortable": false ,
	      "fnRender" : function(o){



            var html = '<div class="listing-action btn-group pull-right">';
			html+='<a href="' + $('#profile_list_table').attr('edit-url') + '&edit=1&id=' + o.aData[4] + '" class="btn btn-info"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
			html+='<button type="button" href="' + o.aData[4] + '" class="delete-btn btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>';
            html+='<button tabindex="-1" data-toggle="dropdown" class="btn btn-success dropdown-toggle" type="button">';
            html+='<i class="fa fa-cog"></i> <span class="caret"></span>';
            html+='</button>';
            html+='<ul class="dropdown-menu dropdown-menu-right" role="menu">';
			html+='<li><a class="rename-btn" href="' + o.aData[4] + '">Rename</a></li>';
			html+='<li><a class="move-btn" data-name="' + o.aData[0] + '" href="' + o.aData[4] + '">Move All</a></li>';
            html+='<li><a class="action" data-action="revise" href="#" data-id="' + o.aData[4] + '">Revise All</a></li>';
			html+='<li><a class="action" data-action="relist" href="#" data-id="' + o.aData[4] + '">Relist All Ended</a></li>';
            html+='<li><a class="action" data-action="end" href="#" data-id="' + o.aData[4] + '">End All</a></li>';

            html+='</ul>';
            html+='</div>';






	    	return html;
	      }
	    }
	  ],
	  "fnDrawCallback": function ( ) {

		  $('.listing-action a.markdef').click(function(){
              $btn = $(this);
              $btn.closest('.listing-action').find('i').removeClass('fa-cog').addClass('fa-refresh eloading');
              $btn.closest('.dropdown-menu').hide();
			  $.get($('#profile_list_table').attr('mark-url') + '&id=' + $(this).attr('data-id'), function(data) {
				  if(data.status) {
					  oTable.fnDraw();
				  }
			  });

			  return false;
		  });
		  $('button.delete-btn').click(function(){
			  $('#delete-dialog .modal-body .alert').remove();
			  $('#delete-listing-profile-btn').data("lId", $(this).attr('href'));
			  $('#delete-dialog').modal('show');
			  return false;
		  });

		  $('a.rename-btn').click(function(){
			  $('#listing-profile-name').val('');
			  $('#listing-profile-name').parent().removeClass('has-error');
			  $('div.text-danger', $('#listing-profile-name').parent()).html('');
			  $('#rename-listing-profile-btn').data("lId", $(this).attr('href'));
			  $('#rename-dialog').modal('show');
			  return false;
		  });

		  $('a.move-btn').click(function(){
			  var select = $('#listing-profiles-list');
			  select.html('<option>Loading...</option>');
			  $('label[for="listing-profiles-list"] strong').html($(this).attr('data-name'));
			  $.get(select.attr('action') + '&id=' + $(this).attr('href'), function(data) {
				  select.html('');
				  if(data.status) {
					  for(var i=0; i < data.list.length; i++) {
						  var o = data.list[i];
						  select.append($("<option></option>")
							  .attr("value",o.id)
							  .text(o.name));
					  }
				  }
			  });


			  $('#move-listing-profile-btn').data("lId", $(this).attr('href'));
			  $('#move-dialog').modal('show');
			  return false;
		  });
		  
		  $('.listing-action a.action').click(function(){
			  $btn = $(this);
              $btn.closest('.listing-action').find('i').removeClass('fa-cog').addClass('fa-refresh eloading');
              $btn.closest('.dropdown-menu').hide();
			  $.post($('#profile_list_table').attr('action-url'), {id: $(this).attr('data-id'), action: $(this).attr('data-action')}, function(data) {
				  if(data.status) {
					  oTable.fnDraw();
				  }
			  });
			  return false;
		  });
	  }
	});

	$('#delete-listing-profile-btn').click(function(){
		$btn = $(this);
		var label = $btn.html();
		$('#delete-dialog .modal-body .alert').remove();
		var id = $(this).data('lId');
		$btn.html("Loading...");
		$.get($('#delete-dialog').attr('action') + '&id=' + id, function(data) {
			$btn.html(label);
		  if(data.status) {
			  $('#delete-dialog').modal('hide');
			  oTable.fnDraw();
		  } else {
			  var msg = $('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + data.error + ' <button type="button" class="close" data-dismiss="alert">×</button></div>');
			  $('#delete-dialog .modal-body p').before(msg);
		  }
		});

	});

	$('#rename-listing-profile-btn').click(function(){
		$btn = $(this);
		var label = $btn.html();
		var id = $(this).data('lId');
		if($('#listing-profile-name').val().length) {
			$btn.html("Loading...");
			$.post($('#rename-dialog').attr('action'), {"id" : id, "name" : $('#listing-profile-name').val()}, function(data) {
				$('#rename-dialog').modal('hide');
				$btn.html(label);
				if(data.status) {
					oTable.fnDraw();
				}
			});
		} else {
			$('#listing-profile-name').parent().addClass('has-error');
			$('div.text-danger', $('#listing-profile-name').parent()).html('Name is required!');
			//validate
		}

	});


	$('#move-listing-profile-btn').click(function(){
		$btn = $(this);
		var label = $btn.html();
		var id = $(this).data('lId');
		if($('#listing-profiles-list').val().length) {
			$btn.html("Loading...");
			$.post($('#move-dialog').attr('action'), {"id" : id, "to_id" : $('#listing-profiles-list').val()}, function(data) {
				$('#move-dialog').modal('hide');
				$btn.html(label);
				if(data.status) {
					oTable.fnDraw();
				}
			});
		}

	});
	
});