<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//// functions ////

function remove_child_post_views( $views ) {
	unset($views['all']);
	unset($views['publish']);
	unset($views['trash']);
	unset($views['draft']);
	unset($views['pending']);
	return $views;
}

//// login redirect ////

function dashboard_redirect($url) {
	
	$thisuser = wp_get_current_user();
	if ( !in_array( 'administrator', (array) $thisuser->roles ) ) {
		$url = admin_url().'edit.php?post_type=reservation';
	}
	return $url;
}
add_filter('login_redirect', 'dashboard_redirect');


//// rename logout link ////

function rename_top_level_menu( $original, $new ) {
   global $menu;

   foreach( $menu AS $k => $v ) {
      if( $original == $v[0] ) {
         $menu[$k][0] = $new;
      }
   }
   $menu[9999] = array(__( 'Logout', 'rentiq' ), 'read', wp_logout_url(),'','admin-logout','admin-logout','dashicons-lock');
}

//// color scheme ////

$suffix = is_rtl() ? '-rtl' : '';

function additional_admin_color_schemes() {

	$rentiqcssfile = RENTIQ_URL . 'assets/admin-theme/rentiq-admin-theme.css';
	
	wp_admin_css_color( 'rentiq', __( 'Rentiq' ),
		'',
		array( '#121a0d', '#1d2b14', '#5D824B', '#7dc14d' )
	);
	
	wp_enqueue_style('rentiq_admin_css', $rentiqcssfile, array(), date("YmdHi"));
	
}
add_action('admin_init', 'additional_admin_color_schemes');


//// hide filters in event list ////

function remove_post_folders( $views ) {
    if ( current_user_can( 'manage_options' ) ) {
        return $views;
	}

    $remove_views = array( 'all','publish','future','sticky','draft','pending','trash', 'mine' );

    foreach( $remove_views as $view ) {
        if ( isset( $views[$view] )) {
            unset( $views[$view] );
		}
    }
    return $views;
}
add_filter( 'views_edit-tc_events', 'remove_post_folders');
add_filter( 'views_edit-exported_tickets', 'remove_post_folders');
add_filter( 'views_edit-product', 'remove_post_folders');
add_filter( 'views_edit-monthly_invoice', 'remove_post_folders');


//// remove help tabs ////

function rentiq_remove_help_tabs(){
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}
add_action( 'admin_head', 'rentiq_remove_help_tabs' );

//// remove display options tabs ////

function wpb_remove_screen_options() {

	$user = wp_get_current_user();
	if ( !in_array( 'administrator', (array) $user->roles ) ) {
		return false;
	}
	return true; 
}
add_filter('screen_options_show_screen', 'wpb_remove_screen_options');