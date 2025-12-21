<?php

//// EXPENSES TABLE ////

// join all expenses //

function rentiq_combine_expenses( $where ) {

    global $pagenow, $wpdb;

    if( 'edit.php' == $pagenow && ( get_query_var('post_type') && 'expenses' == get_query_var('post_type') ) ) {

        $stringToReplace = $wpdb->prefix . "posts.post_type = 'expenses'";

        // invoice type filter //
        if ( isset( $_GET['invoice_type'] ) && $_GET['invoice_type'] ) {

            $replaceWith = $wpdb->prefix . "posts.post_type IN ('" . $_GET['invoice_type'] . "')";            
        
        } else {

            $replaceWith = $wpdb->prefix . "posts.post_type IN ('invoice_agent', 'invoice_owner_payout', 'expenses' )";
        }

        $where = str_replace($stringToReplace, $replaceWith, $where );
    }
    return $where;
}
add_filter( 'posts_where', 'rentiq_combine_expenses' );


function rentiq_expenses_distinct( $where ){

    global $pagenow, $wpdb;
    if ( is_admin() && $pagenow =='edit.php' && $_GET['post_type'] == 'expenses') {
        return "DISTINCT";
    }
    return $where;
}
add_filter( 'posts_distinct', 'rentiq_expenses_distinct' );


// custom columns //

function rentiq_add_expense_type_header( $columns ) {

    unset($columns['date']);
    unset($columns['author']);

    $columns[ 'cpt' ] = __( 'Expense type', 'rentiq' );
    $columns[ 'sum' ] = __( 'Total', 'rentiq' );
    $columns[ 'rental' ] = __( 'Rental info', 'rentiq' );
    $columns[ 'payments' ] = __( 'Payments', 'rentiq' );
    return $columns;

}
add_filter( 'manage_edit-expenses_columns', 'rentiq_add_expense_type_header' );


function rentiq_add_expense_type_column( $column_name, $post_id ) {

    switch ( $column_name ) {
        case 'cpt':
            $post_type = get_post_type( $post_id );
            $post_type_obj = get_post_type_object( $post_type );
            echo $post_type_obj->labels->singular_name;
        break;
        case 'sum':
            $total = get_post_meta( $post_id, 'invoice_total', true );
            if (empty($total)) {
                $total = get_post_meta( $post_id, 'expense_price', true );
            }
            echo number_format((float)$total, 2, ",", ".") . ' $';
        break;
        case 'rental':
			$reservation_id = get_post_meta( $post_id, 'reservation_id', true );
			$apartment = get_the_terms( $reservation_id, 'apartment' )[0];
			$parking = get_the_terms( $reservation_id, 'parking' )[0];
			$owners = get_the_terms( $reservation_id, 'owner' );

			$print_output = '<a href="' . admin_url('post.php?post=' . $reservation_id . '&action=edit') . '">Reservation</a>: <a href="' . admin_url('edit.php?post_type=expenses&reservation_id=' . $reservation_id ) . '">' . $reservation_id . '</a><br/>';
			$print_output .= 'Apartment: <a href="' . admin_url('edit.php?post_type=expenses&apartment=' . $apartment->slug ) . '">' . $apartment->name . '</a><br/>';
			$print_output .= 'Parking: <a href="' . admin_url('edit.php?post_type=expenses&parking=' . $parking->slug ) . '">' . $parking->name . '</a><br/>';
			$print_output .= 'Owners: ';
			foreach ( $owners as $owner ) {
				$print_output .= '<a href="' . admin_url('edit.php?post_type=expenses&owner=' . $owner->slug ) . '">' . $owner->name . '</a> ';
			}
            echo $print_output;
        break;
		case 'payments':

            $payments = get_post_meta( $post_id, 'invoice_payments', true );
			$print_output = '<table class="payment_list">';
            if ( $payments ) {
				foreach ( $payments as $payment ) {

					$payment_date = get_post_meta( $payment, 'payment_date', true );
					$payment_type = get_the_terms( $payment, 'payment_types' )[0]->slug;
					$payment_amount = get_post_meta( $payment, 'payment_amount', true );

                	$print_output .= '<tr><td>' . Rentiq_class::rentiq_format_date( $payment_date ) . '</td><td>' . $payment_type . '</td><td align="right">' . $payment_amount . __( '$', 'rentiq' ) . '</td></tr>';
				}
            }

			$balance = Rentiq_class::is_invoice_paid( $post_id, 'text' );

            if (strpos($balance, 'full') !== false) {
            //if (str_contains( $balance, 'full' )) { 
                $balance_class = 'paid';
            } else if (strpos($balance, 'overpaid') !== false) {
            //} else if (str_contains( $balance, 'overpaid' )) { 
                $balance_class = 'overpaid';
            } else {
                $balance_class = 'topay';
            }
			
			$print_output .= '<tr><td colspan="3" align="center" class="'.$balance_class.'">' . $balance . '</td></tr>';
			$print_output .= '</table>';

			echo $print_output;
        break;
    }

}
foreach( array( 'expenses', 'invoice_agent', 'invoice_owner_payout', 'expenses'  ) as $cpt ) {
    add_action( "manage_{$cpt}_posts_custom_column", 'rentiq_add_expense_type_column', 10, 2 );
}



//// add APARTMENT and OWNER and INVOICE TYPE filter ////

function rentiq_expense_filter() {

    global $typenow;
    if ( $typenow == 'expenses' ) {

        $apartments = get_terms( array(
            'taxonomy' => 'apartment',
            'hide_empty' => false,
        ));
        //asort($apartments, SORT_NUMERIC); 
        $owners = get_terms( array(
            'taxonomy' => 'owner',
            'hide_empty' => false,
        ));
        $invoice_types = array( 'invoice_agent', 'invoice_owner_payout', 'expenses'  );
        
        $current_invoice_type = '';
        $current_apartment = '';
        $current_owner = '';
        if( isset( $_GET['invoice_type'] ) ) {
            $current_invoice_type = $_GET['invoice_type'];
        }
        if( isset( $_GET['apartment'] ) ) {
            $current_apartment = $_GET['apartment'];
        }
        if( isset( $_GET['owner'] ) ) {
            $current_owner = $_GET['owner'];
        } ?>

        <select name="export" id="export">
            <option value=""><?php echo __( 'No export', 'rentiq' ); ?></option>
            <option value="xls"><?php echo __( 'Excel', 'rentiq' ); ?></option>
        </select>

        <select name="invoice_type" id="invoice_type">
            <option value=""><?php echo __( 'All invoices', 'rentiq' ); ?></option>
            <?php foreach( $invoice_types as $invoice_type ) { 
                $it = get_post_type_object( $invoice_type );
                ?>
                <option value="<?php echo esc_attr( $invoice_type ); ?>" <?php if ( $current_invoice_type == $invoice_type ) { echo 'selected' ; } ?>><?php echo esc_attr( $it->labels->name ); ?></option>
            <?php } ?>
        </select>

        <select name="apartment" id="apartment">
            <option value=""><?php echo __( 'All apartments', 'rentiq' ); ?></option>
            <?php foreach( $apartments as $apartment ) { ?>
                <option value="<?php echo esc_attr( $apartment->slug ); ?>" <?php if ( $current_apartment == $apartment->slug ) { echo 'selected' ; } ?>><?php echo esc_attr( $apartment->name ); ?></option>
            <?php } ?>
        </select>

        <select name="owner" id="owner">
            <option value=""><?php echo __( 'All owners', 'rentiq' ); ?></option>
            <?php foreach( $owners as $owner ) { ?>
                <option value="<?php echo esc_attr( $owner->slug ); ?>" <?php if ( $current_owner == $owner->slug ) { echo 'selected' ; } ?>><?php echo esc_attr( $owner->name ); ?></option>
            <?php } ?>
        </select>
        
    <?php }
}
add_action( 'restrict_manage_posts', 'rentiq_expense_filter' );


///// ==== FILTER QUERY ==== /////

function rentiq_expenses_filterquery( $admin_query ) {

    global $pagenow;
    
    if (
        is_admin()
        && $admin_query->is_main_query()
        && $_GET['post_type'] == 'expenses'
        && in_array( $pagenow, array( 'edit.php' ) )
    ) {

        // date filter //

        if ( !empty( $_GET['expense_date_from'] ) || ! empty( $_GET['expense_date_to'] ) ) {

            $admin_query->set(
                'date_query',
                array(
                    'after' => sanitize_text_field( $_GET['expense_date_from'] ), // any strtotime()-acceptable format!
                    'before' => sanitize_text_field( $_GET['expense_date_to'] ),
                    'inclusive' => true, // include the selected days as well
                    'column'    => 'post_date' // 'post_modified', 'post_date_gmt', 'post_modified_gmt'
                )
            );
        }

        // reservation filter //

        if ( isset( $_GET['reservation_id'] ) && $_GET['reservation_id'] ) {
            $admin_query->set( 'meta_query', array(
                array(
                      'key' => 'reservation_id',
                      'value' => $_GET['reservation_id'],
                      'compare' => '=',
                )
          ));
        }
        
    }
    
    return $admin_query;

}
add_action( 'pre_get_posts', 'rentiq_expenses_filterquery' );



//// remove bulk actions ////

function remove_expenses_bulk_actions( $actions ){
    return array();
}
add_filter( 'bulk_actions-edit-expenses', 'remove_expenses_bulk_actions' );


//// ==== add STATS info ==== ////

add_action( 'load-edit.php', function() {
    add_filter( 'views_edit-expenses', 'rentiq_expenses_stats' );
});

function rentiq_expenses_stats() {

    // post_type=expenses & apartment=all & owner=anastasiia-sheredko & expense_date_from=2022-08-04 & expense_date_to=2022-08-25 & filter_action=Filter & paged=1 //

    global $wp_query;
    $total_amount = 0;

    if ( $wp_query->have_posts() ) {

        while ( $wp_query->have_posts() ) {

            $wp_query->the_post();
            global $post;
            $post_id = $post->ID;

            // total amount //

            $amount = get_post_meta( $post_id, 'invoice_total', true );

            if (empty($amount)) {
                $amount = get_post_meta( $post_id, 'expense_price', true );
            }
            $total_amount += (float)$amount;

        }

        ///// EXPORT /////

        $export_type = $_GET['export'];
        if ( isset( $export_type ) && $export_type !== '' ) {
            $export_file = rentiq_export_data( $wp_query, $export_type );
        }

    }

    echo '<h3>' . __( 'Total amount: ', 'rentiq' ) . number_format( $total_amount, 2, '.', ' ' ) . __( ' $', 'rentiq' ) . '</h3>';
    if ( isset( $export_file) ) {
        echo '<h3><a href="' . $export_file . '">' . __( 'Exported file here', 'rentiq' ) . '</a></h3>';
    }
    //echo '<a href="' . $export_file_url . '&file=PDF" id="export-pdf" class="button">'. __( 'Export PDF', 'rentiq' ) .'</a>';
    //echo '<a href="' . $export_file_url . '&file=CSV" id="export-pdf" class="button">'. __( 'Export CSV', 'rentiq' ) .'</a>';
};