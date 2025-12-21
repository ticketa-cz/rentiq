jQuery(document).ready( function ($) {
	
    //// BASIC FUNCTIONS ////

	var apartment_id = getUrlParameter('apartment');

	if ( apartment_id ) {
		var select_apartment = $('#acf-field_reservation_apartment');
		preselect_rental_option( apartment_id, select_apartment, 'apartment' );
	}

	$( "h2:contains('Edit Owner')" ).hide();
	$( ".document_date input, .owner_date input" ).val('').change();
	$( ".document_date input" ).attr( "placeholder", rentiqLang.date ); 
	$( "#date-invoice_owner_from input, #date-extension_from input" ).attr( "placeholder", rentiqLang.datefrom ); 
	$( "#date-invoice_owner_till input, #date-extension_till input" ).attr( "placeholder", rentiqLang.datetill ); 
	
	function openloader() {
		$('.wrap').css('opacity', '0.25');
		$('#wpwrap').append('<div class="wrap_loader"></div>');
	}
	
	function closeloader() {
	    $('#wpwrap').find(".wrap_loader").remove();  
	    $('.wrap').css('opacity', '1');
	}

    function getUrlParameter(sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;
	
		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
	
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	}

    //// get prices and attached parking ////
	
	function get_price( rental_type, rental_id ) {
		$.ajax({
			  type: 'POST',
			  url: ajaxurl,
			  data: {
				    'action': 'get_rentiq_price',
				    'rental_type': rental_type,
                    'rental_id' : rental_id,
			  },
			  beforeSend: function() {
				    openloader();
			  },
			  complete: function(){
				    closeloader();
			  },
			  success: function( data ) {
				  	$("#acf-field_reservation_" + rental_type + "_price").val( data );
			  }
		});
	}
    $("#acf-field_reservation_apartment").change( function() {
		get_price('apartment', $(this).val() );
		if ( $("#acf-field_reservation_type-both").is(':checked') ) {
			get_attached_parking( $(this).val() );
		}
	});
    $("#acf-field_reservation_parking").change( function() {	
		get_price('parking', $(this).val() );
	});

	function get_attached_parking( apartment_id ) {
		$.ajax({
			  type: 'POST',
			  url: ajaxurl,
			  data: {
				    'action': 'get_attached_parking',
                    'apartment_id' : apartment_id,
			  },
			  beforeSend: function() {
				    openloader();
			  },
			  complete: function(){
				    closeloader();
			  },
			  success: function( data ) {
					var select_element = $("#acf-field_reservation_parking");
					preselect_rental_option(data, select_element, 'parking');
			  }
		});
	}

	//// get rental name ////

	function preselect_rental_option( rental_id, select_element, rental_type ) {

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'get_apartment_name',
				'rental_id' : rental_id,
			},
			beforeSend: function() {
				openloader();
			},
			complete: function(){
				closeloader();
				get_price(rental_type, rental_id);
				if ( rental_type == 'apartment' && $("#acf-field_reservation_type-both").is(':checked') ) {
					get_attached_parking( rental_id );
				}
			},
			success: function( data ) {
				var apartmentOption = new Option(data, rental_id, true, true);
				select_element.append(apartmentOption).trigger('change');
				select_element.trigger({
					type: 'select2:select',
					params: {
						data: rental_id
					}
				});
			}
		});
	}

	
	//// create new reservation document ////

	function create_new_reservation_document( document_type, document_date ) {

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: {
				'action': 'create_new_reservation_document',
				'document_type' : document_type,
				'document_date' : document_date,
				'date_from' : $( "#date-extension_from input:hidden").val(),
                'date_till' : $( "#date-extension_till input:hidden").val(),
				'reservation_id' : getUrlParameter('post'),
			},
			beforeSend: function() {
				    openloader();
			},
			complete: function(){
				    closeloader();
			},
			success: function( data ) {

				if (data.error == 'false') {

					var doc_div = '<div id="doc-' + data.document_id + '">';
				  	var doc_link = '<a class="doc_link dashicons-before dashicons-media-text" href="' + rentiqLang.document_url + '/' + data.document_pdf +'" target="_blank">' + data.document_title + '</a>';
					var doc_pay = '';
					  if ( document_type !== 'contract_tenant' || document_type !== 'contract_extension' ){ 
						  doc_pay = '<a class="doc_pay dashicons-before dashicons-money-alt" id="pay-' + data.document_id + '" /></a>'
					}
					var doc_send = '<a class="doc_send dashicons-before dashicons-buddicons-pm" id="send-' + data.document_id + '"></a>';
					var doc_delete = '<a class="doc_del dashicons-before dashicons-no" id="del-' + data.document_id + '"></a></div>';
				
					$("#" + document_type + "_post").append( doc_div + doc_link + doc_pay + doc_send + doc_delete );
					
				} else {
					alert(rentiqLang.notcreated);
				}
			}
		});

	}
	$("#bt-contract_tenant, #bt-invoice_deposit, #bt-invoice_rental, #bt-invoice_deporeturn, #bt-invoice_settlement, #bt-invoice_agent, #bt-invoice_ownerempty, #bt-contract_extension").prop("type", "button");
	$("#bt-contract_tenant, #bt-invoice_deposit, #bt-invoice_rental, #bt-invoice_deporeturn, #bt-invoice_settlement, #bt-invoice_agent, #bt-invoice_ownerempty, #bt-contract_extension").on("click", function() {	
		
		if ( check_if_fields_filled() == true ) {
			var button_id = $(this).attr('id');
			var document_type = button_id.replace('bt-','');
			var document_date = $( "#date-" + document_type + " input:hidden").val();

			create_new_reservation_document( document_type, document_date );
		} else {
			alert(rentiqLang.form_error);
		}
		
	});


	//// check if fields filled ////

	function check_if_fields_filled() {

		var reservation_type = $('#acf-field_reservation_type').val();
		var validated = true;

		if ( reservation_type == 'apartment' ) {
			$('#acf-field_reservation_tenant_name, #acf-field_reservation_apartment, #acf-field_reservation_apartment_price, #acf-field_reservation_date_from, #acf-field_reservation_water_value, #acf-field_reservation_electricity_value').each(function() {
				if ( $(this).val() == '' ) {
					$(this).closest('.acf-input').addClass('form_error');
					validated = false;
				}
			});
		} else if ( reservation_type == 'apartment' ) {
			$('#acf-field_reservation_tenant_name, #acf-field_reservation_parking, #acf-field_reservation_parking_price, #acf-field_reservation_date_from').each(function() {
				if ( $(this).val() == '' ) {
					$(this).closest('.acf-input').addClass('form_error');
					validated = false;
				}
			});
		}
		return validated;

	}


	//// create new owner document ////

	function create_new_owner_document( document_type, document_date ) {

		$.ajax({
			  type: 'POST',
			  url: ajaxurl,
			  dataType: 'json',
			  data: {
				    'action': 'create_new_owner_document',
                    'document_type' : document_type,
                    'document_date' : document_date,
                    'date_from' : $( "#date-invoice_owner_from input:hidden").val(),
                    'date_till' : $( "#date-invoice_owner_till input:hidden").val(),
					'owner_id' : getUrlParameter('tag_ID'),
			  },
			  beforeSend: function() {
				    openloader();
			  },
			  complete: function(){
				    closeloader();
			  },
			  success: function( data ) {

				  if (data.error == 'false') {
					
					if (data.add == 'true') {

						var doc_div = '<div id="doc-' + data.document_id + '">';
						var doc_link = '<a class="doc_link dashicons-before dashicons-media-text" href="' + rentiqLang.document_url + '/' + data.document_pdf +'" target="_blank">' + data.document_title + '</a>';
						var doc_pay = '<a class="doc_pay dashicons-before dashicons-money-alt" id="pay-' + data.document_id + '" /></a>'
						var doc_send = '<a class="doc_send dashicons-before dashicons-buddicons-pm" id="send-' + data.document_id + '"></a>';
						var doc_delete = '<a class="doc_del dashicons-before dashicons-no" id="del-' + data.document_id + '"></a></div>';
					
						$("#" + document_type + "_post").append( doc_div + doc_link + doc_pay + doc_send + doc_delete );

					}
					
				} else {
					alert(rentiqLang.notcreated);
				}
			  }
		});
	}
	$("#bt-invoice_owner, #bt-invoice_owner_payout").prop("type", "button");
	$("#bt-invoice_owner, #bt-invoice_owner_payout").on("click", function() {	
		
		var button_id = $(this).attr('id');
		var document_type = button_id.replace('bt-','');
		var document_date = $( "#date-" + document_type + " input:hidden").val();

		create_new_owner_document( document_type, document_date );
		
	});


	//// fill owner tenant name ////

	function fill_owner_tenant_name() {
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'fill_owner_tenant_name',
				'apartment_id' : $("#acf-field_reservation_apartment" ).val(),
			},
			beforeSend: function() {
				openloader();
			},
			complete: function(){
				closeloader();
			},
			success: function( data ) {
				$("#acf-field_reservation_tenant_name" ).val( data );
			}
		});
	}
	$('#acf-field_reservation_tenant_type-owner').on("click", function() {
		fill_owner_tenant_name();
	});

	//// send document to tenant ////

	function send_document_to_tenant( document_id ) {
		$.ajax({
			  type: 'POST',
			  url: ajaxurl,
			  data: {
				    'action': 'send_document_to_tenant',
                    'document_id' : document_id,
					'reservation_id' : getUrlParameter('post'),
			  },
			  beforeSend: function() {
				    openloader();
			  },
			  complete: function(){
				    closeloader();
			  },
			  success: function( data ) {
				  	if ( data == 'true' ) {
						$("#send-" + document_id ).addClass( 'doc_sent' );
					} else if ( data == 'noemail' ) {
						alert( rentiqLang.noemail );
					} else {
						alert( rentiqLang.notsent );
					}
			  }
		});
	}
	$('.reservation_documents, .owner_documents').on('click', '.doc_send', function() {

		var button_id = $(this).attr('id');
		var document_id = button_id.replace('send-','');
		if (confirm(rentiqLang.reallysend)) {
			send_document_to_tenant( document_id );
		}
	});


	//// delete document ////

	function delete_the_document( document_id ) {
		$.ajax({
			  type: 'POST',
			  url: ajaxurl,
			  data: {
				    'action': 'delete_the_document',
                    'document_id' : document_id,
					'reservation_id' : getUrlParameter('post'),
			  },
			  beforeSend: function() {
				    openloader();
			  },
			  complete: function(){
				    closeloader();
			  },
			  success: function( data ) {
					$(".reservation_documents").find("#doc-" + document_id ).remove();
					$(".owner_documents").find("#doc-" + document_id ).remove();
			  }
		});
	}
	$('.reservation_documents').on('click', '.doc_del', function() {
		var button_id = $(this).attr('id');
		var document_id = button_id.replace('del-','');
		if (confirm(rentiqLang.reallydelete)) {
			delete_the_document( document_id );
		}
	});
	$('.owner_documents').on('click', '.doc_del', function() {
		var button_id = $(this).attr('id');
		var document_id = button_id.replace('del-','');
		var object_id = getUrlParameter('tag_ID');
		if (confirm(rentiqLang.reallydelete)) {
			delete_the_document( document_id, 'owner', object_id );
		}
	});



	//// ==== save document payment ==== ////

	var payment_types = new Map();

	function open_payment_window( document_id ) {

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'JSON',
			data: {
				'action': 'load_payment',
				'document_id' : document_id,
			},
			beforeSend: function() {
				openloader();
			},
			complete: function(){
				closeloader();
			},
			success: function( data ) {

				$.each(data.payment_types, function(index, obj) {
					payment_types.set(obj.slug, obj.name);
				});

				var payment_form = '<div id="payment_form_wrap"><div id="payment_form-' + document_id + '" class="payment_form"><a id="close_payment_window">x</a>';
				payment_form += '<input type="button" name="add_payment" id="add_payment-' + document_id + '" class="add_payment" value="' + rentiqLang.add_payment + '"><table id="payment_table-' + document_id + '">';
				payment_form += '</table><input type="button" name="save_payment" id="save_payment-' + document_id + '" class="save_payment" value="' + rentiqLang.save_payment + '">';
				payment_form += '<span class="payment_balance">' + rentiqLang.balance + '<strong>' + data.balance + '</strong></span></div></div>';
				payment_form += '<input type="hidden" value="0" id="payment_row_count">';
				
				$('body').append( payment_form );

				if (data.status == 'paid') {
					$.each(data.payments, function(index, obj) {
						add_payment_line( document_id, obj.payment_date, obj.payment_type, obj.payment_amount, obj.payment_id, obj.payment_pdf );
					});
				} else {
					add_payment_line( document_id, '', '', data.invoice_total, '', '' );
				}
			}
		});

	}
	$('.reservation_documents, .owner_documents').on('click', '.doc_pay', function() {
		var button_id = $(this).attr('id');
		var document_id = button_id.replace('pay-','');
		open_payment_window( document_id );
	});


	//// add payment line ////

	function add_payment_line( document_id, payment_date, payment_type, payment_amount, payment_id, payment_pdf ) {

		var payment_row_count = parseInt( $(document).find("#payment_row_count").val() ) + 1;

		var payment_type_line = '<tr><td width="30%"><input type="text" name="'+payment_row_count+'[payment_date]" class="payment_date" value="' + payment_date + '"></td>';
		payment_type_line += '<input type="hidden" name="'+payment_row_count+'[payment_id]" class="payment_id" value="' + payment_id + '" id="payment_line-' + payment_id + '">';
		payment_type_line += '<td width="25%"><select name="'+payment_row_count+'[payment_type]" class="payment_type" value="' + payment_type + '">';
		
		for (let [key, value] of payment_types) {
			if  (payment_type == key ) { var selected = 'selected'; } else { var selected = '' }
			payment_type_line += '<option value="' + key + '" ' + selected + '>' + value + '</option>';			
		};

		payment_type_line += '</select></td><td width="25%"><input type="text" name="'+payment_row_count+'[payment_amount]" class="payment_amount" value="' + payment_amount + '">$</td>';

		if ( payment_pdf !== '' ) {
			payment_type_line += '<td width="20%"><a href="' + rentiqLang.document_url + '/' + payment_pdf + '" class="payment_pdf dashicons-before dashicons-pdf" target="_blank"></a><a class="remove_payment dashicons-before dashicons-trash"></a></tr>';
		} else {
			payment_type_line += '<td width="20%"><a class="remove_payment dashicons-before dashicons-trash"></a></tr>';
		}

		$(document).find("#payment_table-" + document_id).append( payment_type_line );
		$(document).find("#payment_row_count").val( payment_row_count );

	}

	$('body').on('click', '.add_payment', function() {
		var button_id = $(this).attr('id');
		var document_id = button_id.replace('add_payment-','');
		add_payment_line( document_id, '', '', '', '', '' );
	});

	//// add datepicker to lines ////

	$(document).on('focus', '.payment_date', function(){
		$(this).datepicker({
			dateFormat: 'dd-mm-yy', //maybe you want something like this
			showButtonPanel: true
		});
	});
	

	//// save payments ////

	function save_payment( document_id ) {

		var payment_data = $(document).find( "#payment_table-" + document_id + " :input" ).serializeJSON();

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'save_payment',
				'document_id' : document_id,
				'reservation_id' : getUrlParameter('post'),
				'owner_id' : getUrlParameter('tag_ID'),
				'payment_data' : payment_data,
			},
			beforeSend: function() {
				openloader();
			},
			complete: function(){
				closeloader();
			},
			success: function( data ) {

				alert(rentiqLang.payments_saved);

				if ( data == 'true' ) {
					$(document).find("#pay-" + document_id).addClass('doc_paid');
				}
				if ( data == 'false' ) {
					$(document).find("#pay-" + document_id).removeClass('doc_paid');
				}

				$(document).find("#payment_form_wrap").remove();
			}
		});
	}
	$('body').on('click', '.save_payment', function() {
		var button_id = $(this).attr('id');
		var document_id = button_id.replace('save_payment-','');
		save_payment( document_id );
	});

	$('body').on('click', '#close_payment_window', function() {
		$(document).find("#payment_form_wrap").remove();
	});


	//// delete payment ////

	function delete_payment( payment_id, document_id ) {

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'delete_payment',
				'payment_id' : payment_id,
				'document_id' : document_id,
			},
			beforeSend: function() {
				openloader();
			},
			complete: function(){
				closeloader();
			},
			success: function( data ) {

				if (data == 'ok') {
					$('body').find("#payment_line-" + payment_id).closest('tr').remove();
				}
			}
		});
	}

	// remove payment line //

	$('body').on('click', '.remove_payment', function() {

		var payment_id = $(this).closest('tr').find( 'input:hidden' ).val();
		var table_id = $(this).closest("table").attr('id');
		var document_id = table_id.replace('payment_table-','');

		if ( payment_id !== '' ) {
			delete_payment( payment_id, document_id );
		} else {
			$(this).closest('tr').remove();
		}
	});

	//// create yearly owner invoices == settings page ////
	/*
	function create_yearly_owners_invoices() {
		$.ajax({
			  type: 'POST',
			  url: ajaxurl,
			  data: {
				    'action': 'create_yearly_owners_invoices',
			  },
			  beforeSend: function() {
				    openloader();
			  },
			  complete: function(){
				    closeloader();
			  },
			  success: function( data ) {
					alert(data + rentiqLang.yearlycreated);
   			  }
		});
	}
	$( "#bt-yearly_owner_summary" ).prop("type", "button");
	$( "#bt-yearly_owner_summary" ).on( "click", function() {
		if (confirm(rentiqLang.reallycreate)) {
			create_yearly_owners_invoices();
		}
	});
	*/


	function add_rent_priceees() {
		$.ajax({
			  type: 'POST',
			  url: ajaxurl,
			  data: {
				    'action': 'add_rent_prices',
			  },
			  beforeSend: function() {
				    openloader();
			  },
			  complete: function(){
				    closeloader();
			  },
			  success: function( data ) {
					alert(data);
   			  }
		});
	}
	$( "#bt-add_rent_prices" ).prop("type", "button");
	$( "#bt-add_rent_prices" ).on( "click", function() {
			add_rent_priceees();
	});

});