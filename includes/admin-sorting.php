<?php

//// global setting ////

function disable_post_actions( $actions, $post ) {
	
	$user = wp_get_current_user();
	if ( !in_array( 'administrator', (array) $user->roles ) ) {
	
		if ( $post->post_type == 'reservation' || $post->post_type == 'expenses') {
			unset( $actions['edit'] );
			unset( $actions['inline'] );
			unset( $actions['view'] );
			unset( $actions['inline hide-if-no-js'] );
		}
	}
	
    return $actions;
}
add_filter( 'post_row_actions', 'disable_post_actions', 10, 2 );

function disable_category_actions( $actions, $tag ) {
    
	$user = wp_get_current_user();
	if ( !in_array( 'administrator', (array) $user->roles ) ) {
		
		unset( $actions['edit'] );
		unset( $actions['view'] );
		unset( $actions['inline'] );
		unset( $actions['inline hide-if-no-js'] );
	}

    return $actions;
}
add_filter( 'apartment_row_actions', 'disable_category_actions', 10, 2 );
add_filter( 'parking_row_actions', 'disable_category_actions', 10, 2 );
add_filter( 'owner_row_actions', 'disable_category_actions', 10, 2 );



//// apartment sorting ////

function create_apartment_columns($columns) {

	unset($columns['slug']);
	unset($columns['description']);
	unset($columns['posts']);

	$columns['number'] = __( 'Number', 'rentiq' );
	$columns['owner'] = __( 'Owner', 'rentiq' );
	$columns['rental_price'] = __( 'Rental price', 'rentiq' );
	$columns['parking'] = __( 'Parking attached', 'rentiq' );
	return $columns;
	
}
add_filter( 'manage_edit-apartment_columns', 'create_apartment_columns' );

function add_apartment_column_content( $content, $column_name, $term_id ) {

	$term = get_term($term_id, 'apartment');

    switch ($column_name) {
        case 'number':
			$content = get_field( 'apartment_number', $term );
        break;
		case 'owner':
			$owner = get_field( 'apartment_owner', $term );
			if ($owner) {
				$owner_url = admin_url('term.php?taxonomy=owner&tag_ID=' . $owner);
            	$content = '<a href="'. $owner_url . '">' . get_term( $owner, 'owner')->name . '</a>';
			}
        break;
		case 'rental_price':
            $content = get_field( 'apartment_rental_price', $term );
        break;
		case 'parking':
            $content = get_field( 'apartment_parking_attached', $term );
        break;
        default:
        break;
    }
    return $content;
}
add_filter( 'manage_apartment_custom_column', 'add_apartment_column_content', 10, 3 );




// make it sortable //
function register_sortable_column_apartment($columns) {
	$columns['number'] = 'apartment_number';
	return $columns;
}
add_filter('manage_edit-apartment_sortable_columns', 'register_sortable_column_apartment');




// create the sorting //
function sort_apartments_by_number($pieces, $taxonomies, $args) {

	global $pagenow, $wpdb; 

    // Require ordering
    $orderby = ( isset( $_GET['orderby'] ) ) ? trim( sanitize_text_field( $_GET['orderby'] ) ) : ''; 
    if ( empty( $orderby ) ) { return $pieces; }

    // set taxonomy
    $taxonomy = $taxonomies[0];

    // only if current taxonomy or edit page in admin           
    if ( !is_admin() || $pagenow !== 'edit-tags.php' || !in_array( $taxonomy, [ 'apartment' ] ) ) { return $pieces; }

    // and ordering matches
    if ( $orderby === 'apartment_number' ) {
        $pieces['join']  .= ' INNER JOIN ' . $wpdb->termmeta . ' AS tm ON t.term_id = tm.term_id ';
        $pieces['where'] .= ' AND tm.meta_key = "apartment_number"'; 
        $pieces['orderby']  = ' ORDER BY tm.meta_value + 0'; 
    }

    return $pieces;
}
add_filter('terms_clauses', 'sort_apartments_by_number', 10, 3);



//// parking sorting ////

function create_parking_columns($columns) {

	unset($columns['slug']);
	unset($columns['description']);
	unset($columns['posts']);

	$columns['number'] = __( 'Number', 'rentiq' );
	$columns['owner'] = __( 'Owner', 'rentiq' );
	$columns['rental_price'] = __( 'Rental price', 'rentiq' );
	return $columns;
	
}
add_filter( 'manage_edit-parking_columns', 'create_parking_columns' );

function add_parking_column_content( $content, $column_name, $term_id ) {

	$term = get_term($term_id, 'parking');

    switch ($column_name) {
        case 'number':
			$content = get_field( 'parking_number', $term );
        break;
		case 'owner':
			$owner = get_field( 'parking_owner', $term );
			if ($owner) {
            	$content = get_term( $owner, 'owner')->name;
			}
        break;
		case 'rental_price':
            $content = get_field( 'parking_rental_price', $term );
        break;
        default:
        break;
    }
    return $content;
}
add_filter( 'manage_parking_custom_column', 'add_parking_column_content', 10, 3 );



// make it sortable //

function register_sortable_column_parking($columns) {
	$columns['number'] = 'parking_number';
	return $columns;
}
add_filter('manage_edit-parking_sortable_columns', 'register_sortable_column_parking');



// create the sorting //

function sort_parkings_by_number( $pieces, $taxonomies, $args ) {

	global $pagenow, $wpdb; 

    $orderby = ( isset( $_GET['orderby'] ) ) ? trim( sanitize_text_field( $_GET['orderby'] ) ) : ''; 
    if ( empty( $orderby ) ) { return $pieces; }

    $taxonomy = $taxonomies[0];

    if ( !is_admin() || $pagenow !== 'edit-tags.php' || !in_array( $taxonomy, [ 'parking' ] ) ) { return $pieces; }

    if ( $orderby === 'parking_number' ) {
        $pieces['join']  .= ' INNER JOIN ' . $wpdb->termmeta . ' AS tm ON t.term_id = tm.term_id ';
        $pieces['where'] .= ' AND tm.meta_key = "parking_number"'; 
        $pieces['orderby']  = ' ORDER BY tm.meta_value + 0'; 
    }

    return $pieces;
}
add_filter('terms_clauses', 'sort_parkings_by_number', 10, 3);



//// owner sorting ////

function create_owner_columns($columns) {

	unset($columns['slug']);
	unset($columns['description']);
	unset($columns['posts']);

	$columns['phone'] = __( 'Phone', 'rentiq' );
	$columns['other_contact'] = __( 'Other contact', 'rentiq' );
	$columns['apartments'] = __( 'Apartments', 'rentiq' );
	$columns['parkings'] = __( 'Parkings', 'rentiq' );
	return $columns;
}
add_filter( 'manage_edit-owner_columns', 'create_owner_columns' );

function add_owner_column_content( $content, $column_name, $term_id ) {

	$term = get_term($term_id, 'owner');

    switch ($column_name) {
		case 'phone':
            $content = get_field( 'owner_phone', $term->taxonomy . '_' . $term_id );
        break;
		case 'other_contact':
            $content = get_field( 'other_contact', $term->taxonomy . '_' . $term_id );
        break;
        case 'apartments':
			$apartments = Rentiq_class::get_owner_estates( $term_id, 'apartment' );
			$content = Rentiq_class::list_estate_links( $apartments, 'apartment' );
        break;
		case 'parkings':
			$parkings = Rentiq_class::get_owner_estates( $term_id, 'parking' );
			$content = Rentiq_class::list_estate_links( $parkings, 'parking' );
        break;
        default:
            break;
    }
    return $content;
}
add_filter( 'manage_owner_custom_column', 'add_owner_column_content', 10, 3 );



//// expense sorting ////

function create_expenses_columns($columns) {

	unset($columns['author']);
	unset($columns['date']);

	$columns['price'] = __( 'Price', 'rentiq' );
	$columns['expense_date'] = __( 'Date', 'rentiq' );
	return $columns;
}
add_filter( 'manage_edit-expenses_columns', 'create_expenses_columns' );

function add_expenses_column_content( $column_name, $post_id ) {

    switch ($column_name) {
		case 'price':
            echo get_post_meta( $post_id, 'expense_price', true );
        break;
		case 'expense_date':
            echo Rentiq_class::rentiq_format_date( get_post_meta( $post_id, 'expense_date', true ) );
        break;
    }
}
add_filter( 'manage_expenses_posts_custom_column', 'add_expenses_column_content', 10, 2 );

function expense_posts_taxonomy_filter() {

	global $typenow;
	if( $typenow == 'expenses' ) {

		$taxonomy_names = array('expensecats');
		foreach ($taxonomy_names as $single_taxonomy) {

			$current_taxonomy = isset( $_GET[$single_taxonomy] ) ? $_GET[$single_taxonomy] : '';
			$taxonomy_object = get_taxonomy( $single_taxonomy );
			$taxonomy_name = strtolower( $taxonomy_object->labels->name );
			$taxonomy_terms = get_terms( $single_taxonomy );

			if(count($taxonomy_terms) > 0) {

				echo "<select name='$single_taxonomy' id='$single_taxonomy' class='postform'>";
				echo "<option value=''>All $taxonomy_name</option>";
				foreach ($taxonomy_terms as $single_term) {
					echo '<option value='. $single_term->slug, $current_taxonomy == $single_term->slug ? ' selected="selected"' : '','>' . $single_term->name .' (' . $single_term->count .')</option>'; 
				}
				echo "</select>";
			}
		}
	}
}
 
add_action( 'restrict_manage_posts', 'expense_posts_taxonomy_filter' );




//// receipts sorting ////

function create_receipts_columns($columns) {

	unset($columns['author']);
	unset($columns['date']);

	$columns['payment_date'] = __( 'Date', 'rentiq' );
	$columns['pdf_file'] = __( 'PDF', 'rentiq' );
	return $columns;
}
add_filter( 'manage_edit-receipts_columns', 'create_receipts_columns' );

function add_receipts_column_content( $column_name, $post_id ) {

    switch ( $column_name ) {
		case 'payment_date':
            echo Rentiq_class::rentiq_format_date( get_post_meta( $post_id, 'payment_date', true ) );
        break;
		case 'pdf_file':
			$wp_upload_dir = wp_upload_dir();
			$receipt_invoice_id = get_post_meta( $post_id, 'payment_invoice', true );
            echo '<a href="'. $wp_upload_dir['baseurl'] . '/' . get_post_meta( $receipt_invoice_id, 'file-receipt', true ) . '" target="_blank" class="pdf_link dashicons-before dashicons-pdf"></a>';
        break;
    }
}
add_filter( 'manage_receipts_posts_custom_column', 'add_receipts_column_content', 10, 2 );

function receipts_posts_taxonomy_filter() {

	global $typenow;
	if( $typenow == 'receipts' ) {

		$taxonomy_names = array('payment_types');
		foreach ($taxonomy_names as $single_taxonomy) {

			$current_taxonomy = isset( $_GET[$single_taxonomy] ) ? $_GET[$single_taxonomy] : '';
			$taxonomy_object = get_taxonomy( $single_taxonomy );
			$taxonomy_name = strtolower( $taxonomy_object->labels->name );
			$taxonomy_terms = get_terms( $single_taxonomy );

			if(count($taxonomy_terms) > 0) {

				echo "<select name='$single_taxonomy' id='$single_taxonomy' class='postform'>";
				echo "<option value=''>All $taxonomy_name</option>";
				foreach ($taxonomy_terms as $single_term) {
					echo '<option value='. $single_term->slug, $current_taxonomy == $single_term->slug ? ' selected="selected"' : '','>' . $single_term->name .' (' . $single_term->count .')</option>'; 
				}
				echo "</select>";
			}
		}
	}
}
 
add_action( 'restrict_manage_posts', 'receipts_posts_taxonomy_filter' );



//// reservation sorting ////

function create_reservations_columns($columns) {

	unset($columns['taxonomy-owner']);

	$columns['owners'] = __( 'Owners', 'rentiq' );
	return $columns;
}
add_filter( 'manage_edit-reservation_columns', 'create_reservations_columns' );

function add_reservations_column_content( $column_name, $post_id ) {

    switch ($column_name) {
		case 'owners':
			$reservation_owners = wp_get_post_terms( $post_id, 'owner', array( 'fields' => 'all' ) );
			if ( ! empty( $reservation_owners ) && ! is_wp_error( $reservation_owners ) ) {
				foreach ( $reservation_owners as $owner ) {
					echo '<a href="' . admin_url('edit.php?post_type=reservation&owner='.$owner->slug) . '">' . $owner->name . '</a>';
					echo '<a href="' . admin_url('term.php?taxonomy=owner&tag_ID='.$owner->term_id) . '" class="dashicons-before dashicons-edit"></a>';
				}
			}
        break;
    }
}
add_filter( 'manage_reservation_posts_custom_column', 'add_reservations_column_content', 10, 2 );


//// reservation filter ////

function rentiq_reservation_filter() {

    global $typenow;
    if ( $typenow == 'reservation' ) {

        $apartments = get_terms( array(
            'taxonomy' => 'apartment',
            'hide_empty' => false,
        ));
        //asort($apartments, SORT_NUMERIC); 
        $current_apartment = '';
        if( isset( $_GET['apartment'] ) ) {
            $current_apartment = $_GET['apartment'];
        }
        ?>
        
        <select name="apartment" id="apartment">
            <option value=""><?php echo __( 'All apartments', 'rentiq' ); ?></option>
            <?php foreach( $apartments as $apartment ) { ?>
                <option value="<?php echo esc_attr( $apartment->slug ); ?>" <?php if ( $current_apartment == $apartment->slug ) { echo 'selected' ; } ?>><?php echo esc_attr( $apartment->name ); ?></option>
            <?php } ?>
        </select>

    <?php }
}
add_action( 'restrict_manage_posts', 'rentiq_reservation_filter' );



//// owner payout invoice sorting ////

function create_ownerpayout_columns($columns) {

	$columns['pdf_file'] = __( 'PDF', 'rentiq' );
	return $columns;
}
add_filter( 'manage_edit-invoice_owner_payout_columns', 'create_ownerpayout_columns' );

function add_ownerpayout_column_content( $column_name, $post_id ) {

    switch ( $column_name ) {
		case 'pdf_file':
			$wp_upload_dir = wp_upload_dir();
            echo '<a href="'. $wp_upload_dir['baseurl'] . '/' . get_post_meta( $post_id, 'file-invoice_owner_payout', true ) . '" target="_blank" class="pdf_link dashicons-before dashicons-pdf"></a>';
        break;
    }
}
add_filter( 'manage_invoice_owner_payout_posts_custom_column', 'add_ownerpayout_column_content', 10, 2 );


//// owner invoice sorting ////

function create_ownerinvoice_columns($columns) {

	$columns['pdf_file'] = __( 'PDF', 'rentiq' );
	return $columns;
}
add_filter( 'manage_edit-invoice_owner_columns', 'create_ownerinvoice_columns' );

function add_ownerinvoice_column_content( $column_name, $post_id ) {

    switch ( $column_name ) {
		case 'pdf_file':
			$wp_upload_dir = wp_upload_dir();
            echo '<a href="'. $wp_upload_dir['baseurl'] . '/' . get_post_meta( $post_id, 'file-invoice_owner', true ) . '" target="_blank" class="pdf_link dashicons-before dashicons-pdf"></a>';
        break;
    }
}
add_filter( 'manage_invoice_owner_posts_custom_column', 'add_ownerinvoice_column_content', 10, 2 );



//// owner empty invoice sorting ////

function create_ownerempty_columns($columns) {

	$columns['pdf_file'] = __( 'PDF', 'rentiq' );
	return $columns;
}
add_filter( 'manage_edit-invoice_ownerempty_columns', 'create_ownerempty_columns' );

function add_ownerempty_column_content( $column_name, $post_id ) {

    switch ( $column_name ) {
		case 'pdf_file':
			$wp_upload_dir = wp_upload_dir();
            echo '<a href="'. $wp_upload_dir['baseurl'] . '/' . get_post_meta( $post_id, 'file-invoice_ownerempty', true ) . '" target="_blank" class="pdf_link dashicons-before dashicons-pdf"></a>';
        break;
    }
}
add_filter( 'manage_invoice_ownerempty_posts_custom_column', 'add_ownerempty_column_content', 10, 2 );



//// settlement invoice sorting ////

function create_settlement_columns($columns) {

	$columns['pdf_file'] = __( 'PDF', 'rentiq' );
	$columns['reservation'] = __( 'Reservation', 'rentiq' );
	return $columns;
}
add_filter( 'manage_edit-invoice_settlement_columns', 'create_settlement_columns' );

function add_settlement_column_content( $column_name, $post_id ) {

    switch ( $column_name ) {
		case 'pdf_file':
			$wp_upload_dir = wp_upload_dir();
            echo '<a href="'. $wp_upload_dir['baseurl'] . '/' . get_post_meta( $post_id, 'file-invoice_settlement', true ) . '" target="_blank" class="pdf_link dashicons-before dashicons-pdf"></a>';
        break;
		case 'reservation':
            $reservation_id = get_post_meta($post_id, 'reservation_id', true);
			echo '<a href="' . admin_url('post.php?action=edit&post='.$reservation_id) . '" class="dashicons-before dashicons-edit"></a>';
		break;
    }
}
add_filter( 'manage_invoice_settlement_posts_custom_column', 'add_settlement_column_content', 10, 2 );