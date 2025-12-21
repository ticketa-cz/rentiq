<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

function rentiq_create_parking_map() {

    ?>

    <table class="apartment_map_table">

        <tr class="apartment_map_floor"><td class="floor_number"></td>
        <td>
            <table class="map_legend" width="80%">

                <tr><td class="room_status-available"><a></a><?php echo __( 'Available', 'rentiq' ); ?></td>
                    <td class="room_status-signed"><a></a><?php echo __( 'Signed', 'rentiq' ); ?></td>
                    <td class="room_status-draft"><a></a><?php echo __( 'Draft', 'rentiq' ); ?></td>
                    <td class="room_status-multiple"><a></a><?php echo __( 'Error - multiple reservations', 'rentiq' ); ?></td>
                </tr>

                <tr><td class="room_status-available not_sold"><a></a><?php echo __( 'Darker = LZ', 'rentiq' ); ?></td>
                    <td class="room_status-available owner_living"><a></a><?php echo __( 'Owner parking', 'rentiq' ); ?></td>
                    <td width="25%"><a class="dashicons-before dashicons-edit-large"></a><?php echo __( 'Edit parking', 'rentiq' ); ?></td>
                    <td width="25%"><a class="dashicons-before dashicons-admin-network"></a><?php echo __( 'Edit / create reservation', 'rentiq' ); ?></td>
                </tr>

            </table>
        </td></tr>
    
    <?php

    $parking_side = array(
        'outside' => __( 'Outside', 'rentiq' ),
        'building_A' => __( 'Inside', 'rentiq' ),
    );

    foreach ( $parking_side as $side_id => $side_name ) {
        ?>
        <tr class="apartment_map_floor"><td class="floor_number"><strong><?php echo $side_name; ?></strong></td>
        <td><table class="apartment_map_floor_body">
        <?php

        $parking_args = array(
            'hide_empty'    => false,
            'taxonomy'      => 'parking',
            'meta_key'      => 'parking_number',
            'orderby'       => 'meta_value_num',
            'order'         => 'ASC',
        );
        $parkings = get_terms( $parking_args );
        $cell = 1;

        ?><tr><?php
        foreach ($parkings as $parking) {

            $parking_place = get_term_meta( $parking->term_id, 'parking_location', true );
            if ( $parking_place == $side_id ) {
                rentiq_create_parking_content( $parking );

                if ( $cell < 8 ) {
                    $cell++;
                } else {
                    ?></tr><tr><?php
                    $cell = 1;
                }
            }

        }
        ?></tr>
        </table></td></tr><tr class="floor_separator"></tr>
        <?php
    }

    ?>
    </table>
    <?php

}

function rentiq_create_parking_content( $parking ) {

    $unit_number = get_term_meta( $parking->term_id, 'parking_number', true );
    $number = substr( $unit_number, -2);
    $owner_living = false;

    $edit_link = admin_url().'term.php?taxonomy=parking&tag_ID='.$parking->term_id;

    // color of room by avaibility //

    $parking_reservations = Rentiq_class::get_reservation_by_parking( $parking->term_id );

    $parking_owner = get_term_meta( $parking->term_id, 'parking_owner', true );
    if ( $parking_owner == 560 ) {
        $sold = 'not_sold';
    } else {
        $sold = '';
    }

    if ( count($parking_reservations) > 1 ) {

        $parking_status = 'multiple';
        $reservation_link = '#';

    } else if ( count($parking_reservations) == 1 ) {

        $reservation_id = $parking_reservations[0]->ID;
        $parking_status = $parking_reservations[0]->post_status;
        $reservation_link = admin_url().'post.php?post='.$reservation_id.'&action=edit';
        $parking_tenant = get_post_meta( $reservation_id, 'reservation_tenant_type', true );
        $parking_image = wp_get_attachment_thumb_url( get_post_meta( $reservation_id, 'reservation_parking_img', true ) );

        if ($parking_tenant == 'owner') {
            $parking_status .= ' owner_living';
            $owner_living = true;
        }
    
    } else {
        
        $parking_status = 'available';
        $reservation_link = admin_url().'post-new.php?post_type=reservation&parking='.$parking->term_id;
        $parking_image = false;
    }

    // render the fields //
    

    echo '<td width="12.5%" class="parking room_'.$unit_number.' room_status-'.$parking_status.' ' . $sold . '">';
    echo '<span class="room_number">'.$unit_number.'</span>';
    echo '<a class="room_edit dashicons-before dashicons-edit-large" href="'.$edit_link.'"></a>';
    echo '<a class="room_reservation dashicons-before dashicons-admin-network" href="'.$reservation_link.'"></a>';
    if ( $parking_image !== false ) {
        echo '<span class="mytooltip tooltip-effect-1">';
        echo '<span class="tooltip-item"><a class="room_image dashicons-before dashicons-format-image"></a></span>';
        echo '<span class="tooltip-content clearfix"><img src="'. $parking_image .'"></span></span>';
    }
    echo '<table class="room_info"><tr>';
    

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