<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

function rentiq_create_apartment_map() {

    $building = 'A';
    $floor_count = 21;
    $skip_floor = 4;

    ?>

    <table class="apartment_map_table">

        <tr class="apartment_map_floor"><td class="floor_number"><?php echo __( 'floor', 'rentiq' ); ?></td>
        <td>
            <table class="map_legend" width="80%">

                <tr><td class="room_status-available"><a></a><?php echo __( 'Available', 'rentiq' ); ?></td>
                    <td class="room_status-signed"><a></a><?php echo __( 'Signed', 'rentiq' ); ?></td>
                    <td class="room_status-draft"><a></a><?php echo __( 'Draft', 'rentiq' ); ?></td>
                    <td class="room_status-multiple"><a></a><?php echo __( 'Error - multiple reservations', 'rentiq' ); ?></td>
                </tr>

                <tr><td class="room_status-available not_sold"><a></a><?php echo __( 'Darker = LZ', 'rentiq' ); ?></td>
                    <td class="room_status-available owner_living"><a></a><?php echo __( 'Owner living', 'rentiq' ); ?></td>
                    <td width="25%"><a class="dashicons-before dashicons-edit-large"></a><?php echo __( 'Edit apartment', 'rentiq' ); ?></td>
                    <td width="25%"><a class="dashicons-before dashicons-admin-network"></a><?php echo __( 'Edit / create reservation', 'rentiq' ); ?></td>
                </tr>

                <!--
                <tr><td width="25%" class="room_info"><a class="dashicons-before dashicons-lightbulb paid"></a><?php //echo __( 'Invoice paid', 'rentiq' ); ?></td>
                    <td width="25%" class="room_info"><a class="dashicons-before dashicons-lightbulb"></a><?php //echo __( 'Invoice not paid', 'rentiq' ); ?></td>

                    <td width="25%"><a class="dashicons-before dashicons-edit-large"></a><?php //echo __( 'Edit apartment', 'rentiq' ); ?></td>
                    <td width="25%"><a class="dashicons-before dashicons-admin-network"></a><?php //echo __( 'Edit / create reservation', 'rentiq' ); ?></td>
                </tr>

                <tr><td width="25%"><a class="dashicons-before dashicons-lightbulb"></a><?php //echo __( 'Deposit invoice', 'rentiq' ); ?></td>
                    <td width="25%"><a class="dashicons-before dashicons-migrate"></a><?php //echo __( 'Deposit return invoice', 'rentiq' ); ?></td>
                    <td width="25%"><a class="dashicons-before dashicons-admin-users"></a><?php //echo __( 'Agent invoice', 'rentiq' ); ?></td>
                    <td width="25%"><a class="dashicons-before dashicons-controls-pause"></a><?php //echo __( 'Empty period invoice', 'rentiq' ); ?></td>
                </tr>
                -->

            </table>
        </td></tr>
    
    <?php
        
    for ( $f = $floor_count; $f >= 0; $f-- ) {
        if ($f !== $skip_floor) {
            ?>
            <tr class="apartment_map_floor"><td class="floor_number"><strong><?php echo $f; ?></strong></td>
            <td><table class="apartment_map_floor_body">
            <?php

            $apartment_args = array(
                'hide_empty'    => false,
                'meta_query'    => array(
                    array(
                        'key'       => 'apartment_building',
                        'value'     => $building,
                        'compare'   => '='
                    ),
                    array(
                        'key'       => 'apartment_floor',
                        'value'     => $f,
                        'compare'   => '='
                    )
                ),
                'taxonomy'      => 'apartment',
            );
            $apartments = get_terms( $apartment_args );
            $apartment_sides = array_chunk( $apartments, 8 );

            ?><tr><?php
            foreach ($apartment_sides[1] as $apartment) {

                rentiq_create_room_content( $apartment );
            
            }
            ?><td class="room empty_room"><span class="dashicons-before dashicons-sort"></span></td></tr>
            
            <tr><td class="room empty_room"><span class="dashicons-before dashicons-sort"></span></td><?php
            foreach ($apartment_sides[0] as $apartment) {
                
                rentiq_create_room_content( $apartment );
            
            }
            ?></tr><?php

            ?>
            </table></td></tr><tr class="floor_separator"></tr>
            <?php
        }
    }

    ?>
    </table>
    <?php

}

function rentiq_create_room_content( $apartment ) {

    $unit_number = get_term_meta( $apartment->term_id, 'apartment_number', true );
    $number = substr( $unit_number, -2);
    $owner_living = false;

    $edit_link = admin_url().'term.php?taxonomy=apartment&tag_ID='.$apartment->term_id;

    // color of room by avaibility //

    $apartment_reservations = Rentiq_class::get_reservation_by_apartment( $apartment->term_id );

    $apartment_owner = get_term_meta( $apartment->term_id, 'apartment_owner', true );
    if ( $apartment_owner == 560 ) {
        $sold = 'not_sold';
    } else {
        $sold = '';
    }

    if ( count($apartment_reservations) > 1 ) {

        $room_status = 'multiple';
        $reservation_link = '#';

    } else if ( count($apartment_reservations) == 1 ) {

        $reservation_id = $apartment_reservations[0]->ID;
        $room_status = $apartment_reservations[0]->post_status;
        $reservation_link = admin_url().'post.php?post='.$reservation_id.'&action=edit';
        $apartment_tenant = get_post_meta( $reservation_id, 'reservation_tenant_type', true );

        if ($apartment_tenant == 'owner') {
            $room_status .= ' owner_living';
            $owner_living = true;
        }
    
    } else {
        
        $room_status = 'available';
        $reservation_link = admin_url().'post-new.php?post_type=reservation&apartment='.$apartment->term_id;
    }

    $documents_array = array(
        array(
            'type' => 'invoice_deposit',
            'icon' => 'dashicons-lightbulb',
            'tip' => __( 'Deposit invoice', 'rentiq' ),
            'owner' => 'hide',
        ),
        array(
            'type' => 'invoice_deporeturn',
            'icon' => 'dashicons-migrate',
            'tip' => __( 'Termination invoice', 'rentiq' ),
            'owner' => 'hide',
        ),
        array(
            'type' => 'invoice_agent',
            'icon' => 'dashicons-admin-users',
            'tip' => __( 'Agent invoice', 'rentiq' ),
            'owner' => 'hide',
        ),
        array(
            'type' => 'invoice_ownerempty',
            'icon' => 'dashicons-controls-pause',
            'tip' => __( 'Empty period invoice', 'rentiq' ),
            'owner' => 'show',
        ),
    );


    // render the fields //

    echo '<td class="room room_'.$unit_number.' room_status-'.$room_status.' ' . $sold . '">';
    echo '<span class="room_number">'.$unit_number.'</span>';
    echo '<a class="room_edit dashicons-before dashicons-edit-large" href="'.$edit_link.'"></a>';
    echo '<a class="room_reservation dashicons-before dashicons-admin-network" href="'.$reservation_link.'"></a>';
    echo '<table class="room_info"><tr>';
    
    // reservation info //
    
    if ( isset( $reservation_id ) ) {

        foreach( $documents_array as $document ) {
            
            $document_id = get_post_meta( $reservation_id, 'reservation_file-'.$document['type'], true );

            if ( isset($document_id) && $document_id != '' ) {

                $document_paid = Rentiq_class::is_invoice_paid( $document_id );
                if ( $document_paid === true ) {
                    $paid = 'paid';
                } else {
                    $paid = '';
                }
                $payment_text = ' - ' . Rentiq_class::is_invoice_paid( $document_id, 'text' );

            } else {
                $paid = '';
                $payment_text = __( ' - not created', 'rentiq' );
            }
            $tooltip = $document['tip'] . $payment_text;

            echo '<td><span class="tooltip--triangle" data-tooltip="'. $tooltip .'"><a class="dashicons-before '. $document['icon'] . ' ' . $paid . '"></a></span></td>';
        }
    }

    // monthly invoice //

    echo '</tr><tr>';
    if ( isset( $reservation_id ) ) {

        $last_rental_invoice = Rentiq_class::get_last_reservation_invoice( $reservation_id, 0 );
        $last_rental_invoice_id = $last_rental_invoice['id'];

        if ( $last_rental_invoice_id != $reservation_id ) {

            $document_paid = Rentiq_class::is_invoice_paid( $last_rental_invoice_id );

            if ( $document_paid === true ) {
                $paid = 'paid';
            } else {
                $paid = '';
            }
            $tooltip_rental = __( 'Monthly invoice', 'rentiq' ) . ' - ' . Rentiq_class::rentiq_format_date($last_rental_invoice['date']) . ' - ' . Rentiq_class::is_invoice_paid( $last_rental_invoice_id, 'text' );
            
            echo '<td colspan="2"><span class="tooltip--triangle" data-tooltip="'. $tooltip_rental .'"><a class="dashicons-before dashicons-backup ' . $paid . '"></a></span></td>';
        
        }

        // warning for new monthly invoice //

        $reservation_date = get_post_meta( $reservation_id, 'reservation_date_from', true );
        if ( $reservation_date ) {

            $this_month = date('Ym01');
            $next_month = date('Ymd', strtotime( $this_month . ' +1 month' ));
            $reservation_day = intval( date('d', strtotime( $reservation_date )) - 1 );

            if ( $owner_living == false ) {
                $invoicing_day = date('Ymd', strtotime( $this_month . ' +'. $reservation_day .' days'));
            } else {
                $invoicing_day = $next_month;
            }
            $first_warning = date('Ymd', strtotime( $invoicing_day . ' -7 days' ));
            $today_date = date('d/m/Y');

            //error_log('first-this-month: '.$this_month . ' // reservation-day: ' . $reservation_day . ' // invoicing: ' . $invoicing_day . ' // first warning: ' . $first_warning );

            if ( Rentiq_class::check_if_date_in_range( $today_date, $first_warning, $invoicing_day ) ) {

                $two_weeks_before_invoicing = date('Ymd', strtotime( $invoicing_day . ' -14 days'));
                $two_weeks_after_invoicing = date('Ymd', strtotime( $invoicing_day . ' +14 days'));
                $last_invoice_date = date('d/m/Y', strtotime( $last_rental_invoice['date'] ));

                //error_log('last invoice: '.$last_invoice_date . ' // two weeks before: ' . $two_weeks_before_invoicing . ' // after: ' . $two_weeks_after_invoicing );

                $warning_green = '';
                if ( Rentiq_class::check_if_date_in_range( $last_invoice_date, $two_weeks_before_invoicing, $two_weeks_after_invoicing ) ) {

                    $warning_green = 'already_created';
                    $tooltip_warning = __( 'New monthly invoice already created.', 'rentiq' );

                } else {
                    $tooltip_warning = __( 'New monthly invoice needs to be created.', 'rentiq' );
                }
                echo '<td colspan="2"><span class="tooltip--triangle" data-tooltip="'. $tooltip_warning .'"><a class="dashicons-before dashicons-warning monthly_warning '.$warning_green.'"></a></span></td>';

            }
        }

    }
    echo '</tr></table>';

}