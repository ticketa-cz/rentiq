<?php

//// get price ////

function get_rentiq_price() {
	
	$rental_type = $_POST['rental_type'];
	$rental_id = $_POST['rental_id'];
	
    $price = get_field( $rental_type.'_rental_price', $rental_type . '_' . $rental_id );
	
	echo $price;
	wp_die();
}
add_action('wp_ajax_get_rentiq_price', 'get_rentiq_price');



//// get attached parking ////

function get_attached_parking() {
	
	$apartment_id = $_POST['apartment_id'];
	
	$term = get_term($apartment_id, 'apartment');
    $parking_id = get_field( 'apartment_parking_attached', $term );

	echo $parking_id;
	wp_die();
}
add_action('wp_ajax_get_attached_parking', 'get_attached_parking');



//// get rental name ////

function get_apartment_name() {
	
	$rental_id = $_POST['rental_id'];
	$term = get_term($rental_id);
		
	echo $term->name;
	wp_die();
}
add_action('wp_ajax_get_apartment_name', 'get_apartment_name');



//// get apartment owner name ////

function fill_owner_tenant_name() {
	
	$apartment_id = $_POST['apartment_id'];
	$owner_id = get_term_meta( $apartment_id, 'apartment_owner', true );
	$owner = get_term( $owner_id );
		
	echo $owner->name;
	wp_die();
}
add_action('wp_ajax_fill_owner_tenant_name', 'fill_owner_tenant_name');



//// create new reservation document ////

function create_new_reservation_document() {
	
	$document_type = $_POST['document_type'];
	$document_date = $_POST['document_date'];
	$reservation_id = $_POST['reservation_id'];
	$date_from = date('Ymd', strtotime( $_POST['date_from'] ));
	$date_till = date('Ymd', strtotime( $_POST['date_till'] ));

	if (empty($document_date)) {
		$document_date = date('Ymd');
	}
	$post_date = date('Y-m-d', strtotime($document_date));

	$tenant_type = get_post_meta( $reservation_id, 'reservation_tenant_type', true );
	$reservation_type = get_post_meta( $reservation_id, 'reservation_type', true );
	$owners = Rentiq_class::get_reservation_owners( $reservation_id );


	// setup reservation type //

	switch( $reservation_type ) {

		case 'apartment':

			$place_id = get_post_meta( $reservation_id, 'reservation_apartment', true );
			$apartment_number = Rentiq_class::get_rental_number_by_id( $place_id, 'apartment' );

			$taxonomies = array(
				'apartment' => array( intval( $place_id ) ),
				'owner' => $owners,
			);

		break;
		case 'parking':

			$place_id = get_post_meta( $reservation_id, 'reservation_parking', true );

			$taxonomies = array(
				'parking' => array( intval( $place_id ) ),
				'owner' => $owners,
			);

		break;

	}

	switch( $tenant_type ) {

		case 'tenant':

			$tenant_name = get_post_meta( $reservation_id, 'reservation_tenant_name', true );
			
		break;
		case 'owner':

			$owner_id = get_term_meta( $place_id, $reservation_type.'_owner', true );
			$tenant_name = get_term( $owner_id )->name;
			
		break;
	}


	// setup post data //

	switch( $document_type ) {

		case 'contract_tenant':

			$document_title = $tenant_name. ' - contract - ' . $post_date;
			$document_meta = array(
				'reservation_id' => $reservation_id,
				'tenant_name' => $tenant_name,
				'tenant_country' => get_post_meta( $reservation_id, 'reservation_tenant_country', true ),
			);

		break;
		case 'contract_extension':

			$extension_posts = get_posts( array(
                'post_type'   => array( 'contract_extension' ),
                'numberposts' => -1,
                'post_status' => 'any',
                'fields'      => 'ids',
                'meta_query'  => array(
                    array(
                        'key'    => 'reservation_id',
                        'value'  => $reservation_id,
                    )

                ),
            ));
            $extension_count = Rentiq_class::ordinal_number( count($extension_posts) + 1 );
			$document_title = $tenant_name. ' - ' . $extension_count . ' contract extension - ' . $post_date;
			$document_meta = array(
				'reservation_id' => $reservation_id,
				'tenant_name' => $tenant_name,
			);

		break;
		case 'invoice_deposit':

			$document_title = $tenant_name. ' - deposit - ' . $post_date;
			$document_meta = array(
				'reservation_id' => $reservation_id,
			);
			
		break;
		case 'invoice_rental':

			$document_title = $tenant_name. ' - rental invoice - ' . $post_date;
			//$receipt_title = 'Unit '. $apartment_number . ' - rental income - ' . $post_date;
			$document_meta = array(
				'reservation_id' => $reservation_id,
			);

		break;
		case 'invoice_deporeturn':

			$document_title = $tenant_name. ' - termination - ' . $post_date;
			$document_meta = array(
				'reservation_id' => $reservation_id,
			);
			
		break;
		case 'invoice_settlement':

			$document_title = $tenant_name. ' - settlement - ' . $post_date;
			$document_meta = array(
				'reservation_id' => $reservation_id,
			);
			
		break;
		case 'invoice_agent':

			$document_title = $tenant_name. ' - agent commission - ' . $post_date;
			$document_meta = array(
				'reservation_id' => $reservation_id,
			);
			
		break;
		case 'invoice_ownerempty':

			$apartment_name = get_term( $place_id )->name;
			$reservation_date = get_post_meta( $reservation_id, 'reservation_date_from', true );
			$post_date = date('Y-m-d', strtotime($reservation_date));

			$document_title = $apartment_name. ' - empty period ending ' . $post_date;
			$document_meta = array(
				'reservation_id' => $reservation_id,
			);
			
		break;

	}
	
	// create post //

	$document_args = array(
		'post_title'   => $document_title,
		'post_status'  => 'publish',
		'post_author'  => get_current_user_id(),
		'tax_input'    => $taxonomies,
		'meta_input'   => $document_meta,
		'post_type'	   => $document_type,
		'post_date'	   => $post_date,
	);

	// create post //

	$document_id = wp_insert_post( $document_args );

	if ( !is_wp_error( $document_id ) ) {

		if ( $document_type == 'contract_extension' ) {
			update_post_meta( $document_id, 'extension_date_range', $date_from . '===' . $date_till );
		}

		// attach document post to reservation //

		if ( $document_type == 'invoice_rental' || $document_type == 'contract_extension' ) {

			$rental_invoices = get_post_meta( $reservation_id, 'reservation_file-'.$document_type, true );
			$rental_invoices_array = explode(',', $rental_invoices);
			$rental_invoices_merged_array = array_unique( array_merge( (array)$rental_invoices_array, (array)$document_id ) );
			$rental_invoices_merged = implode(',', array_filter($rental_invoices_merged_array));
			update_post_meta( $reservation_id, 'reservation_file-'.$document_type, $rental_invoices_merged );

		} else {
			update_post_meta( $reservation_id, 'reservation_file-'.$document_type, $document_id );
		}

		// create PDF //
		
		$document_pdf = Rentiq_class::create_new_document_pdf( $document_id, $document_type, $reservation_id );
		update_post_meta( $document_id, 'file-'.$document_type, $document_pdf );

		$data_return = array(
			'error' 			=> 'false',
			'document_id'		=> $document_id,
			'document_pdf' 		=> $document_pdf,
			'document_title'  	=> $document_title,
		);

	} else {

		$data_return = array(
			'error' => 'true',
		);
	}
		
	echo json_encode( $data_return );
	wp_die();
	
}
add_action('wp_ajax_create_new_reservation_document', 'create_new_reservation_document');



//// create new owner document ////

function create_new_owner_document( $owner_id = NULL, $document_type = NULL, $document_date = NULL ) {
	
	$document_type = $_POST['document_type'];
	$document_date = $_POST['document_date'];
	$date_from = date('Y-m-d', strtotime( $_POST['date_from'] ));
	$date_till = date('Y-m-d', strtotime( $_POST['date_till'] ));
	$owner_id = $_POST['owner_id'];
	$owner_name = get_term( $owner_id )->name;

	if (empty($document_date)) {
		$document_date = date('Y-m-d');
	}
	if (empty($date_from)) {
		$date_from = date('Y-01-01', strtotime('+1 year'));
	}
	if (empty($date_till)) {
		$date_till = date('Y-12-31', strtotime('+1 year'));
	}
	$post_date = date('Y-m-d', strtotime($document_date));

	// setup post data //

	switch( $document_type ) {

		case 'invoice_owner':
	
			$document_title = $owner_name. ' - ' . date( 'm/y', strtotime( "+1 month", strtotime( $_POST['date_from'] ) ) ). ' > ' . date( 'm/y', strtotime( $_POST['date_till'] ) ) . ' fees invoice - ' . $post_date;
			
		break;
		case 'invoice_owner_payout':

			$document_title = $owner_name. ' - owner payout invoice - ' . $post_date;
			
		break;

	}

	$taxonomies = array(
		'owner' => array( intval( $owner_id ) ),
	);
	
	// post data //

	$document_args = array(
		'post_title'   => $document_title,
		'post_status'  => 'publish',
		'post_author'  => get_current_user_id(),
		'tax_input'    => $taxonomies,
		'post_type'	   => $document_type,
		'post_date'    => $post_date,
	);

	// create / update post //

	$document_id = wp_insert_post( $document_args );

	if ( !is_wp_error( $document_id ) ) {

		if ( $document_type == 'invoice_owner' ) {
			update_post_meta( $document_id, 'invoice_date_range', $date_from . '===' . $date_till );
		}

		// attach document post to owner //

		$owner_invoices = get_term_meta( $owner_id, 'owner_file-'.$document_type, true );
		$owner_invoices_array = explode(',', $owner_invoices);
		$owner_invoices_merged_array = array_unique( array_merge( (array)$owner_invoices_array, (array)$document_id ) );
		$owner_invoices_merged = implode(',', array_filter($owner_invoices_merged_array));
		update_term_meta( $owner_id, 'owner_file-'.$document_type, $owner_invoices_merged );

		// create PDF //
		
		$document_pdf = Rentiq_class::create_new_owner_pdf( $document_id, $document_type, $owner_id );
		update_post_meta( $document_id, 'file-'.$document_type, $document_pdf );

		$data_return = array(
			'error' 			=> 'false',
			'document_id'		=> $document_id,
			'document_pdf' 		=> $document_pdf,
			'document_title'  	=> $document_title,
			'add'				=> 'true',
		);

	} else {
		$data_return = array(
			'error' => 'true',
		);
	}
		
	echo json_encode( $data_return );
	wp_die();
	
}
add_action('wp_ajax_create_new_owner_document', 'create_new_owner_document');



//// send document to tenant ////

function send_document_to_tenant() {
	
	$document_id = $_POST['document_id'];
    $document_type = get_post_type( $document_id );
	$attachment = get_post_meta( $document_id, 'file-'.$document_type, true );

	$reservation_id = $_POST['reservation_id'];
	$apartment_id = get_post_meta( $reservation_id, 'reservation_apartment', true );
	$apartment_number = Rentiq_class::get_rental_number_by_id( $apartment_id, 'apartment' );

	$tenant_type = get_post_meta( $reservation_id, 'reservation_tenant_type', true );
	switch( $tenant_type ) {
		case 'tenant':
			$tenant_email = get_post_meta( $reservation_id, 'reservation_tenant_email', true );
		break;
		case 'owner':
			$owner_id = get_term_meta( $apartment_id, 'apartment_owner', true );
			$tenant_email = get_field( 'owner_email', $owner_id );
		break;
	}

	$subject = get_the_title( $document_id );

	$message = __( 'Hello, we are sending you documents regarding the rent of apartment #', 'rentiq' );
	$message .= $apartment_number;
	$message .= __( '<br/><br/> Please check the attachment. <br/> Have a nice day! <br/>', 'rentiq' );

	if ( $tenant_email ) {
		$send_email = Rentiq_class::send_email( $tenant_email, $subject, $message, $attachment );
	} else {
		$success = 'noemail';
	}

	if ( $send_email == true ) {
		update_post_meta( $document_id, 'doc_sent', 'yes' );
		$success = 'true';
	} else {
		delete_post_meta( $document_id, 'doc_sent' );
		$success = 'false';
	}
	
	echo $success;
	wp_die();
}
add_action('wp_ajax_send_document_to_tenant', 'send_document_to_tenant');




//// delete the document ////

function delete_the_document() {
	
	$document_id = $_POST['document_id'];
	$reservation_id = $_POST['object_id'];
    $document_type = get_post_type( $document_id );	

	// delete file //
	$wp_upload_dir = wp_upload_dir();
	$document_pdf = get_post_meta( $document_id, 'file-'.$document_type, true );
	unlink( $wp_upload_dir['basedir'] . '/' . $document_pdf );

	// delete post //
	$deleted_post = wp_delete_post( $document_id, true);

	if ( $document_type == 'invoice_rental' ) {

		$rental_invoices = get_post_meta( $reservation_id, 'reservation_file-invoice_rental', true );
		$rental_invoices_array = explode(',', $rental_invoices);
		$rental_invoices_merged = implode(',', array_diff( $rental_invoices_array, (array)$document_id ));
		$rental_invoices_updated = update_post_meta( $reservation_id, 'reservation_file-invoice_rental', $rental_invoices_merged );
	
	} else {

		delete_post_meta( $reservation_id, 'reservation_file-'.$document_type, $document_id );
	}
	
	if ( !is_wp_error( $deleted_post ) ) {
		$success = 'true';
	} else {
		$success = 'false';
	}
	
	echo $success;
	wp_die();
}
add_action('wp_ajax_delete_the_document', 'delete_the_document');




//// load document payment ////

function load_payment() {
	
	$document_id = $_POST['document_id'];
	$payment_data = array();

	$payment_ids = get_post_meta( $document_id, 'invoice_payments', true );

	$payment_data['balance'] = Rentiq_class::is_invoice_paid( $document_id, 'text' );

	if ( $payment_ids ) {

		$payment_data['status'] = 'paid';

		foreach ( $payment_ids as $payment_id ) {

			$payment_data['payments'][] = array(
				'payment_id'		=>		$payment_id,
				'payment_date'		=>		date("d-m-Y", strtotime( get_post_meta( $payment_id, 'payment_date', true ) ) ),
				'payment_type'		=>		get_the_terms( $payment_id, 'payment_types' )[0]->slug,
				'payment_amount'	=>		get_post_meta( $payment_id, 'payment_amount', true ),
				'payment_pdf'		=>		get_post_meta( $document_id, 'file-receipt', true ),
			);
		}

	} else {

		$payment_data['status'] = 'unpaid';
		$payment_data['invoice_total'] = get_post_meta( $document_id, 'invoice_total', true );
	}

	$payment_data['payment_types'] = get_terms( array(
		'taxonomy' => 'payment_types',
		'hide_empty' => false,
	));
	
	echo json_encode( $payment_data );
	wp_die();
}
add_action('wp_ajax_load_payment', 'load_payment');



//// save document payment ////

function save_payment() {
	
	$document_id = $_POST['document_id'];
	$reservation_id = $_POST['reservation_id'];
	$owner_id = $_POST['owner_id'];
	$payment_data = $_POST['payment_data'];
	$payments_ids = array();

	foreach ( $payment_data as $payment ) {

		if ( !empty( $payment['payment_date'] ) ) {

			$payment_date = date("Ymd", strtotime( $payment['payment_date'] ));

			if ( empty( $payment['payment_id'] ) ) {

				$bill_args = array(
					'post_title'   => __( '$', 'rentiq' ) . $payment['payment_amount'] . ' - ' . get_the_title($document_id),
					'post_status'  => 'publish',
					'post_author'  => get_current_user_id(),
					'post_type'	   => 'receipts',
					'meta_input'   => array(
						'payment_date' => $payment_date,
						'payment_invoice' => $document_id,
						'payment_amount' => $payment['payment_amount'],
					),
					'tax_input'	   => array(
						'payment_types' => array( $payment['payment_type'] ),
					),
				);
				$receipt_id = wp_insert_post( $bill_args );
				if ( !is_wp_error( $receipt_id ) ) {
					Rentiq_class::change_invoice_payments( $document_id, $receipt_id, 'add' );
				}

			} else {

				$receipt_id = $payment['payment_id'];
				$receipt_date = get_post_meta( $receipt_id, 'payment_date', true );
				$receipt_type = get_the_terms( $receipt_id, 'payment_types' )[0]->slug;
				$receipt_amount = get_post_meta( $receipt_id, 'payment_amount', true );

				if ( $receipt_date !== $payment_date || $receipt_type !== $payment['payment_type'] || $receipt_amount !== $payment['payment_amount'] ) {

					wp_update_post( array(
						'ID' => $receipt_id,
						'post_title'   => __( '$', 'rentiq' ) . $payment['payment_amount'] . ' - ' . get_the_title( $document_id ),
						'meta_input'   => array(
							'payment_date' => $payment_date,
							'payment_invoice' => $document_id,
							'payment_amount' => $payment['payment_amount'],
						),
						'tax_input'	   => array(
							'payment_types' => array( $payment['payment_type'] ),
						),
					));
				}
			}
		}
	}

	// create new pdf //

	Rentiq_class::delete_pdf_document( $document_id, 'receipt' ); 
	if ( isset( $reservation_id )) {
		$receipt_pdf = Rentiq_class::create_new_document_pdf( $document_id, 'receipt', $reservation_id );
	} else {
		$receipt_pdf = Rentiq_class::create_new_owner_pdf( $document_id, 'receipt', $owner_id );
	}
	update_post_meta( $document_id, 'file-receipt', $receipt_pdf );

	// get balance //

	$invoice_paid = Rentiq_class::is_invoice_paid( $document_id );
	
	if ( $invoice_paid === true ) {
		$paid = 'true';
	} else {
		$paid = 'false';
	}
	
	echo $paid;
	wp_die();

}
add_action('wp_ajax_save_payment', 'save_payment');



//// delete payment ////

function delete_payment() {
	
	$payment_id = $_POST['payment_id'];
	$document_id = $_POST['document_id'];

	$delete_post = wp_delete_post( $payment_id );
	
	if ( !is_wp_error( $delete_post ) ) {

		Rentiq_class::change_invoice_payments( $document_id, $payment_id, 'delete' );
		echo 'ok';

	} else {
		echo 'error';
	}
	wp_die();

}
add_action('wp_ajax_delete_payment', 'delete_payment');



//// add rent prices to all invoices ////

function add_rent_prices() {
	
	$count = 0;
	
	// get all invoices //
	$tenant_invoices = get_posts( array(
		'post_type'   => array( 'invoice_rental', 'invoice_deposit', 'invoice_deporeturn', 'invoice_ownerempty', 'invoice_settlement' ),
		'numberposts' => -1,
		'post_status' => 'any',
		'orderby' => 'date',
		'order' => 'ASC',
		'fields' => 'ids',
	));

	// check if the apartment and parking price is saved //

	foreach( $tenant_invoices as $tinvoice ) {

		$invoice_reservation_id = get_post_meta( $tinvoice, 'reservation_id', true );
		$reservation_tenant = get_post_meta( $invoice_reservation_id, 'reservation_tenant_type', true );
		
		$rent_payed = get_post_meta( $tinvoice, 'invoice_apartment_rent', true );

		// if not get the price from reservation and save it //

		if ( $rent_payed == NULL || !isset($rent_payed) ) {
			//error_log($rent_payed);

			$apartment_price = get_post_meta( $invoice_reservation_id, 'reservation_apartment_price', true );
			//error_log( 'res #' . $invoice_reservation_id . ' - neni apt price v invoice #' . $tinvoice . ' >> cena ' . $apartment_price );
			$count++;
			update_post_meta( $tinvoice, 'invoice_apartment_rent', $apartment_price );

		}

		// parking // 

		$reservation_type = get_post_meta( $invoice_reservation_id, 'reservation_type', true );
		if ( $reservation_type == 'both' ) {

			$parking_rent_payed = get_post_meta( $tinvoice, 'invoice_parking_rent', true );

			if ( $parking_rent_payed == NULL || !isset($parking_rent_payed) ) {
				//error_log($parking_rent_payed);

				$parking_price = get_post_meta( $invoice_reservation_id, 'reservation_parking_price', true );
				//error_log( 'res #' . $invoice_reservation_id . ' - neni park price v invoice #' . $tinvoice . ' >> cena ' . $parking_price );
				$count++;
				update_post_meta( $tinvoice, 'invoice_parking_rent', $parking_price );
	
			}

		}

	}
	
	echo $count;
	wp_die();
}
add_action('wp_ajax_add_rent_prices', 'add_rent_prices');