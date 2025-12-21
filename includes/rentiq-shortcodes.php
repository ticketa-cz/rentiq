<?php

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

//// shortcodes ////

function rentiq_contract_shortcode( $atts ) {
	
    extract(shortcode_atts(array(
        'item' => 'not_specified',
        'reservation_id' => '0',
        'document_id' => '0',
     ), $atts));

     switch ( $item ) {

        // company //
        case 'company_name':
                return get_option( 'options_company_name' );
            break;
        case 'company_address':
                return get_option( 'options_company_address' );
            break;
        case 'company_email':
                return get_option( 'options_company_email' );
            break;
        case 'company_phone':
                return get_field( 'company_phone', 'option' );
            break;

        // tenant //
        case 'tenant_name':
                return get_post_meta( $reservation_id, 'reservation_tenant_name', true );
            break;
        case 'tenant_birthdate':
                $birthdate = get_post_meta( $reservation_id, 'reservation_tenant_birthdate', true );
                if ( $birthdate ) {
                    return Rentiq_class::rentiq_format_date( $birthdate );
                } else {
                    return '';
                }
            break;
        case 'tenant_country':
                return get_field( 'reservation_tenant_country', $reservation_id );
                break;
        case 'tenant_id':
                return get_post_meta( $reservation_id, 'reservation_tenant_id', true );
            break;
        case 'tenant_phone':
                return get_field( 'reservation_tenant_phone', $reservation_id );
            break;
        case 'tenant_email':
                return get_post_meta( $reservation_id, 'reservation_tenant_email', true );
            break;

        // reservation //
        case 'room_number':
                $apartment_id = get_post_meta( $reservation_id, 'reservation_apartment', true );
                return Rentiq_class::get_rental_number_by_id( $apartment_id, 'apartment' );
            break;
        case 'rent':
                return get_post_meta( $reservation_id, 'reservation_apartment_price', true );
            break;
        case 'date_from':
                $date_from = get_post_meta( $reservation_id, 'reservation_date_from', true );
                return Rentiq_class::rentiq_format_date( $date_from );
            break;
        case 'date_till':
                return Rentiq_class::get_reservation_date_till( $reservation_id );
            break;
        case 'deposit_amount':
                return get_post_meta( $reservation_id, 'reservation_apartment_price', true ) * get_post_meta( $reservation_id, 'reservation_deposit_count', true );
            break;
        case 'electricity_value':
                return get_post_meta( $reservation_id, 'reservation_electricity_value', true );
            break;
        case 'water_value':
                return get_post_meta( $reservation_id, 'reservation_water_value', true );
            break;

        // services //
        case 'electricity_price':
            return get_field( 'electricity_price', 'option' );
        break;
        case 'water_price':
            return get_field( 'water_price', 'option' );
        break;
        case 'services_internet':
                $internet_id = get_post_meta( $reservation_id, 'reservation_internet', true );
                if ($internet_id == 'custom-net') {

                    $internet_price = get_post_meta( $reservation_id, 'reservation_internet_custom_price', true );
               
                } else if ($internet_id == 'no-net') {

                    $internet_price = __( 'no internet - ', 'rentiq' ) . '0';
                
                } else {

                    if( have_rows( 'internet_options' , 'option' ) ) {
                        while ( have_rows( 'internet_options' , 'option' ) ) {
                            the_row();
                            if ( get_sub_field('internet_option_slug') != $internet_id ) {
                                continue;
                            }
                            $internet_price = get_sub_field('internet_option_price');
                        }
                    }
                }
                return $internet_price;
            break;
        case 'services_tv':
                $tv_option = get_post_meta( $reservation_id, 'reservation_tv', true );
                if ($tv_option == 'yes') {
                    return get_field( 'tv_monthly_price', 'option' );
                } else {
                    return __( 'no tv - ', 'rentiq' ) . '0';
                }
            break;
        case 'services_garbage':
                return __( 'included in rent - ', 'rentiq' ) . '0';
            break;
        case 'services_parking':
                $reservation_type = get_post_meta( $reservation_id, 'reservation_type', true );
                if ($reservation_type == 'both') { 
                    return get_post_meta( $reservation_id, 'reservation_parking_price', true );
                } else {
                    return __( 'no parking - ', 'rentiq' ) . '0';
                }
            break;
        case 'services_housekeeping':
                $apartment_id = get_post_meta( $reservation_id, 'reservation_apartment', true );
                $apartment_type = get_term_meta ( $apartment_id, 'apartment_type', true );
                $reservation_housekeeping = get_post_meta( $reservation_id, 'reservation_housekeeping', true );

                if ( $reservation_housekeeping == 'yes' ) {
                    if( have_rows( 'apartment_types' , 'option' ) ) {
                        while ( have_rows( 'apartment_types' , 'option' ) ) {
                            the_row();
                            if ( get_sub_field('apartment_type_slug') != $apartment_type ) {
                                continue;
                            }
                            $housekeeping_price = get_sub_field('apartment_type_housekeeping_fees');

                        }
                    }
                } else {
                    $housekeeping_price = __( 'no housekeeping - ', 'rentiq' ) . '0';
                }
                return $housekeeping_price;
            break;
        
        // other //
        case 'company_account':
                return get_field( 'company_bank_account', 'option' );
            break;
        case 'property_address':
                return get_field( 'property_address', 'option' );
            break;


        // extension //
        case 'extension_count':
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
            return Rentiq_class::ordinal_number( count($extension_posts) );
        break;
        case 'original_contract_date':
            $contract_posts = get_posts( array(
                'post_type'   => array( 'contract' ),
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
            return Rentiq_class::rentiq_format_date( get_the_date( 'Ymd', $contract_posts[0] ) );
        break;
        case 'extension_month_count':
            $document_date_range = get_post_meta( $document_id, 'extension_date_range', true );
            $docdate_from = strtotime( explode( '===', $document_date_range )[0] );
            $docdate_till = strtotime( explode( '===', $document_date_range )[1] );
            $year_from = date('Y', $docdate_from);
            $year_till = date('Y', $docdate_till);
            $month_from = date('m', $docdate_from);
            $month_till = date('m', $docdate_till);
            $month_count = (($year_till - $year_from) * 12) + ($month_till - $month_from);

            return $month_count;
        break;
        case 'extension_from':
            $document_date_range = get_post_meta( $document_id, 'extension_date_range', true );
            $docdate_from = explode( '===', $document_date_range )[0];
            error_log( $document_date_range . '  ' . $docdate_from );
            return Rentiq_class::rentiq_format_date( $docdate_from );
        break;
        case 'extension_till':
            $document_date_range = get_post_meta( $document_id, 'extension_date_range', true );
            $docdate_till = explode( '===', $document_date_range )[1];
            return Rentiq_class::rentiq_format_date( date('Ymd', strtotime( $docdate_till )) );
        break;
        case 'extension_date':
            return Rentiq_class::rentiq_format_date( get_the_date( 'Ymd', $document_id ) );
        break;
        case 'contract_lenght':
            return get_post_meta( $reservation_id, 'reservation_contract_length', true );
        break;

    }
	
}
add_shortcode('rentiq_contract', 'rentiq_contract_shortcode');