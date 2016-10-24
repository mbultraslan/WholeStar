$( document ).ready(function() {
	w = $('<div id="o"></div><div>Please wait...</div>');
	
	ck = ''; // use in fnRender checkboxes
	/******** SETTINGS DATA TABLE ********/
	oTable = $('#items_table').dataTable( {
	  "bProcessing": true,
	  "bServerSide": true,
	  "iDisplayLength": 20,
      "aLengthMenu": [20, 50, 100, 200],
	  "sAjaxSource": $('#items_table').attr('data-url'),
	  "sServerMethod": "POST",
	  "sPaginationType": "full_numbers",
	  
	  "oLanguage": {
	    "sLengthMenu": "Display _MENU_ products per page",
	    "sZeroRecords": "Nothing found - sorry",
	    "sInfo": "Showing _START_ to _END_ of _TOTAL_ Items",
	    "sInfoEmpty": "Showing 0 to 0 of 0 products",
	    "sInfoFiltered": "(filtered from _MAX_ total Items)"
	  },

	  "sDom": 'C<"clear">lfrtip',
	  
	  "aoColumns":
	  [
	    {
	      "sWidth": "1%",
	      "sClass": "center",
	      "bSortable": false ,
	      "fnRender" : function(o){return '<input class="sc" type="checkbox" value="'+o.aData[0]+'"'+ck+'>';}
	    },
	    {
	      "sWidth": "2%",
	      "sClass": "center",
	      "bSortable": false ,
	      "fnRender" : function(o){return '<img src="' + o.aData[1] + '" width="40px" height="40px"  style="padding: 1px; border: 1px solid #DDDDDD;">';}
	    },
	    {
	    	"sWidth": "33%",
	    	"sClass": "left valign-top detail",
	    	"fnRender" : function(o){
	    		var obj = jQuery.parseJSON(o.aData[2]);
	    		console.log(obj.qty_diff);
	    		var html = '<table class="' + ((obj.have_link)? ' suc ' : '') + ((obj.qty_diff)? ' qtydiff ' : '') +  '">';
	    		html += '<tr>';
	    		html += '<td>eBay</td>';
	    		html += '<td>' 
	    			 + '<div class="title">' + obj.title + '</div>'
	    			 + '<div><stong>eBayID:</strong><a href="' + obj.link + '" target="_blank"> ' + obj.id +  '</a></div>'
	    			 + '</td>';
	    		html += '</tr>';
	    		
	    		if(obj.have_link) {
	    			html += '<tr>';
		    		html += '<td>Local</td>';
		    		html += '<td>' 
		    			 + '<div class="title">' + obj.product_name + '</div>'
		    			 + '<div><stong>ProductID:</strong><a href="' + obj.product_link + '" target="_blank"> ' + obj.product_id +  '</a></div>'
		    			 + '</td>';
		    		html += '</tr>';
	    		} else {
	    			html += '<tr>';
		    		html += '<td>Local</td>';
		    		html += '<td>' 
		    			 + '<input type="text" class="product">'
		    			 + '<a href="' + obj.id + '" class="save">Save</a>'
		    			 + '</td>';
		    		html += '</tr>';
	    		}
	    		
	    		html += '</table>';

	    		return html;
	    	}
	    },
	    {
	    	"sWidth": "10",
	    	"sClass": "right",
	    	"fnRender" : function(o){return o.aData[3];}
	    },
	    
	    {
	    	"sWidth": "10",
	    	"sClass": "right",
            "bSortable": false ,
	    	"fnRender" : function(o){return o.aData[4];}
	    },
	    
	    {
	    	"sWidth": "10",
	    	"sClass": "right qty",
	    	"fnRender" : function(o){return o.aData[5];}
	    },
	    
	    {
	    	"sWidth": "10",
	    	"sClass": "right qty",
            "bSortable": false ,
	    	"fnRender" : function(o){return o.aData[6];}
	    },
	    
	    {
	    	"sWidth": "10",
	    	"sClass": "right",
            "bSortable": false ,
	    	"fnRender" : function(o){
	    		var obj = o.aData[7];
	    		var html = '';
	    		if(!obj.have_link) {
	    			html += '<a href="' + obj.item_id + '" class="import btn btn-primary pull-right"><i class="fa fa-download"></i> Import</a>';
	    		} else {
	    			html += '<a href="' + obj.product_id + '" class="remove_link btn btn-danger pull-right"><i class="fa fa-eraser"></i> Remove Link</a>';
	    		}
	    		return html;
	    	}
	    }
	   
	  ]

	  , "fnServerParams": function ( aoData ) {
            aoData.push({"name": "item_id","value": $('#search_input').val()});
	  },
	  
	  "fnDrawCallback": function ( ) {
	      $("input.product" ).autocomplete({
	    	  source: function(request, response) {
	              $.ajax({
	                url: $('#items_table').attr('data-ebay-action') + "&action=product_search&query=" +  encodeURIComponent(request.term),
	                dataType: 'json',
	                type: 'POST',
	                data: 'query=' +  encodeURIComponent(request.term),
	                success: function(json) {
	                    response($.map(json.data, function(item) {
	                        return {
	                            label: item.name,
	                            value: item.product_id
	                        }
	                    }));
	                },
	                error: function (xhr, ajaxOptions, thrownError) {
	                  if (xhr.status != 0) {
	                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	                  }
	                }
	              });
	            },
	            select: function(event, ui) {
	                $p = $(this).parent();
	            	$(this).val(ui.item.label);
	                $('a', $p).attr('product-id', ui.item.value);
	                
	                
//	                var elementId = $(this).attr('id');
//	                getProductStock(ui.item.value, elementId);
//	                $('#'+elementId+'_pid').val(ui.item.value);
	                return false;
	            }
	      });
		  
          $("a.save").button({
   		      icons: {
   		          primary: "ui-icon-disk"
   		        },
   		        text: false
   		      }).click(function( event ) {
   		    	$p = $(this).parent();
   		    	$pid = $(this).attr('product-id');
   		        $id = $(this).attr('href');
   		        if($('input', $p).val().length) {
   		         $("span.ui-icon", $(this)).addClass("ui-icon-refresh").removeClass("ui-icon-disk");
   		        	$.get($('#items_table').attr('data-ebay-action') + "&action=link_product&ebay_item_id=" + $id + "&product_id=" + $pid, function($data){
   		        		oTable.fnDraw(false);
  		    	  });
   		        }
   	           event.preventDefault();
   	      });

          $("a.import").click(function( event ) {
              $(this).html('Loading...');
              $id = $(this).attr('href');
              $.get($('#items_table').attr('data-ebay-action') + "&action=invidual_product_import&ebay_item_id=" + $id, function($data){
                  oTable.fnDraw(false);
              });
              event.preventDefault();
          });

          $("a.remove_link").click(function( event ) {
              $(this).html('Loading...');
              $id = $(this).attr('href');
              $.get($('#items_table').attr('data-ebay-action') + "&action=remove_link&product_ids=" + $id, function($data){
                  oTable.fnDraw(false);
              });
              event.preventDefault();
          });
		  

		  
		  $("a.link").button({
		      icons: {
		          primary: "ui-icon-link"
		        },
		        text: false
		      }).click(function( event ) {
			  
	        event.preventDefault();
	      });
		  
		  
		  $('td.detail table').each(function(){
			  if($(this).hasClass('qtydiff')) {
				 $('td.qty', $(this).parents('tr')).addClass('alert');
			  }
		  });
	  }
	});
	
	$('select[name="pag"]').html($('select[name="items_table_length"]').html()).change(function() {
        $('select[name="items_table_length"]').val($(this).val()).trigger('change');
	});
	
	
	$('#search-btn').click(function(){
		oTable.fnDraw();
		return false;
	});

	
});