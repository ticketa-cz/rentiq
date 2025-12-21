<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Register Custom Post Type Contract Content //

function create_contract_type_cpt() {

	$labels = array(
		'name' => _x( 'Contract types', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Contract type', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Contract types', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Contract type', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Contract type Archives', 'rentiq' ),
		'attributes' => __( 'Contract type Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Contract type:', 'rentiq' ),
		'all_items' => __( 'Contract types', 'rentiq' ),
		'add_new_item' => __( 'Add New Contract type', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Contract type', 'rentiq' ),
		'edit_item' => __( 'Edit Contract type', 'rentiq' ),
		'update_item' => __( 'Update Contract type', 'rentiq' ),
		'view_item' => __( 'View Contract type', 'rentiq' ),
		'view_items' => __( 'View Contract types', 'rentiq' ),
		'search_items' => __( 'Search Contract type', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Contract type', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Contract type', 'rentiq' ),
		'items_list' => __( 'Contract types list', 'rentiq' ),
		'items_list_navigation' => __( 'Contract types list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Contract types list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Contract type', 'rentiq' ),
		'description' => __( 'Contract types for any reservation ', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-book-alt',
		'supports' => array('title', 'editor', 'author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => false,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'contract_type', $args );

}
add_action( 'init', 'create_contract_type_cpt', 0 );



// Register Custom Post Type Reservation //

function create_reservation_cpt() {

	$labels = array(
		'name' => _x( 'Reservations', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Reservation', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Reservations', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Reservation', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Reservation Archives', 'rentiq' ),
		'attributes' => __( 'Reservation Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Reservation:', 'rentiq' ),
		'all_items' => __( 'All Reservations', 'rentiq' ),
		'add_new_item' => __( 'Add New Reservation', 'rentiq' ),
		'add_new' => __( 'New Reservation', 'rentiq' ),
		'new_item' => __( 'New Reservation', 'rentiq' ),
		'edit_item' => __( 'Edit Reservation', 'rentiq' ),
		'update_item' => __( 'Update Reservation', 'rentiq' ),
		'view_item' => __( 'View Reservation', 'rentiq' ),
		'view_items' => __( 'View Reservations', 'rentiq' ),
		'search_items' => __( 'Search Reservation', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Reservation', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Reservation', 'rentiq' ),
		'items_list' => __( 'Reservations list', 'rentiq' ),
		'items_list_navigation' => __( 'Reservations list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Reservations list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Reservation', 'rentiq' ),
		'description' => __( 'Reservation of an apartment or parking or both', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-admin-network',
		'supports' => array('author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'reservation', $args );

}
add_action( 'init', 'create_reservation_cpt', 0 );


// Register Custom Post Type Contract //

function create_contract_cpt() {

	$labels = array(
		'name' => _x( 'Contracts', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Contract', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Contracts', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Contract', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Contract Archives', 'rentiq' ),
		'attributes' => __( 'Contract Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Contract:', 'rentiq' ),
		'all_items' => __( 'Contracts', 'rentiq' ),
		'add_new_item' => __( 'Add New Contract', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Contract', 'rentiq' ),
		'edit_item' => __( 'Edit Contract', 'rentiq' ),
		'update_item' => __( 'Update Contract', 'rentiq' ),
		'view_item' => __( 'View Contract', 'rentiq' ),
		'view_items' => __( 'View Contracts', 'rentiq' ),
		'search_items' => __( 'Search Contract', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Contract', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Contract', 'rentiq' ),
		'items_list' => __( 'Contracts list', 'rentiq' ),
		'items_list_navigation' => __( 'Contracts list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Contracts list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Contract', 'rentiq' ),
		'description' => __( 'Tenant contracts for any reservation ', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-book-alt',
		'supports' => array('title', 'editor', 'excerpt', 'author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=reservation',
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'contract_tenant', $args );

}
add_action( 'init', 'create_contract_cpt', 0 );



// Register Custom Post Type Contract Extension //

function create_contract_extension_cpt() {

	$labels = array(
		'name' => _x( 'Contract extensions', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Contract extension', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Contract extension extensions', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Contract extension', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Contract extension Archives', 'rentiq' ),
		'attributes' => __( 'Contract extension Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Contract extension:', 'rentiq' ),
		'all_items' => __( 'Contract extensions', 'rentiq' ),
		'add_new_item' => __( 'Add New Contract extension', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Contract extension', 'rentiq' ),
		'edit_item' => __( 'Edit Contract extension', 'rentiq' ),
		'update_item' => __( 'Update Contract extension', 'rentiq' ),
		'view_item' => __( 'View Contract extension', 'rentiq' ),
		'view_items' => __( 'View Contract extensions', 'rentiq' ),
		'search_items' => __( 'Search Contract extension', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Contract extension', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Contract extension', 'rentiq' ),
		'items_list' => __( 'Contract extensions list', 'rentiq' ),
		'items_list_navigation' => __( 'Contract extensions list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Contract extensions list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Contract extension', 'rentiq' ),
		'description' => __( 'Tenant contracts for any reservation ', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-book-alt',
		'supports' => array('title', 'editor', 'excerpt', 'author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=reservation',
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'contract_extension', $args );

}
add_action( 'init', 'create_contract_extension_cpt', 0 );


// Register Custom Post Type Deposit invoice //

function create_depositinvoice_cpt() {

	$labels = array(
		'name' => _x( 'Deposit invoices', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Deposit invoice', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Deposit invoices', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Deposit invoice', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Deposit invoice Archives', 'rentiq' ),
		'attributes' => __( 'Deposit invoice Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Deposit invoice:', 'rentiq' ),
		'all_items' => __( 'Deposit invoices', 'rentiq' ),
		'add_new_item' => __( 'Add New Deposit invoice', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Deposit invoice', 'rentiq' ),
		'edit_item' => __( 'Edit Deposit invoice', 'rentiq' ),
		'update_item' => __( 'Update Deposit invoice', 'rentiq' ),
		'view_item' => __( 'View Deposit invoice', 'rentiq' ),
		'view_items' => __( 'View Deposit invoices', 'rentiq' ),
		'search_items' => __( 'Search Deposit invoice', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Deposit invoice', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Deposit invoice', 'rentiq' ),
		'items_list' => __( 'Deposit invoices list', 'rentiq' ),
		'items_list_navigation' => __( 'Deposit invoices list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Deposit invoices list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Deposit invoice', 'rentiq' ),
		'description' => __( 'Invoices for any reservation deposit', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-lightbulb',
		'supports' => array('title', 'editor', 'excerpt', 'author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=reservation',
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'invoice_deposit', $args );

}
add_action( 'init', 'create_depositinvoice_cpt', 0 );


// Register Custom Post Type Rental invoice //

function create_rentalinvoice_cpt() {

	$labels = array(
		'name' => _x( 'Rental invoices', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Rental invoice', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Rental invoices', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Rental invoice', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Rental invoice Archives', 'rentiq' ),
		'attributes' => __( 'Rental invoice Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Rental invoice:', 'rentiq' ),
		'all_items' => __( 'Rental invoices', 'rentiq' ),
		'add_new_item' => __( 'Add New Rental invoice', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Rental invoice', 'rentiq' ),
		'edit_item' => __( 'Edit Rental invoice', 'rentiq' ),
		'update_item' => __( 'Update Rental invoice', 'rentiq' ),
		'view_item' => __( 'View Rental invoice', 'rentiq' ),
		'view_items' => __( 'View Rental invoices', 'rentiq' ),
		'search_items' => __( 'Search Rental invoice', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Rental invoice', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Rental invoice', 'rentiq' ),
		'items_list' => __( 'Rental invoices list', 'rentiq' ),
		'items_list_navigation' => __( 'Rental invoices list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Rental invoices list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Rental invoice', 'rentiq' ),
		'description' => __( 'Invoices for any reservation made', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-admin-post',
		'supports' => array('title', 'editor', 'excerpt', 'author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=reservation',
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'invoice_rental', $args );

}
add_action( 'init', 'create_rentalinvoice_cpt', 0 );


// Register Custom Post Type Termination invoice //

function create_depositreturninvoice_cpt() {

	$labels = array(
		'name' => _x( 'Termination invoices', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Termination invoice', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Termination invoices', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Termination invoice', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Termination invoice Archives', 'rentiq' ),
		'attributes' => __( 'Termination invoice Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Termination invoice:', 'rentiq' ),
		'all_items' => __( 'Termination invoices', 'rentiq' ),
		'add_new_item' => __( 'Add New Termination invoice', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Termination invoice', 'rentiq' ),
		'edit_item' => __( 'Edit Termination invoice', 'rentiq' ),
		'update_item' => __( 'Update Termination invoice', 'rentiq' ),
		'view_item' => __( 'View Termination invoice', 'rentiq' ),
		'view_items' => __( 'View Termination invoices', 'rentiq' ),
		'search_items' => __( 'Search Termination invoice', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Termination invoice', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Termination invoice', 'rentiq' ),
		'items_list' => __( 'Termination invoices list', 'rentiq' ),
		'items_list_navigation' => __( 'Termination invoices list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Termination invoices list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Termination invoice', 'rentiq' ),
		'description' => __( 'Invoices for returning a deposit', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-migrate',
		'supports' => array('title', 'editor', 'author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=reservation',
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'invoice_deporeturn', $args );

}
add_action( 'init', 'create_depositreturninvoice_cpt', 0 );



// Register Custom Post Type Settlement invoice //

function create_settlementinvoice_cpt() {

	$labels = array(
		'name' => _x( 'Settlement invoices', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Settlement invoice', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Settlement invoices', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Settlement invoice', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Settlement invoice Archives', 'rentiq' ),
		'attributes' => __( 'Settlement invoice Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Settlement invoice:', 'rentiq' ),
		'all_items' => __( 'Settlement invoices', 'rentiq' ),
		'add_new_item' => __( 'Add New Settlement invoice', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Settlement invoice', 'rentiq' ),
		'edit_item' => __( 'Edit Settlement invoice', 'rentiq' ),
		'update_item' => __( 'Update Settlement invoice', 'rentiq' ),
		'view_item' => __( 'View Settlement invoice', 'rentiq' ),
		'view_items' => __( 'View Settlement invoices', 'rentiq' ),
		'search_items' => __( 'Search Settlement invoice', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Settlement invoice', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Settlement invoice', 'rentiq' ),
		'items_list' => __( 'Settlement invoices list', 'rentiq' ),
		'items_list_navigation' => __( 'Settlement invoices list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Settlement invoices list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Settlement invoice', 'rentiq' ),
		'description' => __( 'Invoices for settling the deposit', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-migrate',
		'supports' => array('title', 'editor', 'author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=reservation',
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'invoice_settlement', $args );

}
add_action( 'init', 'create_settlementinvoice_cpt', 0 );



// Register Custom Post Type Owner invoice //

function create_ownerinvoice_cpt() {

	$labels = array(
		'name' => _x( 'Owner invoices', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Owner invoice', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Owner invoices', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Owner invoice', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Owner invoice Archives', 'rentiq' ),
		'attributes' => __( 'Owner invoice Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Owner invoice:', 'rentiq' ),
		'all_items' => __( 'All Owner invoices', 'rentiq' ),
		'add_new_item' => __( 'Add New Owner invoice', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Owner invoice', 'rentiq' ),
		'edit_item' => __( 'Edit Owner invoice', 'rentiq' ),
		'update_item' => __( 'Update Owner invoice', 'rentiq' ),
		'view_item' => __( 'View Owner invoice', 'rentiq' ),
		'view_items' => __( 'View Owner invoices', 'rentiq' ),
		'search_items' => __( 'Search Owner invoice', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Owner invoice', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Owner invoice', 'rentiq' ),
		'items_list' => __( 'Owner invoices list', 'rentiq' ),
		'items_list_navigation' => __( 'Owner invoices list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Owner invoices list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Owner invoice', 'rentiq' ),
		'description' => __( 'Invoices made to the owners', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-portfolio',
		'supports' => array('title', 'editor', 'author', 'custom-fields'),
		'taxonomies' => array('owner'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'invoice_owner', $args );

}
add_action( 'init', 'create_ownerinvoice_cpt', 0 );


// Register Custom Post Type Owner Payout monthly invoice //

function create_ownerpayout_invoice_cpt() {

	$labels = array(
		'name' => _x( 'Owner payout invoices', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Owner payout invoice', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Owner payout invoices', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Owner payout invoice', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Owner payout invoice Archives', 'rentiq' ),
		'attributes' => __( 'Owner payout invoice Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Owner payout invoice:', 'rentiq' ),
		'all_items' => __( 'All Owner payout invoices', 'rentiq' ),
		'add_new_item' => __( 'Add New Owner payout invoice', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Owner payout invoice', 'rentiq' ),
		'edit_item' => __( 'Edit Owner payout invoice', 'rentiq' ),
		'update_item' => __( 'Update Owner payout invoice', 'rentiq' ),
		'view_item' => __( 'View Owner payout invoice', 'rentiq' ),
		'view_items' => __( 'View Owner payout invoices', 'rentiq' ),
		'search_items' => __( 'Search Owner payout invoice', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Owner payout invoice', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Owner payout invoice', 'rentiq' ),
		'items_list' => __( 'Owner payout invoices list', 'rentiq' ),
		'items_list_navigation' => __( 'Owner payout invoices list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Owner payout invoices list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Owner payout invoice', 'rentiq' ),
		'description' => __( 'Invoices payed to the owners', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-money-alt',
		'supports' => array('title', 'editor', 'author', 'custom-fields'),
		'taxonomies' => array('owner'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'invoice_owner_payout', $args );

}
add_action( 'init', 'create_ownerpayout_invoice_cpt', 0 );


// Register Custom Post Type Owner empty period invoice //

function create_owneremptyinvoice_cpt() {

	$labels = array(
		'name' => _x( 'Owner empty period invoices', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Owner empty period invoice', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Owner empty period invoices', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Owner empty period invoice', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Owner empty period invoice Archives', 'rentiq' ),
		'attributes' => __( 'Owner empty period invoice Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Owner empty period invoice:', 'rentiq' ),
		'all_items' => __( 'All Owner empty period invoices', 'rentiq' ),
		'add_new_item' => __( 'Add New Owner empty period invoice', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Owner empty period invoice', 'rentiq' ),
		'edit_item' => __( 'Edit Owner empty period invoice', 'rentiq' ),
		'update_item' => __( 'Update Owner empty period invoice', 'rentiq' ),
		'view_item' => __( 'View Owner empty period invoice', 'rentiq' ),
		'view_items' => __( 'View Owner empty period invoices', 'rentiq' ),
		'search_items' => __( 'Search Owner empty period invoice', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Owner empty period invoice', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Owner empty period invoice', 'rentiq' ),
		'items_list' => __( 'Owner empty period invoices list', 'rentiq' ),
		'items_list_navigation' => __( 'Owner empty period invoices list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Owner empty period invoices list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Owner empty period invoice', 'rentiq' ),
		'description' => __( 'Invoices made to the owners for the empty period', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-controls-pause',
		'supports' => array('title', 'editor', 'author', 'custom-fields'),
		'taxonomies' => array( 'owner', 'apartment', 'parking' ),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'invoice_ownerempty', $args );

}
add_action( 'init', 'create_owneremptyinvoice_cpt', 0 );


// Register Custom Post Type Agent invoice //

function create_agentinvoice_cpt() {

	$labels = array(
		'name' => _x( 'Agent invoices', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Agent invoice', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Agent invoices', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Agent invoice', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Agent invoice Archives', 'rentiq' ),
		'attributes' => __( 'Agent invoice Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Agent invoice:', 'rentiq' ),
		'all_items' => __( 'All Agent invoices', 'rentiq' ),
		'add_new_item' => __( 'Add New Agent invoice', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Agent invoice', 'rentiq' ),
		'edit_item' => __( 'Edit Agent invoice', 'rentiq' ),
		'update_item' => __( 'Update Agent invoice', 'rentiq' ),
		'view_item' => __( 'View Agent invoice', 'rentiq' ),
		'view_items' => __( 'View Agent invoices', 'rentiq' ),
		'search_items' => __( 'Search Agent invoice', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Agent invoice', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Agent invoice', 'rentiq' ),
		'items_list' => __( 'Agent invoices list', 'rentiq' ),
		'items_list_navigation' => __( 'Agent invoices list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Agent invoices list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Agent invoice', 'rentiq' ),
		'description' => __( 'Invoices made to the agents', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-portfolio',
		'supports' => array('title', 'editor', 'excerpt', 'author', 'custom-fields'),
		'taxonomies' => array('apartment', 'parking'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=reservation',
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'invoice_agent', $args );

}
add_action( 'init', 'create_agentinvoice_cpt', 0 );


// Register Custom Post Type Expenses //

function create_expenses_cpt() {

	$labels = array(
		'name' => _x( 'Expenses', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Expense', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Expenses', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Expense', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Expense Archives', 'rentiq' ),
		'attributes' => __( 'Expense Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Expense:', 'rentiq' ),
		'all_items' => __( 'All Expenses', 'rentiq' ),
		'add_new_item' => __( 'Add New Expense', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Expense', 'rentiq' ),
		'edit_item' => __( 'Edit Expense', 'rentiq' ),
		'update_item' => __( 'Update Expense', 'rentiq' ),
		'view_item' => __( 'View Expense', 'rentiq' ),
		'view_items' => __( 'View Expenses', 'rentiq' ),
		'search_items' => __( 'Search Expense', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Expense', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Expense', 'rentiq' ),
		'items_list' => __( 'Expenses list', 'rentiq' ),
		'items_list_navigation' => __( 'Expenses list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Expenses list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Expense', 'rentiq' ),
		'description' => __( 'Expenses made by the management', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-money-alt',
		'supports' => array('title', 'author', 'custom-fields'),
		'taxonomies' => array(),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'expenses', $args );

}
add_action( 'init', 'create_expenses_cpt', 0 );


// Register Custom Post Type Income //

function create_income_cpt() {

	$labels = array(
		'name' => _x( 'Incomes', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Income', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Incomes', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Income', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Income Archives', 'rentiq' ),
		'attributes' => __( 'Income Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Income:', 'rentiq' ),
		'all_items' => __( 'All Incomes', 'rentiq' ),
		'add_new_item' => __( 'Add New Income', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Income', 'rentiq' ),
		'edit_item' => __( 'Edit Income', 'rentiq' ),
		'update_item' => __( 'Update Income', 'rentiq' ),
		'view_item' => __( 'View Income', 'rentiq' ),
		'view_items' => __( 'View Incomes', 'rentiq' ),
		'search_items' => __( 'Search Income', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Income', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Income', 'rentiq' ),
		'items_list' => __( 'Incomes list', 'rentiq' ),
		'items_list_navigation' => __( 'Incomes list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Incomes list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Income', 'rentiq' ),
		'description' => __( 'Incomes from the rents', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-money-alt',
		'supports' => array('title', 'author', 'custom-fields'),
		'taxonomies' => array(),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
		'capabilities' => array(
			'create_posts' => 'do_not_allow',
		),
	);
	register_post_type( 'incomes', $args );

}
add_action( 'init', 'create_income_cpt', 0 );


// Register Custom Post Type RECEIPT //

function create_receipts_cpt() {

	$labels = array(
		'name' => _x( 'Receipts', 'Post Type General Name', 'rentiq' ),
		'singular_name' => _x( 'Receipt', 'Post Type Singular Name', 'rentiq' ),
		'menu_name' => _x( 'Receipts', 'Admin Menu text', 'rentiq' ),
		'name_admin_bar' => _x( 'Receipt', 'Add New on Toolbar', 'rentiq' ),
		'archives' => __( 'Receipt Archives', 'rentiq' ),
		'attributes' => __( 'Receipt Attributes', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Receipt:', 'rentiq' ),
		'all_items' => __( 'All Receipts', 'rentiq' ),
		'add_new_item' => __( 'Add New Receipt', 'rentiq' ),
		'add_new' => __( 'Add New', 'rentiq' ),
		'new_item' => __( 'New Receipt', 'rentiq' ),
		'edit_item' => __( 'Edit Receipt', 'rentiq' ),
		'update_item' => __( 'Update Receipt', 'rentiq' ),
		'view_item' => __( 'View Receipt', 'rentiq' ),
		'view_items' => __( 'View Receipts', 'rentiq' ),
		'search_items' => __( 'Search Receipt', 'rentiq' ),
		'not_found' => __( 'Not found', 'rentiq' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'rentiq' ),
		'featured_image' => __( 'Featured Image', 'rentiq' ),
		'set_featured_image' => __( 'Set featured image', 'rentiq' ),
		'remove_featured_image' => __( 'Remove featured image', 'rentiq' ),
		'use_featured_image' => __( 'Use as featured image', 'rentiq' ),
		'insert_into_item' => __( 'Insert into Receipt', 'rentiq' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Receipt', 'rentiq' ),
		'items_list' => __( 'Receipts list', 'rentiq' ),
		'items_list_navigation' => __( 'Receipts list navigation', 'rentiq' ),
		'filter_items_list' => __( 'Filter Receipts list', 'rentiq' ),
	);
	$args = array(
		'label' => __( 'Receipt', 'rentiq' ),
		'description' => __( 'Receipts from the rents', 'rentiq' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-money-alt',
		'supports' => array('title', 'author', 'custom-fields'),
		'taxonomies' => array(),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 25,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
		'capabilities' => array(
			'create_posts' => 'do_not_allow',
		),
	);
	register_post_type( 'receipts', $args );

}
add_action( 'init', 'create_receipts_cpt', 0 );