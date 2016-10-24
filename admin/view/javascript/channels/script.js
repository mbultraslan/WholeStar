jQuery( document ).ready(function() {
	w = $('<div id="o"></div><div>Please wait...</div>');
	ck = ''; // use in fnRender checkboxes
	/******** SETTINGS DATA TABLE ********/
	oTable = $('#products_table').dataTable( {
	  "bProcessing": true,
	  "bServerSide": true,
	  "sAjaxSource": $('#products_table').attr('data-url'),
	  "sServerMethod": "POST",
	  "sPaginationType": "full_numbers",

	  "oLanguage": {
	    "sLengthMenu": "Display _MENU_ products per page",
	    "sZeroRecords": "Nothing found - sorry",
	    "sInfo": "Showing _START_ to _END_ of _TOTAL_ products",
	    "sInfoEmpty": "Showing 0 to 0 of 0 products",
	    "sInfoFiltered": "(filtered from _MAX_ total products)"
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
	      "fnRender" : function(o){return '<img src="' + o.aData[1] + '" style="padding: 1px; border: 1px solid #DDDDDD;">';}
	    },
	    {
	    	"sWidth": "53%",
	    	"sClass": "left",
	    	"fnRender" : function(o){return o.aData[2];}
	    },
	    {
	      "sWidth": "7%",
	      "fnRender" : function(o){return o.aData[3];}
	    },
	    {
	      "sWidth": "5%",
	      "sClass": "right",
	      "fnRender" : function(o){return o.aData[4];}
	    },
	    {
	      "sWidth": "5%",
	      "sClass": "right",
	      "fnRender" : function(o){
	    	  return o.aData[5];
	       }
	    },
	    {
	      "sWidth": "2%",
	      "sClass": "center",
	      "fnRender" : function(o){
              return (o.aData[6] > 0)? '<span class="label label-success">Enabled</span>' : '<span class="label label-warning">Disabled</span>';
          }
	    },
	    {
		      "sWidth": "5%",
		      "sClass": "center",
		      "fnRender" : function(o){return o.aData[7];}
		    },
	    {
		      "sWidth": "10%",
		      "sClass": "center",
		      "fnRender" : function(o){return o.aData[8];}
		    },
	  {
	      "sWidth": "5%",
	      "bSortable": false ,
	      "sClass": "center",
	      "fnRender" : function(o){return o.aData[9];}
	    }

	  ]

	  , "fnServerParams": function ( aoData ) {


		var idx = 0;
		$('#asb .asf').each(function(){
			$this = $(this);
			if(!$this.hasClass('template')) {
				var name = $('select.asfn', $this).val();
				var type = $('select.asfn option:selected', $this).attr('data-type');
				var filter = $('select.asfn option:selected', $this).attr('data-filter');
				var ologic = '';
				var value = '';
				var from = '';
				var to = '';

				if($('select.aso', $this).length) {
					ologic = $('select.aso', $this).val();
				}

				if(type == 'input' || type == 'select') {
					value = $('.' + filter, $this).val();
				} else if(type == 'range') {
					from = $('.' + filter + ' .from', $this).val();
					to = $('.' + filter + ' .to', $this).val();
				} else if(type == 'category') {
					value = $('.' + filter + ' .category_id', $this).val();
				}

				var hasValue = false
				if(value) {
					aoData.push({"name": "asp[" + idx + "][value]", "value": value});
					hasValue = true;
				} else if(from || to) {
					if(from) {
						hasValue = true;
						aoData.push({"name": "asp[" + idx + "][from]", "value": from});
					}
					if(to) {
						hasValue = true;
						aoData.push({"name": "asp[" + idx + "][to]", "value": to});
					}
				}

				if(hasValue) {
					aoData.push({"name": "asp[" + idx + "][name]", "value": name});
					if(idx > 0) {
						aoData.push({"name": "asp[" + idx + "][ologic]", "value": ologic});
					} else {
						aoData.push({"name": "asp[" + idx + "][ologic]", "value": ''});
					}

					idx++;
				}


				console.log('name: ' + name + ' ologic: ' + ologic + ' type: ' + type + ' value: ' + value + ' from: ' + from + ' to: ' + to);

			}
		});






		  var v = $('select[name="search_filter"]').val();
			if(v=='fa') {
				aoData.push({"name": "all_filter","value": $('input[name="all_filter"]').val()});
			} else if(v=='pn') {
				aoData.push({"name": "pn_filter","value": $('input[name="pn_filter"]').val()});
			} else if(v=='m') {
				aoData.push({"name": "m_filter","value": $('input[name="m_filter"]').val()});
			} else if(v=='p') {
				aoData.push({"name": "p_filter_from","value": $('input[name="p_filter_from"]').val()});
				aoData.push({"name": "p_filter_to","value": $('input[name="p_filter_to"]').val()});
			} else if(v=='q') {
				aoData.push({"name": "q_filter_from","value": $('input[name="q_filter_from"]').val()});
				aoData.push({"name": "q_filter_to","value": $('input[name="q_filter_to"]').val()});
			} else if(v=='s') {
				aoData.push({"name": "s_filter","value": $('select[name="s_filter"]').val()});
			} else if(v=='eid') {
				aoData.push({"name": "eid_filter","value": $('input[name="eid_filter"]').val()});
			}  else if(v=='eet') {
				aoData.push({"name": "eet_filter","value": $('input[name="eet_filter"]').val()});
			} else if(v=='elt') {
				aoData.push({"name": "elt_filter","value": $('select[name="elt_filter"]').val()});
			}  else if(v=='em') {
				aoData.push({"name": "em_filter","value": $('select[name="em_filter"]').val()});
			} else if(v=='lp') {
				aoData.push({"name": "lp_filter","value": $('select[name="lp_filter"]').val()});
			}
			$.each($("#h input[type=checkbox]"), function(){
		      if( this.checked ){
		        aoData.push( { "name": "w[]", "value": $(this).val() } );
		      }
			});
	  },
	  "fnDrawCallback": function ( ) {

		  $('.product-action a.end_list').click(function() {
              var $id = $(this).attr('data-id');
              endItemsFromEbayDialog($id);
			  return false;
		  });


		  $('.product-action a.relist').click(function() {
			  var $btn = $(this);
			  var $id = $btn.attr('data-id');
              openList2EbayDialogNew($id,1);
			  return false;
		  });

		  $('.product-action a.list_to_ebay').click(function() {
			  var $btn = $(this);
			  var $id = $btn.attr('data-id');
              openList2EbayDialogNew($id, 1)
			  return false;
		  });

		  $('.product-action a.revise_to').click(function() {
			  var $btn = $(this);
			  var $id = $btn.attr('data-id');
              openList2EbayDialogNew($id,1);
			  return false;
		  });

		  $('.product-action a.update_stock').click(function() {
			  var $btn = $(this);
			  var $id = $btn.attr('data-id');
              $btn.closest('.product-action').find('i').removeClass('fa-cog').addClass('fa-refresh eloading');
              $btn.closest('.dropdown-menu').hide();

			  $.get($('#products_table').attr('data-ebay-action'), {id : $id, action: 'update_inventory', 'product_ids' : $id}, function(data) {
				  oTable.fnDraw(false);
			  });
			  return false;
		  });

		  $('.product-action a.syncronize').click(function() {
			  var $btn = $(this);
			  var $id = $btn.attr('data-id');
              $btn.closest('.product-action').find('i').removeClass('fa-cog').addClass('fa-refresh eloading');
              $btn.closest('.dropdown-menu').hide();
			  $.get($('#products_table').attr('data-ebay-action'), {id : $id, action: 'syncronize', 'product_ids' : $id}, function(data) {
				  oTable.fnDraw(false);
			  });
			  return false;
		  });

		  $('.product-action a.remove_link').click(function() {
			  var $btn = $(this);
			  var $id = $btn.attr('data-id');
              $btn.closest('.product-action').find('i').removeClass('fa-cog').addClass('fa-refresh eloading');
              $btn.closest('.dropdown-menu').hide();
			  $.get($('#products_table').attr('data-ebay-action'), {id : $id, action: 'remove_link', 'product_ids' : $id}, function(data) {
				  oTable.fnDraw(false);
			  });
			  return false;
		  });

		  $('.product-action a.revise').click(function() {
			  var $btn = $(this);
			  var $id = $btn.attr('data-id');
			  $btn.closest('.product-action').find('i').removeClass('fa-cog').addClass('fa-refresh eloading');
			  $btn.closest('.dropdown-menu').hide();
			  $.get($('#products_table').attr('data-ebay-action'), {id : $id, action: 'revise_now', 'product_ids' : $id}, function(data) {
				  oTable.fnDraw(false);
			  });
			  return false;
		  });


	  }
	});

	$('select[name="pag"]').html($('select[name="products_table_length"]').html()).change(function(){
		$('select[name="products_table_length"]').val($(this).val()).trigger('change');
	});

	$('select[name="search_filter"]').change(function(){
		var v = $(this).val();
		console.log(v);
		if(v=='fa') {
			$('input[name="all_filter"]').removeClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='pn') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').removeClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='m') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').removeClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='p') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').removeClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='q') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').removeClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='s') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('select[name="s_filter"]').removeClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='eid') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eid_filter"]').removeClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='eet') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eet_filter"]').removeClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='elt') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').removeClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='em') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('select[name="em_filter"]').removeClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('select[name="lp_filter"]').addClass('hide');
		} else if(v=='lp') {
			$('input[name="all_filter"]').addClass('hide');
			$('input[name="pn_filter"]').addClass('hide');
			$('input[name="m_filter"]').addClass('hide');
			$('.price-range').addClass('hide');
			$('.qty').addClass('hide');
			$('select[name="s_filter"]').addClass('hide');
			$('select[name="lp_filter"]').removeClass('hide');
			$('select[name="elt_filter"]').addClass('hide');
			$('input[name="eet_filter"]').addClass('hide');
			$('input[name="eid_filter"]').addClass('hide');
			$('select[name="em_filter"]').addClass('hide');
		}


	});

	$('#search-btn').click(function(){
		oTable.fnDraw();
		return false;
	});

	$('#clear-btn').click(function() {
		$('select[name="search_filter"]').val('fa');
		$('input[name="all_filter"]').removeClass('hide').val('');
		$('input[name="pn_filter"]').addClass('hide').val('');
		$('input[name="m_filter"]').addClass('hide').val('');
		$('.price-range').addClass('hide');
		$('.qty').addClass('hide');
		$('select[name="s_filter"]').addClass('hide');
		$('select[name="elt_filter"]').addClass('hide');
		$('input[name="eid_filter"]').addClass('hide');
		$('select[name="em_filter"]').addClass('hide');
		$('select[name="lp_filter"]').addClass('hide');
		$('.lhorizontal input[type="text"]').each(function(){
			$(this).val('');
		});
		oTable.fnDraw();
		return false;
	});


	 $('#selectall').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.sc').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('.sc').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
	 });


    $('#confirm').click(function(){
    	var o = $('#action-s').val();
    	var args='';
		var n=0;
    	$(".sc").each(function(){
    		 if(this.checked) {
    			 args += "," + $(this).val();
    			 n++;
    		 }
	    });
    	args = args.substr(1);

    	if(o == 1 && n > 0) {
            openList2EbayDialogNew(args, n);
    	} else if(o == 2 && n > 0) {
    		openUpdateItem2EbayDialog(args);
    	} else if(o == 3 && n > 0) {
    		endItemsFromEbayDialog(args);
    	} else if(o == 4 && n > 0) {
    		openSyncronizeDialog(args);
    	}
    	return false;
    });

    $('.datetime').datetimepicker({
    	dateFormat: 'yy-mm-dd',
    	timeFormat: 'h:m'
    });

    $('.date').datepicker({dateFormat: 'yy-mm-dd'});




	/******** ACTIONS ********/
	$("#a input").click(function() {
		e = $('#a select[name="a"]');
		a = e.val(); // CURENT ACTION

		if(a!='0'){
			v='';
			n=0;
	    /******************************* for all products ****************************/
	    if($("#s>*").val() == "All"){
			v = "all=1";
			// oTable params
			v += '&' + $.param( oTable.oApi._fnAjaxParameters(oTable.dataTable().fnSettings()) );
			n = $('#products_table_info').text();
			n = n.substr( n.indexOf('of ')+3, 9 );
			n = n.substring( 0, n.indexOf(' ') );
	    } else if($("#s>*").val() == "Visible") {
			// oTable params
			v += '&' + $.param( oTable.oApi._fnAjaxParameters(oTable.dataTable().fnSettings()) );
			n = $('#products_table_length select').val();
	    } else {
	    /******************************* for selected products ****************************/
			$("#products_table tbody td:first-child *:checked").each(function(){
		    	v += "," + $(this).val(); //STRING LIKE ( 125,35,41 )
		    	n++;
		    });
	        v = "&i=" + v.substr(1);
	    }

	    if(a == 7){
			v += "&ice=1";
		}

		if(a == 3){
			v += "&delete=1";
		}

		if(a == 1){
			v += "&enabled=1";
		}

		if(a == 2){
			v += "&disabled=1";
		}


	    /******** SEND AJAX ********/
	    if( n ){

	    	if( confirm('Are you sure you want to '+$('#a option:selected').html()+' '+n+' product(s)?!') ){
	    		$('#process_wait').show();
	    		$('body').append(w);
	    		$.ajax({
	    			type: "POST",
	    			url: $(this).attr('data-url'),
	    			data: v,
	    			processData: false,
	    			success: function(r){
	    				$('#process_wait').hide();
	    				w.detach();
	    				oTable.fnDraw();
	    			}
	    		});

	    	}
	    }
	  }
		return false;
	});


	//==========Advance Search=====================
	$('#asb').on('click', '.nf-btn', function() {
		$('#asb .nf-btn:last').addClass('hide');
		$template = $('<div class="section row asf">' +  $('#asb .template').html() + '</div>');
		$('#asb .filters').append($template);
	});

	$('#asb').on('click', '.rf-btn', function() {
		$(this).closest('.asf').remove();
		$('#asb .nf-btn:last').removeClass('hide');
	});

	$('body').on('change', 'select.asfn', function() {
		var $data = $('option:selected', $(this)).attr('data-filter');
        $parent = $(this).closest('.asf')
		$('.aff', $parent).addClass('hide');
		$('.' + $data, $parent).removeClass('hide');
	});

	$('#asb .ase').click(function() {
		oTable.fnDraw();
		return false;
	});


	$('#asb .asc').click(function() {
		$('#asb .asf').each(function(){
			$this = $(this);
			if(!$this.hasClass('template')) {
				$this.remove();
			}
		});
		$template = $('<div class="section row asf">' +  $('#asb .template').html() + '</div>');

		$('.rf-btn', $template).remove();

        $('div.fno', $template).remove();
        $('div.fnn', $template).removeClass('col-md-9').addClass('col-md-12');
		$('#asb .filters').append($template);
		oTable.fnDraw();
		return false;
	});

    $('button.sw-mode-search').click(function() {
        if ($("#asb").is(":visible") ) {
            $("#asb").addClass('hide');
            $("#ssb").removeClass('hide');
        } else {
            $('#asb .asf').each(function(){
                $this = $(this);
                if(!$this.hasClass('template')) {
                    $this.remove();
                }
            });
            $template = $('<div class="section row asf">' +  $('#asb .template').html() + '</div>');

            $('div.fno', $template).remove();
            $('div.fnn', $template).removeClass('col-md-9').addClass('col-md-12');

            $('.rf-btn', $template).remove();

            $('#asb .filters').append($template);

            $("#ssb").addClass('hide');
            $("#asb").removeClass('hide');
        }
        return false;
    });

    $('body').on('click', 'button.browse-category', function(){
        $('#category_modal').modal('show');
        $('#category_modal button.choose-category').data('input-el', $('input', $(this).closest('.input-group')));
    });



    //=========================================================


    $('#list_ebay_modal_form').on('change', '.lps', function(){
        if($(this).val()) {
            $('#cboxContent .next').removeClass('disable');
        } else {
            $('#cboxContent .next').addClass('disable');
        }

        $('#list_items_modal button.back-step').addClass('disabled');
        $('#list_items_modal button.next-step').removeClass('disabled');
    });


    $('#list_items_modal').on('show.bs.modal', function (e) {
        $('#list_items_modal button.next-step').attr('step', 2).removeClass('disabled').show();
        $('#list_items_modal button.back-step').attr('step', 0).addClass('disabled').show();
        $('#step2').remove();
        $('#step3').remove();
        $('#step4').remove();
        $('#step5').remove();
        $.removeData( $('#list_items_modal button.next-step'), "ids" );
        $('#list_items_modal .pstep').html(1);
        $('#list_items_modal .panel-content div.ds').remove();
        $('#step1').show();
    });


    $('#list_items_modal button.back-step').click(function() {
        $this = $(this);
        $('.form-group').removeClass('has-error');
        if(!$this.hasClass('disable')) {
            $next = $('#list_items_modal button.next-step');
            $next.removeClass('disabled');
            var step = parseInt($this.attr('step'));
            console.log("Back step - " + step);
            $('#list_items_modal .pstep').html(step);
            $this.attr('step', step - 1);
            $next.attr('step', step + 1);

            $('.step').hide();
            $('#step'+step).show();
            if(step == 1) {
                $this.addClass('disabled');
                $('#step3').remove();
            } else if(step == 3) {
                if($('#step4').length) {
                    $('#step4').remove();
                }

                if(!($('select.specific_values').length)) {
                    $this.trigger('click');
                }

            } else if(step == 4) {
                if($('#step5').length) {
                    $('#step5').remove();
                }
            }
        }
        return false;
    });

    $('#list_items_modal button.next-step').click(function() {
        $this = $(this);
        $back = $('#list_items_modal button.back-step');
        var ids = $this.data('ids');
        if(!$this.hasClass('disabled')) {
            var step = parseInt($this.attr('step'));
            console.log("Next step - " + step);
            var data = {"step" : step};

            if(step == 2) {
                if($('#list_items_modal .lps').val()) {
                    data.id = $('#list_items_modal .lps').val();
                    data.languageId = $('#list_items_modal .ll').val();
                    data.productIds = ids;
                    $('#step'+step).remove();
                } else {
                    $('#list_items_modal .lps').closest('.form-group').addClass('has-error');
                    return false;
                }
            }

            $('#list_items_modal .pstep').html(step);
            $('.step').hide();
            if($('#step'+step).length) {
                $('#step'+step).show();
                $this.attr('step', step + 1);
                $back.attr('step', step - 1).removeClass('disabled');
            } else {
                var urlService = $('#list_items_modal').attr('action');
                if(step == 4) {
                    $('#list_items_modal .modal-body').append('<h2 class="loading-container"><i class="eloading"></i> Please wait while we retrieve the estimated listing costs...</h2>');
                } else {
                    $('#list_items_modal .modal-body').append('<h2 class="loading-container"><i class="eloading"></i> Please wait...</h2>');
                }

                $.get(urlService, data, function($html){
                    $('#list_items_modal .modal-body .loading-container').remove();
                    var st = $('<div id="step' + step + '" class="step ds"></div>').html($html);
                    $('#list_items_modal .modal-body').append(st);

                    $this.attr('step', step + 1);
                    $back.attr('step', step - 1).removeClass('disabled');

                    if(step != 2) {
                        $this.addClass('disabled');
                    }

                    if(step == 3) {
                        $this.removeClass('disabled');
                    }

                    if(step == 4) {
                        $this.hide();
                        $back.hide();								//xhr stream request
                        if (typeof XMLHttpRequest == "undefined") { // taken from wikipedia
                            XMLHttpRequest = function () {
                                try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
                                catch (e) {}
                                try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
                                catch (e) {}
                                try { return new ActiveXObject("Microsoft.XMLHTTP"); }
                                catch (e) {}
                                //Microsoft.XMLHTTP points to Msxml2.XMLHTTP and is redundant
                                throw new Error("This browser does not support XMLHttpRequest.");
                            };
                        };

                        var xhr = new XMLHttpRequest(), l=0;

                        $( "#progressbar .progress-bar" ).width('1%');

                        xhr.onreadystatechange = function() {

                            if(xhr.readyState === 3) {
                                h = xhr.responseText.substr(l);
                                console.log('responseText: ' + xhr.responseText.trim());
                                var a = xhr.responseText.trim().split('.');
                                console.log('a lenght: ' + a.length);
                                console.log('val: ' + a[a.length - 1]);
                                p = parseInt(a[a.length - 1]);
                                if(!isNaN(p)){
                                    $( "#progressbar .progress-bar" ).width(p + '%');
                                }
                                l = xhr.responseText.length;
                            } if(xhr.readyState === 4) {
                                $('#list_items_modal').modal('hide');
                                oTable.fnDraw(false);
                            }
                        };

                        xhr.open("GET", urlService + "&step=5", true);
                        xhr.send();
                    }



                });
            }
        }
        return false;
    });


    $('#end_list_items_modal').on('show.bs.modal', function (e) {
        $( "#end-progressbar .progress-bar" ).width( '0%');
        $('#end_list_items_modal button.end-item').show();
        $('#end_list_items_modal .select_option').removeClass('hide');
        $('#end_list_items_modal .progress_form').addClass('hide');
        $('#end_list_items_modal').removeClass('hide');
    });

    $('#end_list_items_modal button.end-item').click(function() {
        $ids = $('#end_list_items_modal button.end-item').data('ids');

		$endreason = $('input[name="endreason"]:checked').val();

        $('#end_list_items_modal .select_option').addClass('hide');
        $('#end_list_items_modal .progress_form').removeClass('hide');
        $('#end_list_items_modal button.end-item').hide();

        //xhr stream request
        if (typeof XMLHttpRequest == "undefined") { // taken from wikipedia
            XMLHttpRequest = function () {
                try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
                catch (e) {}
                try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
                catch (e) {}
                try { return new ActiveXObject("Microsoft.XMLHTTP"); }
                catch (e) {}
                //Microsoft.XMLHTTP points to Msxml2.XMLHTTP and is redundant
                throw new Error("This browser does not support XMLHttpRequest.");
            };
        };

        var xhr = new XMLHttpRequest(), l=0;

        $( "#end-progressbar .progress-bar" ).width('0%');

        xhr.onreadystatechange = function() {
            if(xhr.readyState === 3) {
                h = xhr.responseText.substr(l);
                console.log('responseText: ' + xhr.responseText.trim());
                var a = xhr.responseText.trim().split('.');
                console.log('a lenght: ' + a.length);
                console.log('val: ' + a[a.length - 1]);
                p = parseInt(a[a.length - 1]);
                if(!isNaN(p)){
                    $( "#end-progressbar .progress-bar" ).width(p + '%');
                }
                l = xhr.responseText.length;
            } if(xhr.readyState === 4) {
                $('#end_list_items_modal').modal('hide');
                oTable.fnDraw(false);
            }
        };

        xhr.open("GET", $('#products_table').attr('data-ebay-action') + "&is_progress=1&endreason="+ $endreason +"&action=end_list&product_ids=" + $ids, true);
        xhr.send();

        return false;
    });


    $("#category_tree").dynatree({
        persist: false,
        checkbox: false,
        selectMode: 1,
        onPostInit: function(isReloading, isError) {
            this.reactivate();
        },
        fx: { height: "toggle", duration: 200 },
        initAjax: {url: $("#category_tree").attr('data-service'),
            dataType: "json",
            timeout: 10000
        },
        onLazyRead: function(node){
            node.appendAjax(
                {url: $("#category_tree").attr('data-service'),
                    dataType: "json",
                    data: {"parent_id": node.data.key}
                });
        },
        onActivate: function(node) {
            $input = $('#category_modal button.choose-category').data('input-el');
            $input.val(toCategoryTree(node, null));
            $('#category_modal').modal('hide');
            $('input[type="hidden"]', $input.closest('.dtselect')).val(node.data.key);
        }
    });

});//end ready


function toCategoryTree(node, title) {
	var tree = htmlDecode(node.data.title) + ((title)? ' > ' + htmlDecode(title)  : '') ;
    if(node.parent.data.title) {
        return toCategoryTree(node.parent, tree);
    }
    return tree;
}

function htmlDecode(input){
    var e = document.createElement('div');
    e.innerHTML = input;
    return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}




function openList2EbayDialogNew(ids, count) {
    $('#list_items_modal').modal(true);
    $('#list_items_modal .pcnt').html(count);
    $('#list_items_modal button.next-step').data('ids', ids);

}

function endItemsFromEbayDialog($ids) {
    $('#end_list_items_modal').modal(true);
    $('#end_list_items_modal button.end-item').data('ids', $ids);
}






function openUpdateItem2EbayDialog($ids) {
    $('#update_inv_list_items_modal').modal(true);

	//xhr stream request
	if (typeof XMLHttpRequest == "undefined") { // taken from wikipedia
	    XMLHttpRequest = function () {
	      try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
	        catch (e) {}
	      try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
	        catch (e) {}
	      try { return new ActiveXObject("Microsoft.XMLHTTP"); }
	        catch (e) {}
	      //Microsoft.XMLHTTP points to Msxml2.XMLHTTP and is redundant
	      throw new Error("This browser does not support XMLHttpRequest.");
	    };
	  };

	  var xhr = new XMLHttpRequest(), l=0;
    $( "#update-inventory-progressbar .progress-bar" ).width('0%');

      xhr.onreadystatechange = function() {
	        if(xhr.readyState === 3) {
	        	h = xhr.responseText.substr(l);
	        	console.log('responseText: ' + xhr.responseText.trim());
	        	var a = xhr.responseText.trim().split('.');
	        	console.log('a lenght: ' + a.length);
	        	console.log('val: ' + a[a.length - 1]);
	        	p = parseInt(a[a.length - 1]);
	        	if(!isNaN(p)){
                    $( "#update-inventory-progressbar .progress-bar" ).width(p + '%');
		        }
	        	l = xhr.responseText.length;
	        } if(xhr.readyState === 4) {
              $('#update_inv_list_items_modal').modal('hide');
	        	oTable.fnDraw(false);
	        }
      };

     xhr.open("GET", $('#products_table').attr('data-ebay-action') + "&is_progress=1&action=update_inventory&product_ids=" + $ids, true);
     xhr.send();
}






function openSyncronizeDialog($ids) {

    $('#sync_list_items_modal').modal(true);

	//xhr stream request
	if (typeof XMLHttpRequest == "undefined") { // taken from wikipedia
	    XMLHttpRequest = function () {
	      try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
	        catch (e) {}
	      try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
	        catch (e) {}
	      try { return new ActiveXObject("Microsoft.XMLHTTP"); }
	        catch (e) {}
	      //Microsoft.XMLHTTP points to Msxml2.XMLHTTP and is redundant
	      throw new Error("This browser does not support XMLHttpRequest.");
	    };
	  };

	  var xhr = new XMLHttpRequest(), l=0;
      $( "#sync-progressbar .progress-bar" ).width('0%');

      xhr.onreadystatechange = function() {
	        if(xhr.readyState === 3) {
	        	h = xhr.responseText.substr(l);
	        	console.log('responseText: ' + xhr.responseText.trim());
	        	var a = xhr.responseText.trim().split('.');
	        	console.log('a lenght: ' + a.length);
	        	console.log('val: ' + a[a.length - 1]);
	        	p = parseInt(a[a.length - 1]);
	        	if(!isNaN(p)){
                    $( "#sync-progressbar .progress-bar" ).width(p + '%');
		        }
	        	l = xhr.responseText.length;
	        } if(xhr.readyState === 4) {
              $('#sync_list_items_modal').modal('hide');
	        	oTable.fnDraw(false);

	        }
      };

     xhr.open("GET", $('#products_table').attr('data-ebay-action') + "&is_progress=1&action=syncronize&product_ids=" + $ids, true);
     xhr.send();
}



