$( document ).ready(function() {
	w = $('<div id="o"></div><div>Please wait...</div>');
	cid = null;
	
	
	/******** SETTINGS DATA TABLE ********/
	oTable = $('#templates_list_table').dataTable( {
	  "bProcessing": true,
	  "bServerSide": true,
	  "sAjaxSource": $('#templates_list_table').attr('data-url'),
	  "sServerMethod": "POST",
	  "sPaginationType": "full_numbers",
	  "iDisplayLength": 25,
	  
	  "oLanguage": {
	    "sLengthMenu": "Display _MENU_ templates per page",
	    "sZeroRecords": "Nothing found - sorry",
	    "sInfo": "Showing _START_ to _END_ of _TOTAL_ templates",
	    "sInfoEmpty": "Showing 0 to 0 of 0 templates",
	    "sInfoFiltered": "(filtered from _MAX_ total templates)"
	  },

	  "sDom": 'C<"clear">lfrtip',
	  
	  "aoColumns":
	  [
	    {
	      "sWidth": "85%",
	      "sClass": "left"
	    },
	    {
	      "sWidth": "15%",
	      "sClass": "right",
	      "bSortable": false ,
	      "fnRender" : function(o){
	    	  var html = '<a href="' + o.aData[1] + '" class="button edit_template">Edit</a>';
	    	  html += '&nbsp;';
	    	  html += '<a href="' + o.aData[1] + '" class="button delete_template">Delete</a>';
	    	  return html;
	      }
	    }
	  ],
	  "fnDrawCallback": function ( ) {
		  $('#templates_list_table .edit_template').click(function() {
			  window.location = $('#templates_list_table').attr('data-edit') + "&id=" + $(this).attr('href');
			  return false;
		  });
		  
		  $('#templates_list_table .delete_template').click(function() {
			  if (confirm("Do you wont to delete this template!") == true) {
				  $.post($('#templates_list_table').attr('data-delete'), {'id' : $(this).attr('href')}, function() {
					  oTable.fnDraw(); 
				  });
			    }
			  
			  return false;
		  });
	  }
	});
	
});