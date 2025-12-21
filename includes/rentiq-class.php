<?php

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( !class_exists( 'Rentiq_class' ) ) {
	
	add_action( 'init', array ( 'Rentiq_class', 'init' ) );

	class Rentiq_class {
		
		//// init ////
		
		public static function init() {
			new self;
		}

		//// construct ////
		
		function __construct() {
			
			$this->title = __( 'Rentiq class', 'rentiq' );
			
		}
		
		//// get owner estates ////
		
		public static function get_owner_estates( $owner_id, $rental_type ) {
			
			$estates = get_terms( array(
				'hide_empty' => false,
				'meta_query' => array(
					array(
					   'key'       => $rental_type.'_owner',
					   'value'     => $owner_id,
					   'compare'   => '='
					)
				),
				'taxonomy'  => $rental_type,
			) );

            return $estates;
		}
			
        public static function list_estate_links( $terms, $rental_type ) {

            $content = '';
            $sign = '';

            foreach ($terms as $term_id) {
                $term = get_term($term_id, $rental_type);

                if ($rental_type == 'parking') {
                    $location = get_field( 'parking_location', $term );
                    switch ($location) {
                        case 'outside': $sign = 'OUT-'; break;
                        case 'under_roof': $sign = 'ROOF-'; break;
                        case 'building_A': $sign = 'IN-'; break;
                    }
                }

                $content .= '<a href="' . admin_url() . 'term.php?taxonomy=' . $rental_type . '&tag_ID=' . $term->term_id . '">' . $sign . get_field( $rental_type.'_number', $term ) . '</a>';
                if ( next($terms) ) { 
                    $content .= ', ';
                }
            }

            return $content;
        }


        //// get date till ////

        public static function get_reservation_date_till( $reservation_id ) {

            $date_from = get_post_meta( $reservation_id, 'reservation_date_from', true );
            $rent_length = get_post_meta( $reservation_id, 'reservation_contract_length', true );
            $date_till = Rentiq_class::rentiq_format_date( $date_from, '+' . $rent_length . ' months' );

            return $date_till;

        }

        //// get apartment / parking number by id ////

        public static function get_rental_number_by_id( $rental_id, $rental_type ) {

            $rental_number = get_term_meta( $rental_id, $rental_type.'_number', true );
            return $rental_number;

        }

        //// get reservation properties owners ////

        public static function get_reservation_owners( $reservation_id ) {

            $reservation_type = get_post_meta( $reservation_id, 'reservation_type', true );

            $apartment_id = get_post_meta( $reservation_id, 'reservation_'.$reservation_type, true );
            $apt_owner = get_term_meta( $apartment_id, $reservation_type.'_owner', true );
            $owners = array( intval( $apt_owner ) );

            return $owners;

        }

        //// format date ////

        public static function rentiq_format_date( $date_string, $modify = NULL ) {
            
            if ( !is_string( $date_string ) || $date_string == '' || is_bool( $date_string ) ) {
                return;
            }
            $date = DateTime::createFromFormat( 'Ymd', $date_string );
            if ( isset( $modify ) ) {
                $date->modify( $modify );
            }
            return $date->format('d/m/Y');

        }  
        
        
        //// ordinal numbers ////

        public static function ordinal_number( $number ) {
            $ends = array('th','st','nd','rd','th','th','th','th','th','th');
            if ((($number % 100) >= 11) && (($number%100) <= 13))
                return $number. 'th';
            else
                return $number. $ends[$number % 10];
        }


        //// get date of last reservation invoice ////

        public static function get_last_reservation_invoice( $reservation_id, $last_or_the_one_before ) {

            $last_invoice = get_posts( array(
                'post_type'   => 'invoice_rental',
                'numberposts' => 2,
                'order_by'    => 'date',
                'order'       => 'DESC',
                'post_status' => 'any',
                'meta_query'  => array(
                    array(
                        'key'    => 'reservation_id',
                        'value'  => $reservation_id,
                    )
                ),
            ));

            if ( $last_invoice && $last_invoice[$last_or_the_one_before] ) {


                $last_invoice_id = $last_invoice[$last_or_the_one_before]->ID;
                $last_invoice_date = get_the_date( 'Ymd', $last_invoice[$last_or_the_one_before] );

            } else {

                $last_invoice_id = $reservation_id;
                $last_invoice_date = get_post_meta( $reservation_id, 'reservation_date_from', true );
            }

            return array( 'date' => $last_invoice_date, 'id' => $last_invoice_id );
        }

        //// check if date is within range of dates ////

        public static function check_if_date_in_range( $expense_date, $date_from, $date_till ) {

            $check_date = DateTime::createFromFormat( 'd/m/Y', $expense_date );
            $first_date = DateTime::createFromFormat( 'Ymd', $date_from );
            $date_till  = DateTime::createFromFormat( 'Ymd', $date_till );

            if ( $first_date < $check_date && $check_date <= $date_till ) {
                return true;
            } else {
                return false;
            }

        }

        //// check if date is later than other ////

        public static function check_if_date_is_later( $date_from, $checked_date ) {

            $check_date = DateTime::createFromFormat( 'Ymd', $checked_date );
            $first_date = DateTime::createFromFormat( 'Ymd', $date_from );

            if ( $first_date < $check_date ) {
                return true;
            } else {
                return false;
            }

        }

        //// get month count since date ////

        public static function get_day_count_since( $date ) {

            $check_date = DateTime::createFromFormat( 'Ymd', $date );
            $today_date = new DateTime('NOW');

            $interval = $check_date->diff( $today_date );
            return $interval->format('%a');

        }

        //// does this owner owns this ////

        public static function does_he_own_it( $owner_id, $rental_id ) {

            $rental_term = get_term( $rental_id );
            $rental_type = $rental_term->taxonomy;
            $owner = get_field( $rental_type.'_owner', $rental_term );

            if ( $owner == $owner_id ) {
                return true;
            } else {
                return false;
            }

        }

        //// get reservation by apartment id ////

        public static function get_reservation_by_apartment( $apartment_id ) {

            $res_args = array(
                'post_type' => 'reservation',
                'post_status' => array( 'draft', 'signed' ),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'apartment',
                        'terms' => $apartment_id,
                    ),
                ),
            );
            $reservations = get_posts( $res_args );
            return $reservations;

        }


        //// get reservation by parking id ////

        public static function get_reservation_by_parking( $parking_id ) {

            $res_args = array(
                'post_type' => 'reservation',
                'post_status' => array( 'draft', 'signed' ),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'parking',
                        'terms' => $parking_id,
                    ),
                ),
            );
            $reservations = get_posts( $res_args );
            return $reservations;

        }


        //// get currently edited term ////

        public static function get_term_being_edited() {

            global $taxnow;
            if ( ! $taxnow || empty( $_GET['tag_ID'] ) ) {
                return null;
            }
        
            $term_id = absint( $_GET['tag_ID'] );
            $term    = get_term( $term_id, $taxnow );

            return $term instanceof WP_Term ? $term : null;
        }

        //// get last apartment reservation before this one ////

        public static function get_last_apartment_reservation( $apartment_id ) {

            $res_args = array(
                'post_type'   => 'reservation',
                'post_status' => array( 'terminated' ),
                'orderby'     => 'date',
                'order'       => 'DESC',
                'tax_query'   => array(
                    array(
                        'taxonomy'  => 'apartment',
                        'terms'     => $apartment_id,
                    ),
                ),
            );
            $reservations = get_posts( $res_args );

            return $reservations[0]->ID;

        }



        //// check balance of the invoice ////

        public static function is_invoice_paid( $invoice_id, $output_type = 'number' ) {

            $sum = 0;
            $invoice_total = get_post_meta( $invoice_id, 'invoice_total', true );
            $payments = get_post_meta( $invoice_id, 'invoice_payments', true );
            if ( $payments ) {
                foreach( $payments as $payment ) {
                    $payment_amount = get_post_meta( $payment, 'payment_amount', true );
                    $sum = (float)$sum + (float)$payment_amount;
                }
            }
            
            // output //

            if ( number_format( (float)$invoice_total, 2 ) === number_format( (float)$sum, 2)  ){

                // is paid full //
                /*error_log('id: '.$invoice_id);
                error_log('invoice: '.$invoice_total);
                error_log('sum: '.$sum);*/

                if ( $output_type == 'number' ) {
                    return true;
                } else {
                    return __( 'Paid full', 'rentiq' );
                }
                
            } else {

                $balance = (float)$invoice_total - (float)$sum;
                //error_log('invoice: '.$invoice_total);
                //error_log('balance: '.$balance);
                
                if ( $output_type == 'number' ) {

                    return $balance;

                } else {

                    if ( (float)$balance < 0 ) {
                        return number_format( (float)($balance * -1), 2 ) . __( '$ overpaid!', 'rentiq' );
                    } else {
                        return number_format( (float)$balance, 2 ) . __( '$ to pay', 'rentiq' );
                    }
                }
            }
        }




        //// add payment to invoice payments lits ////

        public static function change_invoice_payments( $invoice_id, $payment_id, $change ) {

            // update the meta //

            $document_payments = get_post_meta( $invoice_id, 'invoice_payments', true );
            if ( empty( $document_payments )) { $document_payments = array(); }
            if ( $change == 'delete' ) {
                $document_payments_merged = array_diff( $document_payments, (array)$payment_id );
            } else {
                $document_payments_merged = array_merge( $document_payments, (array)$payment_id );
            }
            
            update_post_meta( $invoice_id, 'invoice_payments', $document_payments_merged );

            // recreate the pdf //

            Rentiq_class::delete_pdf_document( $invoice_id, 'receipt' ); 

            $document_type = get_post_type( $invoice_id );
            if ( $document_type == 'invoice_owner' || $document_type == 'invoice_owner_payout' ) {
                $owner_id = get_the_terms( $invoice_id, 'owner' );
                $receipt_pdf = Rentiq_class::create_new_owner_pdf( $invoice_id, 'receipt', $owner_id[0]->term_id );
            } else {
                $reservation_id = get_post_meta( $invoice_id, 'reservation_id', true );
                $receipt_pdf = Rentiq_class::create_new_document_pdf( $invoice_id, 'receipt', $reservation_id );
            }

            // ulozi jen posledni uctenku //
            update_post_meta( $invoice_id, 'file-receipt', $receipt_pdf );
        
        }




        //// delete attached pdf document ////

        public static function delete_pdf_document( $document_id, $document_type ) {

            $document_file = get_post_meta( $document_id, 'file-'.$document_type, true );

            if ( isset( $document_file) && $document_file !== '' ) {

                $wp_upload_dir = wp_upload_dir();
                $file = $wp_upload_dir['basedir'] . '/' . $document_file;

                unlink( $file );
                delete_post_meta( $document_id, 'file-'.$document_type );
            }

        }


        

        //// show attached documents ////

        public static function show_attached_documents( $document_type, $object_id, $meta_term ) {

            $meta_query = '';
            $tax_query = '';
            switch ( $meta_term ) {
                case 'meta':
                    $meta_query = array(
                        array(
                            'key'    => 'reservation_id',
                            'value'  => $object_id,
                        )
                    );
                break;
                case 'term':
                    $tax_query = array(
                        array(
                            'taxonomy' => 'owner',
                            'field' => 'term_id',
                            'terms' => $object_id,
                        )
                    );
                break;
            }

            $documents = get_posts( array(
                'post_type'   => $document_type,
                'numberposts' => -1,
                'post_status' => 'any',
                'meta_query'  => $meta_query,
                'tax_query'  => $tax_query,
            ));
            $field_data = '';
            
            if ( $documents ) {
                foreach ( $documents as $document ) {
    
                    $document_id = $document->ID;
                    $doc_url = site_url('wp-content/uploads/' . get_post_meta( $document_id, 'file-'.$document_type, true ));
    
                    $field_data .= '<div class="doc" id="doc-' . $document_id . '">';
                    $field_data .= '<a class="doc_link dashicons-before dashicons-media-text" href="' . $doc_url . '" target="_blank">' . $document->post_title . '</a>';
                    
                    if ( $document_type !== 'contract_tenant' || $document_type !== 'contract_extension' ) {

                        $doc_payment = Rentiq_class::is_invoice_paid( $document_id );
                        $doc_sent_note = get_post_meta( $document_id, 'doc_sent', true );

                        if ( $doc_payment === true ) {
                            $doc_paid = 'doc_paid';
                        } else {
                            if ( $doc_payment < 0 ) {
                                $doc_paid = 'doc_overpaid';
                            } else {
                                $doc_paid = '';
                            }
                        }
                        if ( $doc_sent_note != false ) {
                            $doc_sent = 'doc_sent';
                        } else {
                            $doc_sent = '';
                        }

                        $field_data .= '<a class="doc_pay ' . $doc_paid . ' dashicons-before dashicons-money-alt" id="pay-' . $document_id . '" /></a>';
                    }
    
                    $field_data .= '<a class="doc_send ' . $doc_sent . ' dashicons-before dashicons-buddicons-pm" id="send-' . $document_id . '"></a>';
                    $field_data .= '<a class="doc_del dashicons-before dashicons-no" id="del-' . $document_id . '"></a>';
                    $field_data .= '</div>';

                }
            }

            return $field_data;

        }



        //// send email ////

        public static function send_email( $receiver_email, $subject, $message, $attachment ) {

            $sender_name = get_field( 'company_name', 'option' );
            $sender_email = get_field( 'company_email', 'option' );
            $wp_upload_dir = wp_upload_dir();
            $attachment = array( $wp_upload_dir['basedir'] .'/'. $attachment );
            $headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: '.$sender_name.' <'.$sender_email.'>' );

            $mail_sent = false;
            $mail_sent = wp_mail( $receiver_email, html_entity_decode($subject), $message, $headers, $attachment );
            return $mail_sent;

        }

        


        ///// =============================================================== /////
                            //// create new document pdf ////




        public static function create_new_document_pdf( $document_id, $document_type, $reservation_id ) {

            // setup document data //

            $reservation_type = get_post_meta( $reservation_id, 'reservation_type', true );            
            switch( $reservation_type ) {

                case 'apartment':

                    $apartment_price = get_post_meta( $reservation_id, 'reservation_apartment_price', true );
                    $rent_total = floatval( $apartment_price );
                    $number_of_entry_cards = get_post_meta( $reservation_id, 'reservation_entry_cards', true );
                    $entry_cards_total = floatval( get_field( 'entry_card_price', 'option' ) * $number_of_entry_cards );

                break;

                case 'parking':

                    $parking_price = get_post_meta( $reservation_id, 'reservation_parking_price', true );
                    $rent_total = floatval( $parking_price );
                    $parking_id = get_post_meta( $reservation_id, 'reservation_parking', true );
                    $parking_number = Rentiq_class::get_rental_number_by_id( $parking_id, 'parking' );

                break;

            }
            
            $apartment_id = get_post_meta( $reservation_id, 'reservation_apartment', true );
            $owner_id = get_term_meta( $apartment_id, 'apartment_owner', true );

            if ( $document_type == 'invoice_ownerempty' ) {
                $tenant_type = 'owner';
            } else {
                $tenant_type = get_post_meta( $reservation_id, 'reservation_tenant_type', true );
            }

            switch( $tenant_type ) {

                case 'tenant':

                    $tenant_name = get_post_meta( $reservation_id, 'reservation_tenant_name', true );
                    $tenant_country = get_field( 'reservation_tenant_country', $reservation_id );
                    $tenant_birthdate = get_post_meta( $reservation_id, 'reservation_tenant_birthdate', true );
                    $tenant_email = get_post_meta( $reservation_id, 'reservation_tenant_email', true );
                    $tenant_phone = get_field( 'reservation_tenant_phone', $reservation_id );

                break;

                case 'owner':

                    $tenant_name = get_term( $owner_id )->name;
                    $tenant_country = get_field( 'owner_country', 'owner_' . $owner_id );
                    $tenant_birthdate = get_term_meta( $owner_id, 'owner_birthdate', true );
                    $tenant_email = get_term_meta( $owner_id, 'owner_email', true );
                    $tenant_phone = get_field( 'owner_phone', 'owner_' . $owner_id );

                break;
                
            }


            $company_name = get_field( 'company_name', 'option' );
            $company_address = get_field( 'company_address', 'option' );
            $company_phone = get_field( 'company_phone', 'option' );
            $company_email = get_field( 'company_email', 'option' );
            $company_logo = get_field( 'company_logo', 'option' );
            $company_account = get_field( 'company_bank_account', 'option' );

            $apartment_type = get_term_meta( $apartment_id, 'apartment_type', true );
            $water_price = floatval( get_field( 'water_price', 'option' ) );
            $electricity_price = floatval( get_field( 'electricity_price', 'option' ) );
            $apartment_number = Rentiq_class::get_rental_number_by_id( $apartment_id, 'apartment' );

            if ( $document_type == 'receipt' ) {
                $document_title = get_the_title( $document_id ) . __( ' receipt', 'rentiq' );
            } else {
                $document_title = get_the_title( $document_id );                
            }
            $sanitized_title = preg_replace('/(.)\1{3,}/', '', sanitize_title( $document_title ) );
            $this_invoice_date = get_the_date( 'Ymd', $document_id );

            $invoice_items = array();


            // setup document type data //

                switch( $document_type ) {

                    case 'contract_tenant':

                        $template_type = 'contract';
                        $document_code = 'CT';
                        $contract_type_id = get_post_meta( $reservation_id, 'reservation_contract_type', true );

                    break;
                    case 'contract_extension':

                        $template_type = 'contract';
                        $document_code = 'CE';
                        $template_post = get_page_by_path( 'contract-extension', OBJECT, 'contract_type' );
                        $contract_type_id = $template_post->ID;

                    break;
                    case 'invoice_deposit':

                        $template_type = 'invoice';
                        $invoice_name = __( 'deposit invoice', 'rentiq' );
                        $deposit_count = get_post_meta( $reservation_id, 'reservation_deposit_count', true );
                        $document_code = 'DP';
                        $invoice_items[] = array (
                            'name' => __( 'Deposit', 'rentiq' ),
                            'description' => __( 'Rental deposit for possible expenses - apartment #', 'rentiq' ) . $apartment_number,
                            'price' => $apartment_price * $deposit_count,
                        );
                        if ( $tenant_type == 'tenant' ) {
                            $invoice_items[] = array (
                                'name' => __( 'Utility deposit', 'rentiq' ),
                                'description' => __( 'Deposit for possible utility expenses - apartment #', 'rentiq' ) . $apartment_number,
                                'price' => get_post_meta( $reservation_id, 'reservation_deposit_utility', true ),
                            );                            
                        }
                        $invoice_items[] = array (
                            'name' => __( 'Entry cards', 'rentiq' ),
                            'description' => __( 'Deposit for entry cards', 'rentiq' ),
                            'item_price' => get_field( 'entry_card_price', 'option' ),
                            'price' => $entry_cards_total,
                        );
                        $invoice_items[] = array (
                            'name' => __( 'Rent', 'rentiq' ),
                            'description' => __( 'Rent for the first month - apartment #', 'rentiq' ) . $apartment_number,
                            'price' => $apartment_price,
                        );

                        if ( $reservation_type == 'both' ) {

                            $invoice_items[] = array (
                                'name' => __( 'Rent', 'rentiq' ),
                                'description' => __( 'Rent for the first month - parking #', 'rentiq' ) . $parking_number,
                                'price' => $parking_price,
                            );
                            update_post_meta( $document_id, 'invoice_parking_rent', $parking_price );
                        }


                        update_post_meta( $reservation_id, 'deposit_amount', $apartment_price * $deposit_count );

                        update_post_meta( $document_id, 'invoice_deposit', $apartment_price * $deposit_count );
                        update_post_meta( $document_id, 'invoice_rent', $rent_total );
                        update_post_meta( $document_id, 'invoice_apartment_rent', $apartment_price );

                    break;
                    case 'invoice_rental':

                        $template_type = 'invoice';
                        $document_code = 'IN';
                        $invoice_name = __( 'rental invoice', 'rentiq' );

                        // rent //

                        if ( $reservation_type == 'parking' && $tenant_type == 'tenant' ) {

                            $invoice_items[] = array (
                                'name' => __( 'Rent', 'rentiq' ),
                                'description' => __( 'Monthly rent for parking #', 'rentiq' ) . $parking_number,
                                'price' => $parking_price,
                            );
                            update_post_meta( $document_id, 'invoice_rent', $rent_total );
                            update_post_meta( $document_id, 'invoice_parking_rent', $parking_price );
                        
                        } else {

                            if ( $tenant_type == 'tenant' ) {

                                $invoice_items[] = array (
                                    'name' => __( 'Rent', 'rentiq' ),
                                    'description' => __( 'Monthly rent for apartment #', 'rentiq' ) . $apartment_number,
                                    'price' => $apartment_price,
                                );
                                update_post_meta( $document_id, 'invoice_rent', $rent_total );
                                update_post_meta( $document_id, 'invoice_apartment_rent', $apartment_price );

                            }

                            // services //

                            $housekeeping = get_post_meta( $reservation_id, 'reservation_housekeeping', true );
                            $tv = get_post_meta( $reservation_id, 'reservation_tv', true );
                            $internet_option = get_post_meta( $reservation_id, 'reservation_internet', true );

                            if ($housekeeping == 'yes') {
                                if( have_rows( 'apartment_types' , 'option' ) ) {
                                    while ( have_rows( 'apartment_types' , 'option' ) ) {
                                        the_row();
                                        if ( get_sub_field('apartment_type_slug') != $apartment_type ) {
                                            continue;
                                        }
                                        $housekeeping_price = get_sub_field('apartment_type_housekeeping_fees');
                                    }
                                }
                                $invoice_items[] = array (
                                    'name' => __( 'Housekeeping', 'rentiq' ),
                                    'description' => __( 'Monthly fee for housekeeping service - #', 'rentiq' ) . $apartment_number,
                                    'price' => $housekeeping_price,
                                );
                                update_post_meta( $document_id, 'invoice_housekeeping', $housekeeping_price );
                            }

                            if ($tv == 'yes') {
                                $tv_price = get_field('tv_monthly_price', 'option');
                                $invoice_items[] = array (
                                    'name' => __( 'TV', 'rentiq' ),
                                    'description' => __( 'Monthly fee for TV - #', 'rentiq' ) . $apartment_number,
                                    'price' => $tv_price,
                                );
                                update_post_meta( $document_id, 'invoice_tv', $tv_price );
                            }

                            if ( $internet_option == 'custom-net' ) {
                                $internet_price = get_post_meta( $reservation_id, 'reservation_internet_custom_price', true );
                                $internet_name = __( 'Custom', 'rentiq' );
                            } else {
                                if( have_rows( 'internet_options' , 'option' ) ) {
                                    while ( have_rows( 'internet_options' , 'option' ) ) {
                                        the_row();
                                        if ( get_sub_field('internet_option_slug') != $internet_option ) {
                                            continue;
                                        }
                                        $internet_price = get_sub_field('internet_option_price');
                                        $internet_name = get_sub_field('internet_option_name');
                                    }
                                }
                            }
                            $invoice_items[] = array (
                                'name' => __( 'Internet', 'rentiq' ),
                                'description' => __( 'Monthly fee for internet - ', 'rentiq' ) . $internet_name . ' - #'  . $apartment_number,
                                'price' => $internet_price,
                            );
                            update_post_meta( $document_id, 'invoice_internet', $internet_price );

                            // monthly expenses //

                            $last_invoice = Rentiq_class::get_last_reservation_invoice( $reservation_id, 1 );
                            $monthly_expenses_total = 0;
                            if( have_rows( 'reservation-expenses', $reservation_id ) ) {
                                while( have_rows( 'reservation-expenses', $reservation_id ) ) {

                                    the_row();
                                    $expense_date = get_sub_field('reservation-expense_date');

                                    if ( Rentiq_class::check_if_date_in_range( $expense_date, $last_invoice['date'], $this_invoice_date ) ) {
                                        $expense_price = floatval( get_sub_field( 'reservation-expense_price' ) );
                                        $invoice_items[] = array (
                                            'name' => __( 'Expense', 'rentiq' ),
                                            'description' => get_sub_field( 'reservation-expense_name' ),
                                            'price' => $expense_price,
                                        );
                                        $monthly_expenses_total += floatval($expense_price);
                                    }
                            
                                }
                            }
                            update_post_meta( $document_id, 'invoice_expenses', $monthly_expenses_total );

                            // monthly energies //

                            $invoice_water_total = 0;
                            $invoice_electricity_total = 0;
                            if( have_rows( 'reservation-energies', $reservation_id ) ) {
                                while( have_rows( 'reservation-energies', $reservation_id ) ) {

                                    the_row();
                                    $energies_date = get_sub_field('reservation-monthly_energies_date');
                                    //error_log( $last_invoice['id'] . ' / ' .$last_invoice['date'] . ' > ' . $energies_date . ' < ' . $this_invoice_date );

                                    if ( Rentiq_class::check_if_date_in_range( $energies_date, $last_invoice['date'], $this_invoice_date ) ) {

                                        $water_value = floatval( get_sub_field('reservation-monthly_water_value') );
                                        $electricity_value = floatval( get_sub_field('reservation-monthly_electricity_value') );

                                        $last_water_value = floatval( get_post_meta( $last_invoice['id'], 'reservation_water_value', true ) );
                                        $last_electricity_value = floatval( get_post_meta( $last_invoice['id'], 'reservation_electricity_value', true ) );

                                        $water_value_used = $water_value - $last_water_value;
                                        $electricity_value_used = $electricity_value - $last_electricity_value;
                                        
                                        $invoice_items[] = array (
                                            'name' => __( 'Water', 'rentiq' ),
                                            'description' => __( 'Water used (m3): ', 'rentiq' ) . $water_value_used . ' - #'  . $apartment_number,
                                            'price' => floatval( $water_price * $water_value_used ),
                                        );
                                        $invoice_water_total += floatval( $water_price * $water_value_used );

                                        $invoice_items[] = array (
                                            'name' => __( 'Electricity', 'rentiq' ),
                                            'description' => __( 'Electricity used (kW): ', 'rentiq' ) . $electricity_value_used . ' - #'  . $apartment_number,
                                            'price' => floatval( $electricity_price * $electricity_value_used ),
                                        );
                                        $invoice_electricity_total += floatval( $electricity_price * $electricity_value_used );

                                        update_post_meta( $document_id, 'reservation_water_value', $water_value );
                                        update_post_meta( $document_id, 'reservation_electricity_value', $electricity_value );
                                    }
                            
                                }
                            }
                            update_post_meta( $document_id, 'invoice_water_total', $invoice_water_total );
                            update_post_meta( $document_id, 'invoice_electricity_total', $invoice_electricity_total );

                        }
                        
                    break;
                    case 'receipt':

                        $template_type = 'receipt';
                        $document_code = 'IR';

                        $invoice_number = get_post_meta( $document_id, 'document_number', true );
                        $invoice_type = get_post_type( $document_id );
                        $invoice_type_name = get_post_type_object( $invoice_type );
                        $invoice_name = $invoice_type_name->labels->singular_name . __( ' receipt', 'rentiq' );
                        $payment_invoice_total = get_post_meta( $document_id, 'invoice_total', true );
	                    $invoice_balance = Rentiq_class::is_invoice_paid( $document_id, 'text' );

                        // load all payments //

                        $payments = get_posts( array(
                            'post_type'   => 'receipts',
                            'numberposts' => -1,
                            'post_status' => 'any',  
                            'orderby'     => 'date',
                            'order'       => 'DESC',   
                            'meta_query' => array(
                                array(
                                   'key'       => 'payment_invoice',
                                   'value'     => $document_id,
                                   'compare'   => '='
                                )
                            )
                        ));

                        foreach ( $payments as $payment ) {

                            $payment_id = $payment->ID;
                            $invoice_items[] = array (
                                'name' => get_the_terms( $payment_id, 'payment_types' )[0]->name,
                                'description' => get_post_meta( $payment_id, 'payment_date', true ),
                                'price' => get_post_meta( $payment_id, 'payment_amount', true ),
                            );
                        }
                        
                    break;
                    case 'invoice_deporeturn':

                        $template_type = 'invoice';
                        $document_code = 'DR';
                        $invoice_name = __( 'termination invoice', 'rentiq' );

                        // deposit return //

                        $deposit_amount = floatval( get_post_meta( $reservation_id, 'deposit_amount', true ) );
                        $leaving_early = get_post_meta( $reservation_id, 'termination_leaving_early', true );
                        if ( $tenant_type == 'tenant' ) {
                            if ( is_array( $leaving_early ) && in_array( 'yes', $leaving_early )) {
                                $deposit_returning_amount = $deposit_amount;
                                $invoice_items[] = array (
                                    'name' => __( 'Deposit', 'rentiq' ),
                                    'description' => __( 'Deposit will not be returned due to early rental ending - #', 'rentiq' ) . $apartment_number,
                                    'price' => '-',
                                );
                            } else {
                                $deposit_returning_amount = 0;
                                $invoice_items[] = array (
                                    'name' => __( 'Deposit', 'rentiq' ),
                                    'description' => __( 'Returning of deposit for apartment #', 'rentiq' ) . $apartment_number,
                                    'price' => -$deposit_amount,
                                );
                                update_post_meta( $document_id, 'invoice_deposit_returned', $deposit_amount );
                            }
                        }

                        // utility deposit return //

                        if ( $tenant_type == 'tenant' ) {

                            $utility_deposit_amount = get_post_meta( $reservation_id, 'reservation_deposit_utility', true );
                            $deposit_returning_amount = 0;
                            $invoice_items[] = array (
                                'name' => __( 'Utility deposit', 'rentiq' ),
                                'description' => __( 'Returning of utility deposit for apartment #', 'rentiq' ) . $apartment_number,
                                'price' => -floatval($utility_deposit_amount),
                            );
                            update_post_meta( $document_id, 'invoice_utility_deposit_returned', $utility_deposit_amount );
                        }

                        // entry cards //
                        
                        $invoice_items[] = array (
                            'name' => __( 'Entry cards', 'rentiq' ),
                            'description' => __( 'Returning of deposit for entry cards', 'rentiq' ),
                            'item_price' => get_field( 'entry_card_price', 'option' ),
                            'price' => -$entry_cards_total,
                        );

                        // monthly expenses //
                        
                        $last_invoice = Rentiq_class::get_last_reservation_invoice( $reservation_id, 0 );
                        $monthly_expenses_total = 0;
                        if( have_rows( 'reservation-expenses', $reservation_id ) ) {
                            while( have_rows( 'reservation-expenses', $reservation_id ) ) {

                                the_row();
                                $expense_date = get_sub_field('reservation-expense_date');

                                if ( Rentiq_class::check_if_date_in_range( $expense_date, $last_invoice['date'], $this_invoice_date ) ) {
                                    $expense_price = floatval( get_sub_field( 'reservation-expense_price' ) );
                                    $invoice_items[] = array (
                                        'name' => __( 'Expense', 'rentiq' ),
                                        'description' => get_sub_field( 'reservation-expense_name' ),
                                        'price' => $expense_price,
                                    );
                                    $monthly_expenses_total += floatval($expense_price);
                                }
                            }
                            update_post_meta( $document_id, 'invoice_expenses', $monthly_expenses_total );
                        }

                        // additional expenses //

                        $additional_expenses_total = 0;
                        if( have_rows( 'termination-expenses', $reservation_id ) ) {
                            while( have_rows( 'termination-expenses', $reservation_id ) ) {

                                the_row();
                                $additional_expense_price = floatval( get_sub_field( 'termination-expense_price' ) );
                                $invoice_items[] = array (
                                    'name' => __( 'Expense', 'rentiq' ),
                                    'description' => get_sub_field( 'termination-expense_name' ),
                                    'price' => $additional_expense_price,
                                );
                                $additional_expenses_total += floatval($additional_expense_price);
                        
                            }
                            update_post_meta( $document_id, 'invoice_additional_expenses', $additional_expenses_total );
                        }

                        // last energies //

                        $invoice_water_total = 0;
                        $invoice_electricity_total = 0;

                        $water_value = floatval( get_post_meta( $reservation_id, 'termination_water_value', true ) );
                        $electricity_value = floatval( get_post_meta( $reservation_id, 'termination_electricity_value', true ) );

                        $last_water_value = floatval( get_post_meta( $last_invoice['id'], 'reservation_water_value', true ) );
                        $last_electricity_value = floatval( get_post_meta( $last_invoice['id'], 'reservation_electricity_value', true ) );

                        $water_value_used = $water_value - $last_water_value;
                        $electricity_value_used = $electricity_value - $last_electricity_value;
                        
                        $invoice_items[] = array (
                            'name' => __( 'Water', 'rentiq' ),
                            'description' => __( 'Water used (m3): ', 'rentiq' ) . $water_value_used . ' - #'  . $apartment_number,
                            'price' => floatval( $water_price * $water_value_used ),
                        );
                        $invoice_water_total += floatval( $water_price * $water_value_used );

                        $invoice_items[] = array (
                            'name' => __( 'Electricity', 'rentiq' ),
                            'description' => __( 'Electricity used (kW): ', 'rentiq' ) . $electricity_value_used . ' - #'  . $apartment_number,
                            'price' => floatval( $electricity_price * $electricity_value_used ),
                        );
                        $invoice_electricity_total += floatval( $electricity_price * $electricity_value_used );

                        update_post_meta( $document_id, 'invoice_water_total', $invoice_water_total );
                        update_post_meta( $document_id, 'invoice_electricity_total', $invoice_electricity_total );

                        // services //

                        $housekeeping = get_post_meta( $reservation_id, 'reservation_housekeeping', true );
                        $tv = get_post_meta( $reservation_id, 'reservation_tv', true );
                        $internet_option = get_post_meta( $reservation_id, 'reservation_internet', true );

                        if ($housekeeping == 'yes') {
                            if( have_rows( 'apartment_types' , 'option' ) ) {
                                while ( have_rows( 'apartment_types' , 'option' ) ) {
                                    the_row();
                                    if ( get_sub_field('apartment_type_slug') != $apartment_type ) {
                                        continue;
                                    }
                                    $housekeeping_price = get_sub_field('apartment_type_housekeeping_fees');
                                }
                            }
                            $invoice_items[] = array (
                                'name' => __( 'Housekeeping', 'rentiq' ),
                                'description' => __( 'Monthly fee for housekeeping service - #', 'rentiq' ) . $apartment_number,
                                'price' => $housekeeping_price,
                            );
                            update_post_meta( $document_id, 'invoice_housekeeping', $housekeeping_price );
                        }

                        if ($tv == 'yes') {
                            $tv_price = get_field('tv_monthly_price', 'option');
                            $invoice_items[] = array (
                                'name' => __( 'TV', 'rentiq' ),
                                'description' => __( 'Monthly fee for TV - #', 'rentiq' ) . $apartment_number,
                                'price' => $tv_price,
                            );
                            update_post_meta( $document_id, 'invoice_tv', $tv_price );
                        }

                        if ( $internet_option == 'custom-net' ) {
                            $internet_price = get_post_meta( $reservation_id, 'reservation_internet_custom_price', true );
                            $internet_name = __( 'Custom', 'rentiq' );
                        } else {
                            if( have_rows( 'internet_options' , 'option' ) ) {
                                while ( have_rows( 'internet_options' , 'option' ) ) {
                                    the_row();
                                    if ( get_sub_field('internet_option_slug') != $internet_option ) {
                                        continue;
                                    }
                                    $internet_price = get_sub_field('internet_option_price');
                                    $internet_name = get_sub_field('internet_option_name');
                                }
                            }
                        }
                        $invoice_items[] = array (
                            'name' => __( 'Internet', 'rentiq' ),
                            'description' => __( 'Monthly fee for internet - ', 'rentiq' ) . $internet_name . ' - #'  . $apartment_number,
                            'price' => $internet_price,
                        );
                        update_post_meta( $document_id, 'invoice_internet', $internet_price );


                        // expenses above deposit //

                        $deposit_returning_total = 0;
                        foreach ( $invoice_items as $value ) {
                            if ( is_numeric( $value['price'] ) ) {
                                $deposit_returning_total += floatval($value['price']);
                            }
                        }

                        $expenses_above = floatval( $deposit_returning_total - $deposit_returning_amount );
                        if ( $expenses_above > 0 ) {
                            /*
                            $invoice_items[] = array (
                                'name' => __( '', 'rentiq' ),
                                'description' => __( 'Expenses above deposit amount will be payed by the owner: ', 'rentiq' ) . $expenses_above . '$',
                                'price' => '',
                            );
                            */
                            update_post_meta( $document_id, 'expenses_above_deposit', $expenses_above );
                        }

                    break;
                    case 'invoice_settlement':

                        $template_type = 'invoice';
                        $document_code = 'IS';
                        $invoice_name = __( 'settlement invoice', 'rentiq' );

                        $settlement_items = get_field('settlement-expenses', $reservation_id );
                        if( $settlement_items ) {
                            foreach( $settlement_items as $settlement_item ) {
                                $invoice_items[] = array (
                                    'name' => $settlement_item['settlement-expense_name'],
                                    'description' => $settlement_item['settlement-expense_desc'],
                                    'price' => $settlement_item['settlement-expense_price'],
                                );
                            }
                        }                       
                        
                    break;
                    case 'invoice_agent':

                        $template_type = 'invoice';
                        $invoice_name = __( 'agent invoice', 'rentiq' );
                        $document_code = 'IA';

                        $agent = get_post_meta( $reservation_id, 'reservation_agent', true );
                        $tenant_name = '';
                        $tenant_country = '';
                        $tenant_birthdate = '';
                        $tenant_email = '';
                        $tenant_phone = '';

                        if( have_rows( 'agents', 'option' ) ) {
                            while( have_rows( 'agents', 'option' ) ) {

                                the_row();
                                $agent_id = get_sub_field('agent_id');

                                if ( $agent_id == $agent ) {
                                    $tenant_name = get_sub_field('agent_name');
                                    $tenant_email = get_sub_field('agent_email');
                                    $tenant_phone = get_sub_field('agent_phone');
                                }
                        
                            }
                        }

                        $contract_type_id = get_post_meta( $reservation_id, 'reservation_contract_type', true );
                        $contract_length = get_post_meta( $reservation_id, 'reservation_contract_length', true );
                        $agent_commission = get_post_meta( $contract_type_id, 'contract_commision_agent', true );
                        if ($contract_length == '6') {
                            $agent_commission = intval( $agent_commission / 2 );
                        }
                        $commission_price = floatval( ( $apartment_price / 100 ) * $agent_commission );
                        $invoice_items[] = array (
                            'name' => __( 'Commission', 'rentiq' ),
                            'description' => __( 'Agent commission for the rental of apartment #', 'rentiq' ) . $apartment_number .  ' - ' . $agent_commission . '%',
                            'price' => $commission_price,
                        );
                        update_post_meta( $document_id, 'invoice_agent_commission', $commission_price );
                        
                    break;
                    case 'invoice_ownerempty':

                        $template_type = 'invoice';
                        $document_code = 'IE';
                        $invoice_name = __( 'empty period invoice', 'rentiq' );

                        $last_rental_reservation_id = Rentiq_class::get_last_apartment_reservation( $apartment_id );

                        if ( $last_rental_reservation_id ) {

                            $last_rental_date = get_post_meta( $last_rental_reservation_id, 'termination_date', true );

                            // empty period expenses //

                            $empty_expenses_total = 0;
                            if( have_rows( 'apartment_expenses', 'apartment_'.$apartment_id ) ) {
                                while( have_rows( 'apartment_expenses', 'apartment_'.$apartment_id ) ) {

                                    the_row();
                                    $expense_date = get_sub_field('apartment_expense_date');
                                    if ( Rentiq_class::check_if_date_in_range( $expense_date, $last_rental_date, $this_invoice_date ) ) {

                                        $empty_expense_price = floatval( get_sub_field( 'apartment_expense_price' ) );
                                        $invoice_items[] = array (
                                            'name' => __( 'Expense', 'rentiq' ),
                                            'description' => get_sub_field( 'apartment_expense_name' ),
                                            'price' => $empty_expense_price,
                                        );
                                        $empty_expenses_total += floatval($empty_expense_price);

                                    }
                                }
                                update_post_meta( $document_id, 'invoice_empty_expenses', $empty_expenses_total );
                            }

                            // empty period energies //

                            $invoice_water_total = 0;
                            $invoice_electricity_total = 0;

                            $water_value = floatval( get_post_meta( $reservation_id, 'reservation_water_value', true ) );
                            $electricity_value = floatval( get_post_meta( $reservation_id, 'reservation_electricity_value', true ) );

                            $last_water_value = floatval( get_post_meta( $last_rental_reservation_id, 'termination_water_value', true ) );
                            $last_electricity_value = floatval( get_post_meta( $last_rental_reservation_id, 'termination_electricity_value', true ) );

                            $water_value_used = $water_value - $last_water_value;
                            $electricity_value_used = $electricity_value - $last_electricity_value;
                            
                            $invoice_items[] = array (
                                'name' => __( 'Water', 'rentiq' ),
                                'description' => __( 'Water used (m3): ', 'rentiq' ) . $water_value_used . ' - #'  . $apartment_number,
                                'price' => floatval( $water_price * $water_value_used ),
                            );
                            $invoice_water_total += floatval( $water_price * $water_value_used );
                            $invoice_items[] = array (
                                'name' => __( 'Electricity', 'rentiq' ),
                                'description' => __( 'Electricity used (kW): ', 'rentiq' ) . $electricity_value_used . ' - #'  . $apartment_number,
                                'price' => floatval( $electricity_price * $electricity_value_used ),
                            );
                            $invoice_electricity_total += floatval( $electricity_price * $electricity_value_used );

                            update_post_meta( $document_id, 'invoice_water_total', $invoice_water_total );
                            update_post_meta( $document_id, 'invoice_electricity_total', $invoice_electricity_total );

                        } else {

                            $invoice_items[] = array (
                                'name' => __( 'No data', 'rentiq' ),
                                'description' => __( 'There was no preceding reservation = no empty period', 'rentiq' ),
                                'price' => 0,
                            );

                        }
                        
                    break;

                }

            // prices //

                $invoice_total = 0;
                $currency = ' $';
                foreach ( $invoice_items as $value ) {
                    if ( is_numeric( $value['price'] ) ) {
                        $invoice_total += floatval($value['price']);
                    }
                }
                $add_tax = false;
                $invoice_tax = floatval( ( $invoice_total / 100 ) * get_field('rental_tax', 'option') );
                if ( $add_tax == true ) {
                    $invoice_grand = number_format( floatval( $invoice_total + $invoice_tax ), 2, ".","" );
                } else {
                    $invoice_grand = number_format( floatval( $invoice_total ), 2, ".","" );
                }

                $invoice_date = Rentiq_class::rentiq_format_date( $this_invoice_date );
                $invoice_date_due = Rentiq_class::rentiq_format_date( $this_invoice_date, '+2 days' );

                if ( $document_type !== 'receipt' ) {
                    update_post_meta( $document_id, 'invoice_total', $invoice_grand );
                }

            // document number IN-106/411-0421 //

                $document_date_simple = get_the_date( 'my', $document_id );
                $document_number = $document_code . '-' . $reservation_id . '/' . $apartment_number . '-' . $document_date_simple;
                update_post_meta( $document_id, 'document_number', $document_number );

            // setup MPDF //

            require_once RENTIQ_PATH . '/includes/plugins/mpdf8/vendor/autoload.php';

                $mpdf_config_class = new \Mpdf\Config\ConfigVariables();
                $defaultConfig = $mpdf_config_class->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $mpdf_font_config_class = new \Mpdf\Config\FontVariables();
                $defaultFontConfig = $mpdf_font_config_class->getDefaults();

                $fontData = $defaultFontConfig['fontdata'];
                $font_dir = RENTIQ_PATH . '/assets/fonts';
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->autoLangToFont = true;

            // create document directory //

                $wp_upload_dir = wp_upload_dir();
                $path_to_document = "documents/{$reservation_id}/{$document_type}";
                $dir = $wp_upload_dir['basedir'] . '/' . $path_to_document;

                if( is_dir( $dir ) === false ) {
                    wp_mkdir_p( $dir );
                }

            // include the template //

                require RENTIQ_PATH . 'includes/templates/'. $template_type .'/'. $template_type .'.php';            

            //// create the pdf ////
                
                $stylesheet = file_get_contents( RENTIQ_PATH . 'includes/templates/'. $template_type .'/style.css' );

                $mpdf->WriteHTML( $html, 0 );
                $mpdf->Output( $dir.'/'.$sanitized_title.'.pdf', 'F' );

                unset($html);

                //$html .= $stylesheet;
                //file_put_contents($dir . '/' . $sanitized_title.'.html', $html);

                $document_url = $path_to_document . '/' . $sanitized_title.'.pdf';
            
            return $document_url;

        }


        //// create new owner pdf ////

        public static function create_new_owner_pdf( $document_id, $document_type, $owner_id ) {

            // setup document data //

            $document_title = get_the_title( $document_id );
            $sanitized_title = preg_replace('/(.)\1{3,}/', '', sanitize_title( $document_title ) );
            $invoice_items = array();
            $company_logo = get_field( 'company_logo', 'option' );
            $this_invoice_date = get_the_date( 'Ymd', $document_id );

            // info //

            $tenant_name = get_term( $owner_id )->name;
            $tenant_country = get_field( 'owner_country', 'owner_' . $owner_id );
            $tenant_email = get_term_meta( $owner_id, 'owner_email', true );
            $tenant_phone = get_field( 'owner_phone', 'owner_' . $owner_id );

            $company_name = get_field( 'company_name', 'option' );
            $company_address = get_field( 'company_address', 'option' );
            $company_phone = get_field( 'company_phone', 'option' );
            $company_email = get_field( 'company_email', 'option' );
            $company_account = get_field( 'company_bank_account', 'option' );

            // setup document type data //
            
                switch( $document_type ) {

                    case 'invoice_owner':

                        $template_type = 'invoice';
                        $document_code = 'IO';
                        $invoice_name = __( 'owner fees invoice', 'rentiq' );

                        $document_date_range = get_post_meta( $document_id, 'invoice_date_range', true );
                        $docdate_from = strtotime( explode( '===', $document_date_range )[0] );
                        $docdate_till = strtotime( explode( '===', $document_date_range )[1] );
                        $year_from = date('Y', $docdate_from);
                        $year_till = date('Y', $docdate_till);
                        $month_from = date('m', $docdate_from);
                        $month_till = date('m', $docdate_till);
                        $month_count = (($year_till - $year_from) * 12) + ($month_till - $month_from);

                        $owner_apartments = Rentiq_class::get_owner_estates( $owner_id, 'apartment' );

                        $sinkink_total = 0;
                        $management_total = 0;

                        if ( $owner_apartments ) {
                            foreach ( $owner_apartments as $owner_apartment ) {

                                $owner_apartment_id = $owner_apartment->term_id;
                                $owner_apartment_type = get_term_meta( $owner_apartment_id, 'apartment_type', true );
                                $owner_apartment_number = Rentiq_class::get_rental_number_by_id( $owner_apartment_id, 'apartment' );

                                if( have_rows('apartment_types', 'option') ) {
            
                                    while( have_rows('apartment_types', 'option') ) {
                            
                                        the_row();
                                        $apartment_type_slug = get_sub_field('apartment_type_slug');

                                        if ( $apartment_type_slug == $owner_apartment_type ) {
                                            $sinkink_fund_fee = $month_count * get_sub_field('apartment_type_sinkinkfund_fees');
                                            $management_fee = $month_count * get_sub_field('apartment_type_management_fees');
                                        }
                                        
                                    }
                                }

                                $invoice_items[] = array (
                                    'name' => __( 'Sinking fund', 'rentiq' ),
                                    'description' => __( 'Fee for apartment #', 'rentiq' ) . $owner_apartment_number . ' - ' . date( 'm/y', strtotime( "+1 month", $docdate_from ) ). ' till ' . date( 'm/y', $docdate_till ),
                                    'price' => floatval( $sinkink_fund_fee ),
                                );
                                $sinkink_total += floatval($sinkink_fund_fee);
                                $invoice_items[] = array (
                                    'name' => __( 'Management fees', 'rentiq' ),
                                    'description' => __( 'Fee for apartment #', 'rentiq' ) . $owner_apartment_number . ' - ' . date( 'm/y', strtotime( "+1 month", $docdate_from ) ). ' till ' . date( 'm/y', $docdate_till ),
                                    'price' => floatval( $management_fee ),
                                );
                                $management_total += floatval($management_fee);
                            }
                        }
                        update_post_meta( $document_id, 'management_total', floatval($management_total) );
                        update_post_meta( $document_id, 'sinkink_total', floatval($sinkink_total) );

                    break;
                    case 'invoice_owner_payout':
            
                        $template_type = 'invoice';
                        $document_code = 'IP';
                        $invoice_name = __( 'owner payout invoice', 'rentiq' );

                        // get last payout date //

                        $last_payout_invoice = get_posts( array(
                            'post_type'   => 'invoice_owner_payout',
                            'numberposts' => -1,
                            'post_status' => 'any',  
                            'orderby'     => 'date',
                            'order'       => 'DESC',   
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'owner',
                                    'field' => 'term_id',
                                    'terms' => array( $owner_id ),
                                    'operator' => 'IN',
                                )
                            ),
                        ));
                        
                        if ( count($last_payout_invoice) > 1 ) {
                            $last_payout = get_the_date( 'Ymd', $last_payout_invoice[1]->ID );
                        } else {
                            $last_payout = date("Ymd", strtotime("-30 years"));
                        }

                        //error_log( count( $last_payout_invoice ) . ' -- date: ' . $last_payout );

                        // get tenant invoices since last //

                        $tenant_invoices = get_posts( array(
                            'post_type'   => array( 'invoice_rental', 'invoice_deposit', 'invoice_deporeturn', 'invoice_ownerempty', 'invoice_settlement' ),
                            'numberposts' => -1,
                            'post_status' => 'any',
                            'orderby' => 'date',
                            'order' => 'ASC',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'owner',
                                    'field' => 'term_id',
                                    'terms' => array( $owner_id ),
                                    'operator' => 'IN',
                                )
                            ),
                            'date_query' => array(
                                'after' => date('Ymd', strtotime( $last_payout ) ) 
                            )
                        ));

                        //error_log( count( $tenant_invoices ));

                        $rent_payout_total = 0;
                        $deposit_substracted_total = 0;
                        $rental_tax_total = 0;
                        $rental_service_total = 0;
                        $invoice_count = 0;

                        if ( $tenant_invoices ) {
                            foreach( $tenant_invoices as $invoice_made ) {

                                $invoice_made_id = $invoice_made->ID;
                                $invoice_made_date = get_the_date( "d/m/Y", $invoice_made_id );
                                $invoice_made_date_simple = get_the_date( 'm/Y', $invoice_made_id );
                                $invoice_number = get_post_meta( $invoice_made_id, 'document_number', true );
                                $invoice_made_type = get_post_type( $invoice_made_id );

                                //error_log( 'invoice' . $invoice_made_id . '-' . $invoice_made_date . '- last payout ' . $last_payout. '- this date ' . $this_invoice_date);

                                if ( Rentiq_class::check_if_date_in_range( $invoice_made_date, $last_payout, $this_invoice_date ) ) {

                                    $invoice_made_balance = Rentiq_class::is_invoice_paid( $invoice_made_id, $output_type = 'number' );
                                    if ( $invoice_made_balance == 1 || $invoice_made_balance < 1 ) {

                                        $invoice_count++;
                                        $invoice_reservation_id = get_post_meta( $invoice_made_id, 'reservation_id', true );
                                        $reservation_tenant = get_post_meta( $invoice_reservation_id, 'reservation_tenant_type', true );

                                        // parking / apartment //
                                        $reservation_type = get_post_meta( $invoice_reservation_id, 'reservation_type', true );
                                        $object_id = get_post_meta( $invoice_reservation_id, 'reservation_'.$reservation_type, true );
                                        $object_number = Rentiq_class::get_rental_number_by_id( $object_id, $reservation_type );

                                        // deposit or monthly //
                                        if ( $invoice_made_type == 'invoice_rental' || $invoice_made_type == 'invoice_deposit'  ) {
                                                
                                            //// == APARTMENT == ////

                                            if ( $reservation_type == 'apartment' && Rentiq_class::does_he_own_it( $owner_id, $object_id ) === true ) {

                                                // if tenant is not the owner //
                                                if ( $reservation_tenant == 'tenant' ) {
                                                    
                                                    // heading //
                                                    $invoice_items[] = array (
                                                        'name' => '<b>' . $invoice_made_date_simple . '</b>',
                                                        'description' => __( '<b>Monthly summary for the rental of apartment #', 'rentiq' ) . $object_number . '</b>',
                                                        'price' => '<b>' . $invoice_number . '</b>',
                                                    );
                                                    
                                                    // rent income //
                                                    $rent_payed = floatval( get_post_meta( $invoice_made_id, 'invoice_apartment_rent', true ) );
                                                    $invoice_items[] = array (
                                                        'name' => __( 'Rent', 'rentiq' ),
                                                        'description' => __( 'Incomes from rent of apartment #', 'rentiq' ) . $object_number,
                                                        'price' => $rent_payed,
                                                    );
                                                    $rent_payout_total += floatval($rent_payed);

                                                    // deposit //
                                                    if ( $invoice_made_type == 'invoice_deposit' ) {

                                                        $rent_length = intval( get_post_meta( $invoice_reservation_id, 'reservation_contract_length', true ) );
                                                        $commission = floatval( get_post_meta( $invoice_reservation_id, 'reservation_apartment_price', true ) );   
                                                        if ( $rent_length < 12 ) {
                                                            $commission = 0.5 * $commission;
                                                        }

                                                        $invoice_items[] = array (
                                                            'name' => __( 'Commision', 'rentiq' ),
                                                            'description' => __( 'For the rental arrangement', 'rentiq' ),
                                                            'price' => -$commission,
                                                        );
                                                        $deposit_substracted_total += floatval( $commission );
                                                    }

                                                    // rental tax //
                                                    $rental_tax = floatval( get_field('rental_tax', 'option') );
                                                    $rental_tax_amount = floatval( ( $rent_payed / 100 ) * $rental_tax );
                                                    $invoice_items[] = array (
                                                        'name' => __( 'Rental tax', 'rentiq' ),
                                                        'description' => $rental_tax . __( '% government tax for the rental', 'rentiq' ),
                                                        'price' => -$rental_tax_amount,
                                                    );
                                                    $rental_tax_total += floatval($rental_tax_amount);

                                                    // rental service //
                                                    $rental_service = floatval( get_field('rental_service_price', 'option') );
                                                    $rental_service_amount = floatval( ( $rent_payed / 100 ) * $rental_service );
                                                    $invoice_items[] = array (
                                                        'name' => __( 'Rental service', 'rentiq' ),
                                                        'description' => $rental_service . __( '% rental service for the rental', 'rentiq' ),
                                                        'price' => -$rental_service_amount,
                                                    );
                                                    $rental_service_total += floatval($rental_service_amount);

                                                } else {

                                                    // if owner is living in his own //
                                                    $invoice_items[] = array (
                                                        'name' => '<b>' . __( 'No payout', 'rentiq' ) . '</b>',
                                                        'description' => '<b>' . __( 'Owner living in own apartment #', 'rentiq' ) . $object_number . '</b>',
                                                        'price' =>  '<b>' . $invoice_number . '</b>',
                                                    );

                                                }
                                            }

                                            //// == PARKING == ////

                                            //error_log( $reservation_type . ' -- ' . $object_id . ' / ' . $owner_id );

                                            if ( $reservation_type == 'parking' && Rentiq_class::does_he_own_it( $owner_id, $object_id ) === true ) {

                                                // if tenant is not the owner //
                                                $reservation_owner_id = Rentiq_class::get_reservation_owners( $reservation_id );

                                                if ( $reservation_tenant == 'tenant' ) {

                                                    // heading //
                                                    $invoice_items[] = array (
                                                        'name' => '<b>' . $invoice_made_date_simple . '</b>',
                                                        'description' => __( '<b>Monthly summary for the rental of parking #', 'rentiq' ) . $object_number . '</b>',
                                                        'price' => '<b>' . $invoice_number . '</b>',
                                                    );

                                                    // rent income //
                                                    $parking_rent_payed = floatval( get_post_meta( $invoice_made_id, 'invoice_parking_rent', true ) );
                                                    $invoice_items[] = array (
                                                        'name' => __( 'Rent', 'rentiq' ),
                                                        'description' => __( 'Incomes from rent of parking #', 'rentiq' ) . $object_number,
                                                        'price' => $parking_rent_payed,
                                                    );
                                                    $rent_payout_total += floatval($parking_rent_payed);

                                                    // rental service //
                                                    $rental_service = floatval( get_field('rental_service_price', 'option') );
                                                    $rental_service_amount = floatval( ( $parking_rent_payed / 100 ) * $rental_service );
                                                    $invoice_items[] = array (
                                                        'name' => __( 'Rental service', 'rentiq' ),
                                                        'description' => $rental_service . __( '% rental service for the rental', 'rentiq' ),
                                                        'price' => -$rental_service_amount,
                                                    );
                                                    $rental_service_total += floatval($rental_service_amount);

                                                } 
                                            }
                                            
                                        }

                                        // expenses above deposit //
                                        if ( $invoice_made_type == 'invoice_settlement' ) {

                                            //// == APARTMENT == ////
                                            if ( Rentiq_class::does_he_own_it( $owner_id, $object_id ) === true ) {

                                                // heading //
                                                $invoice_items[] = array (
                                                    'name' => '<b>' . $invoice_made_date_simple . '</b>',
                                                    'description' => __( '<b>Termination settlement for '. $reservation_type .' #', 'rentiq' ) . $object_number . '</b>',
                                                    'price' => '<b>' . $invoice_number . '</b>',
                                                ); 

                                                if( have_rows( 'settlement-expenses', $invoice_reservation_id ) ) {
                                                    while( have_rows( 'settlement-expenses', $invoice_reservation_id ) ) {
                        
                                                        the_row();
                        
                                                        $expense_price = floatval( get_sub_field( 'settlement-expense_price' ) );
                                                        //$expense_price_inverted = -1 * abs($expense_price);
                                                        $invoice_items[] = array (
                                                            'name' => get_sub_field( 'settlement-expense_name' ),
                                                            'description' => get_sub_field( 'settlement-expense_desc' ),
                                                            'price' => $expense_price,
                                                        );
                                                    }
                                                }
                                            
                                            }
                                        }

                                        // empty period //
                                        if ( $invoice_made_type == 'invoice_ownerempty' ) {

                                            //// == APARTMENT == ////
                                            if ( Rentiq_class::does_he_own_it( $owner_id, $object_id ) === true ) {

                                                $empty_expenses = floatval( get_post_meta( $invoice_made_id, 'invoice_empty_expenses', true ) );                                        
                                                $empty_water = floatval( get_post_meta( $invoice_made_id, 'invoice_water_total', true ) );                                        
                                                $empty_electricity = floatval( get_post_meta( $invoice_made_id, 'invoice_electricity_total', true ) );                                        
                                                
                                                $invoice_items[] = array (
                                                    'name' => '<b>' . $invoice_made_date_simple . '</b>',
                                                    'description' => __( '<b>Empty period expenses for apartment #', 'rentiq' ) . $object_number . '</b>',
                                                    'price' => '<b>' . $invoice_number . '</b>',
                                                );  

                                                $invoice_items[] = array (
                                                    'name' => __( 'Water', 'rentiq' ),
                                                    'description' => __( 'Water used within the empty period', 'rentiq' ),
                                                    'price' => -$empty_water,
                                                );
                                                $invoice_items[] = array (
                                                    'name' => __( 'Electricity', 'rentiq' ),
                                                    'description' => __( 'Electricity used within the empty period', 'rentiq' ),
                                                    'price' => -$empty_electricity,
                                                );
                                                $invoice_items[] = array (
                                                    'name' => __( 'Expenses', 'rentiq' ),
                                                    'description' => __( 'Expenses made within the empty period', 'rentiq' ),
                                                    'price' => -$empty_expenses,
                                                );
                                            }
                                        }
                                    }

                                }
                            }
                        }

                        update_post_meta( $document_id, 'rent_payout_total', floatval($rent_payout_total) );
                        update_post_meta( $document_id, 'deposit_substracted_total', floatval($deposit_substracted_total) );
                        update_post_meta( $document_id, 'rental_tax_total', floatval($rental_tax_total) );
                        update_post_meta( $document_id, 'rental_service_total', floatval($rental_service_total) );
                        
                        if ( $invoice_count < 1 ) {

                            // if no new invoices made //
                            $invoice_items[] = array (
                                'name' => __( 'No payout', 'rentiq' ),
                                'description' => __( 'No new tenant invoices made', 'rentiq' ),
                                'price' => 0,
                            );
                        }
                        
                    break;
                    case 'receipt':

                        $template_type = 'receipt';
                        $document_code = 'IR';

                        $invoice_type = get_post_type( $document_id );
                        $invoice_type_name = get_post_type_object( $invoice_type );
                        $invoice_name = $invoice_type_name->labels->singular_name . __( ' receipt', 'rentiq' );
                        $payment_invoice_total = get_post_meta( $document_id, 'invoice_total', true );
	                    $invoice_balance = Rentiq_class::is_invoice_paid( $document_id, 'text' );
                        $invoice_number = get_post_meta( $document_id, 'document_number', true );

                        // load all payments //

                        $payments = get_posts( array(
                            'post_type'   => 'receipts',
                            'numberposts' => -1,
                            'post_status' => 'any',  
                            'orderby'     => 'date',
                            'order'       => 'DESC',   
                            'meta_query' => array(
                                array(
                                   'key'       => 'payment_invoice',
                                   'value'     => $document_id,
                                   'compare'   => '='
                                )
                            )
                        ));

                        foreach ( $payments as $payment ) {

                            $payment_id = $payment->ID;
                            $invoice_items[] = array (
                                'name' => get_the_terms( $payment_id, 'payment_types' )[0]->name,
                                'description' => get_post_meta( $payment_id, 'payment_date', true ),
                                'price' => get_post_meta( $payment_id, 'payment_amount', true ),
                            );
                        }
                        
                    break;

                }

            // prices //

                $invoice_total = 0;
                $currency = ' $';
                foreach ( $invoice_items as $value ) {
                    if ( is_numeric( $value['price'] ) ) {
                        $invoice_total += floatval($value['price']);
                    }
                }
                $add_tax = false;
                $invoice_tax = floatval( ( $invoice_total / 100 ) * get_field('rental_tax', 'option') );
                if ( $add_tax == true ) {
                    $invoice_grand = number_format( floatval( $invoice_total + $invoice_tax ), 2, ".","" );
                } else {
                    $invoice_grand = number_format( floatval( $invoice_total ), 2, ".","" );
                }

                $document_date = get_the_date( 'Ymd', $document_id );
                $document_date_simple = get_the_date( 'my', $document_id );
                $invoice_date = Rentiq_class::rentiq_format_date( $document_date );
                $invoice_date_due = Rentiq_class::rentiq_format_date( $document_date, '+2 days' );

                if ( $document_type !== 'receipt' ) {
                    update_post_meta( $document_id, 'invoice_total', $invoice_grand );
                }

            // document number IN-106/411-0421 //

                if ( $document_type == 'receipt' ) {
                    $document_number = $document_code . '-' . $invoice_number;
                } else {
                    $document_number = $document_code . '-' . $owner_id . '/' . $document_date_simple;
                    update_post_meta( $document_id, 'document_number', $document_number );
                }

            // setup MPDF //

            require_once RENTIQ_PATH . '/includes/plugins/mpdf8/vendor/autoload.php';

                $mpdf_config_class = new \Mpdf\Config\ConfigVariables();
                $defaultConfig = $mpdf_config_class->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $mpdf_font_config_class = new \Mpdf\Config\FontVariables();
                $defaultFontConfig = $mpdf_font_config_class->getDefaults();

                $fontData = $defaultFontConfig['fontdata'];
                $font_dir = RENTIQ_PATH . '/assets/fonts';
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->autoLangToFont = true;

            // create document directory //

                $wp_upload_dir = wp_upload_dir();
                $path_to_document = "documents/{$document_type}/{$owner_id}";
                $dir = $wp_upload_dir['basedir'] . '/' . $path_to_document;

                if( is_dir( $dir ) === false ) {
                    wp_mkdir_p( $dir );
                }

            // include the template //

                require RENTIQ_PATH . '/includes/templates/'. $template_type .'/'. $template_type .'.php';            

            //// create the pdf ////
                
                $stylesheet = file_get_contents( RENTIQ_PATH . '/includes/templates/'. $template_type .'/style.css' );

                $mpdf->WriteHTML( $html, 0 );
                $mpdf->Output( $dir.'/'.$sanitized_title.'.pdf', 'F' );

                unset($html);

                $document_url = $path_to_document . '/' . $sanitized_title.'.pdf';
            
            return $document_url;

        }

	}
}


/* FUCKING OWNER PAYOUT COUNTING 

$reservation_terminated = get_post_meta( $invoice_reservation_id, 'reservation_termination_date', true );
                           
 if ( Rentiq_class::check_if_date_is_later( $reservation_start, $last_payout ) ) {
    $last_payed = $last_payout;
} else {
    $last_payed = $reservation_start;
}
$months_since_last = intval( Rentiq_class::get_day_count_since( $last_payed ) / 30 );

// check if rent is finished //
if ( !empty( $reservation_terminated ) ) {
    $deposit_commision = ( $deposit_parts - $parts_already_payed ) * $deposit_part_cost;
    update_post_meta( $invoice_reservation_id, 'reservation_deposit_parts_payed', $deposit_parts );
} else {
    $deposit_commision = $months_since_last * $deposit_part_cost;
    update_post_meta( $invoice_reservation_id, 'reservation_deposit_parts_payed', intval( $parts_already_payed + $months_since_last ) );
}

*/

?>