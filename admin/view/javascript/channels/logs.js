$( document ).ready(function() {
	w = $('<div id="o"></div><div>Please wait...</div>');
	cid = null;
	
	
	/******** SETTINGS DATA TABLE ********/
	oTable = $('#logs_list_table').dataTable( {
	  "bProcessing": true,
	  "bServerSide": true,
	  "sAjaxSource": $('#logs_list_table').attr('data-url'),
	  "sServerMethod": "POST",
	  "sPaginationType": "full_numbers",
	  "iDisplayLength": 25,
	  "order": [[ 4, "desc" ]],
	  
	  "oLanguage": {
	    "sLengthMenu": "Display _MENU_ logs per page",
	    "sZeroRecords": "Nothing found - sorry",
	    "sInfo": "Showing _START_ to _END_ of _TOTAL_ logs",
	    "sInfoEmpty": "Showing 0 to 0 of 0 logs",
	    "sInfoFiltered": "(filtered from _MAX_ total logs)"
	  },

	  "sDom": 'C<"clear">lfrtip',
	  
	  "aoColumns":
	  [
	    {
	      "sWidth": "10%",
	      "sClass": "left"
	    },
	    {
	      "sWidth": "5%",
	      "sClass": "left"
	    },
	    {
	      "sWidth": "10%",
	      "sClass": "left"
	    },		    
	    {
	      "sWidth": "55%",
	      "sClass": "left"
	    },
	    {
	      "sWidth": "10%",
	      "sClass": "center"
	    },
	    {
	      "sWidth": "10%",
	      "sClass": "center"
	    }
	  ],
	  "fnDrawCallback": function ( ) {
		  
	  }
	});
	oTable.fnSort( [ [4,'desc'] ] );
	
	//setInterval(function(){oTable.fnDraw();}, 3000);
	
	
	$('#clear-btn').click(function() {
		var btn = $(this);
		var label = btn.html();
		btn.html('Loading...');
		$.get(btn.attr('href'), function(){
			oTable.fnDraw();
			btn.html(label);
		});
		return false;
	});
	
});