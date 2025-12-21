<?php

/**
* Plugin Name: Rentiq
* Plugin URI: https://www.ticketa.cz/rentiq/
* Description: Rental management and invoicing plugin
* Version: 1
* Author: Ticketa
* Author URI: https://www.ticketa.cz/
* Developer: Ticketa
* Developer URI: https://www.ticketa.cz/
* Text Domain: rentiq
* Domain Path: /languages
*
* WC requires at least: 3.4
* WC tested up to: 5.0
*/

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//// defines ////

define('RENTIQ_URL', plugin_dir_url( __FILE__ ) );
define('RENTIQ_PATH', plugin_dir_path( __FILE__ ) );

/*
function rentiq_add_lz() {
    $apts = get_terms( array(
        'taxonomy' => 'parking',
        'hide_empty' => false
    ));
    foreach ($apts as $apartment) {
        $owner_id = get_term_meta( $apartment->term_id, 'parking_owner', true );
        if ($owner_id == false) {

            echo '+++++';
            update_term_meta($apartment->term_id, 'parking_owner', 560 );

        }
        echo $apartment->term_id . ' - ' . $owner_id . '<br/>';

    }
}
add_shortcode('rentiq_add_lz', 'rentiq_add_lz');
*/


//// initiate ////
	
// includes //

include_once ( RENTIQ_PATH . 'includes/plugins/acf/acf.php' );
include_once ( RENTIQ_PATH . 'includes/plugins/acf-extended/acf-extended.php' );
include_once ( RENTIQ_PATH . 'includes/plugins/acf-country/acf-country.php' );
include_once ( RENTIQ_PATH . 'includes/plugins/acf-separator/acf-separator.php' );
include_once ( RENTIQ_PATH . 'includes/plugins/acf-intl-tel-input/acf-intl-tel-input.php' );
include_once ( RENTIQ_PATH . 'includes/plugins/acf-tab-icons/acf-tab-icons.php' );
include_once ( RENTIQ_PATH . 'includes/plugins/acf-read-only/acf-read-only.php' );

include_once ( RENTIQ_PATH . 'includes/rentiq-class.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-ajax.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-shortcodes.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-export.php' );

include_once ( RENTIQ_PATH . 'includes/rentiq-cpt.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-tax.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-statuses.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-users.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-incomes.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-expenses.php' );

include_once ( RENTIQ_PATH . 'includes/admin-sorting.php' );
include_once ( RENTIQ_PATH . 'assets/admin-theme/admin-setup.php' );
include_once ( RENTIQ_PATH . 'includes/rentiq-dash.php' );

include_once ( RENTIQ_PATH . 'includes/fields-contract_type.php' );
include_once ( RENTIQ_PATH . 'includes/fields-expenses.php' );
include_once ( RENTIQ_PATH . 'includes/fields-apartment.php' );
include_once ( RENTIQ_PATH . 'includes/fields-parking.php' );
include_once ( RENTIQ_PATH . 'includes/fields-owner.php' );

include_once ( RENTIQ_PATH . 'includes/setting-page.php' );
include_once ( RENTIQ_PATH . 'includes/reservation-page.php' );
include_once ( RENTIQ_PATH . 'includes/apartment-map.php' );
include_once ( RENTIQ_PATH . 'includes/parking-map.php' );

//include_once ( RENTIQ_PATH . 'includes/submit-error.php' );
//include_once ( RENTIQ_PATH . 'includes/scheduled-actions.php' );
    
// load styles and scripts //
add_action( 'admin_enqueue_scripts', 'rentiq_styles' );
add_action( 'admin_enqueue_scripts', 'rentiq_reservation_enqueue_scripts' );

// load language //
add_action( 'plugins_loaded', 'rentiq_localisation' );

// setup menu //
add_action( 'admin_menu', 'rentiq_admin_menu' );
add_action( 'parent_file', 'rentiq_highlight_taxonomy_parent_menu' );


//// ACF setting ////

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return RENTIQ_URL . 'includes/plugins/acf/';
}

// (Optional) Hide the ACF admin menu item.
add_filter('acf/settings/show_admin', 'my_acf_settings_show_admin');
function my_acf_settings_show_admin( $show_admin ) {
    return false;
}


//// styles ////

function rentiq_styles($hook) {
		
    $lastmodtimecss = filemtime( RENTIQ_PATH . 'assets/css/rentiq.css' );
    wp_enqueue_style('rentiq_css', RENTIQ_URL . 'assets/css/rentiq.css', array(), $lastmodtimecss );

    wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
			
}


//// language ////
	
function rentiq_localisation() {
    
	$plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages';
	load_plugin_textdomain( 'rentiq', false, $plugin_rel_path );
}


//// admin menu ////

function rentiq_admin_menu() { 

    global $menu;

    // logo //
    $menu[0] = array( __( 'Admin Home', 'rentiq' ), 'read', admin_url( 'admin.php?page=apartment-map' ), '', 'admin-logo', 'admin-logo', 'dashicons-none' );

    // logout //
    $menu[9999] = array(__( 'Logout', 'rentiq' ), 'read', wp_logout_url(), '', 'admin-logout', 'admin-logout', 'dashicons-lock' );

    // owners, apartments, parkings //
    add_menu_page( __( 'Owners', 'rentiq' ), __( 'Owners', 'rentiq' ), 'edit_posts', 'edit-tags.php?taxonomy=owner', '', 'dashicons-businessman', 25 );
    add_menu_page( __( 'Apartments', 'rentiq' ), __( 'Apartments', 'rentiq' ), 'edit_posts', 'edit-tags.php?taxonomy=apartment', '', 'dashicons-admin-multisite', 25 );
    add_menu_page( __( 'Parkings', 'rentiq' ), __( 'Parkings', 'rentiq' ), 'edit_posts', 'edit-tags.php?taxonomy=parking', '', 'dashicons-car', 25 );

    add_menu_page( __( 'Apartment map', 'rentiq' ), __( 'Apartment map', 'rentiq' ), 'edit_posts', 'apartment-map', 'rentiq_create_apartment_map', 'dashicons-admin-home', 25 );
    add_menu_page( __( 'Parking map', 'rentiq' ), __( 'Parking map', 'rentiq' ), 'edit_posts', 'parking-map', 'rentiq_create_parking_map', 'dashicons-columns', 25 );

    // hotel runner //
    $menu[36] = array( __('Hotel Runner'), 'edit_posts', 'https://mlz-apartments.hotelrunner.com/admin', '', 'open-if-no-js menu-top', 'dashicons-calendar-alt', 25 );

    if( current_user_can('editor')) {
        global $submenu;
        unset($submenu['edit.php?post_type=reservation']);
        unset($submenu['edit.php?post_type=expenses']);
    }

}

function rentiq_highlight_taxonomy_parent_menu( $parent_file ) {

	if ( get_current_screen()->taxonomy == 'owner' ) {
		$parent_file = 'edit-tags.php?taxonomy=owner';
	}
    if ( get_current_screen()->taxonomy == 'parking' ) {
		$parent_file = 'edit-tags.php?taxonomy=parking';
	}
    if ( get_current_screen()->taxonomy == 'apartment' ) {
		$parent_file = 'edit-tags.php?taxonomy=apartment';
	}
	return $parent_file;
}


//// enqueue scripts ////

function rentiq_reservation_enqueue_scripts( $hook ) {

    global $post_type, $taxnow;
    if( 'reservation' == $post_type || 'owner' == $taxnow || $_GET['page'] == 'rental-general-settings' ) {

    	wp_enqueue_script( 'rentiq-reservation_js', RENTIQ_URL . '/assets/js/reservation.js', array(), date('Ymdhi'), true );
    	wp_enqueue_script( 'rentiq-serializejson_js', RENTIQ_URL . '/assets/js/serializejson.js', array(), date('Ymdhi'), true );
        
        wp_localize_script('rentiq-reservation_js', 'rentiqLang', array(
			'chceteodejit' => __( 'You are leaving the page. Do you want to save or erase the event?', 'rentiq' ),
			'siteurl' => site_url(),
            'document_url' => site_url('wp-content/uploads'),
            'notcreated' => __( 'Document could not be created because of an error.', 'rentiq' ),
            'form_error' => __( 'Please fill all the basic reservation data before creating any document.', 'rentiq' ),
            'noemail' => __( 'There is no receiver email filled.', 'rentiq' ),
            'notsent' => __( 'The document could not be sent because of an error.', 'rentiq' ),
            'reallycreate' => __( 'Do you really want to create yearly invoices for all the owners? You can recreate them later individually.', 'rentiq' ),
            'yearlycreated' => __( ' yearly invoices was created.', 'rentiq' ),
            'reallysend' => __( 'Do you really want to send the email?', 'rentiq' ),
            'reallydelete' => __( 'Do you really want to delete the document?', 'rentiq' ),
            'add_payment' => __( 'Add payment', 'rentiq' ),
            'save_payment' => __( 'Save payments', 'rentiq' ),
            'balance' => __( 'Balance: ', 'rentiq' ),
            'payments_saved' => __( 'Payments saved', 'rentiq' ),
            'date' => __( '... on date', 'rentiq' ),
            'datefrom' => __( 'From ..', 'rentiq' ),
            'datetill' => __( 'Till ..', 'rentiq' ),
		));

    }

    if ( 'index.php' == $hook ) {

    	wp_enqueue_script( 'rentiq-chart_js', RENTIQ_URL . '/includes/plugins/chart.js/dist/chart.js', array(), date('Ymdhi'), true );
        
    }
}

//// delete invoices with reservation ////

function delete_reservation_invoices( $reservation_id ) { 

    if ( get_post_type( $reservation_id ) == 'reservation' ) {

        $documents = get_posts( array(
            'post_type'   => array( 'contract_tenant', 'invoice_deposit', 'invoice_rental', 'invoice_deporeturn', 'invoice_agent', 'invoice_ownerempty' ),
            'numberposts' => -1,
            'post_status' => 'any',
            'meta_query'  => array(
                array(
                    'key'    => 'reservation_id',
                    'value'  => $reservation_id,
                )
            ),
        ));

        if ( $documents ) {
            foreach( $documents as $document ) {

                wp_delete_post( $document->ID, true );

            }
        }

    }

}
add_action( 'delete_post', 'delete_reservation_invoices', 10, 1 ); 


//// rentiq logging ////

function rentiq_logs( $message ) {

    if( is_array( $message ) ) { 
        $message = json_encode( $message ); 
    }

	$dt = new DateTime();
	$dt->setTimezone(new DateTimeZone('Europe/Prague'));

    $logfile = fopen( RENTIQ_PATH ."log/error.log", "a" );
    fwrite( $logfile, "\n" . $dt->format('d.m Y h:i:s') . " :: " . $message ); 
    fclose( $logfile );

}


//// check user role ////

function rentiq_user_has_role( $user_role ) {

    $current_user = wp_get_current_user();
    if ( in_array( $user_role, (array) $current_user->roles ) ) {
        return true;
    } else {
        return false;
    }
    
}


//// delete pdf when deleting post ////

function rentiq_delete_pdf_with_post( $post_id, $post ) {

    if ( ( 'receipts' !== $post->post_type ) || ( 'invoice_owner_payout' !== $post->post_type ) ) {
        return;
    }
    Rentiq_class::delete_pdf_document( $post_id, $post->post_type ); 
}
add_action( 'before_delete_post', 'rentiq_delete_pdf_with_post', 99, 2 );
					