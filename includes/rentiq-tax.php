<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Register Taxonomy Apartment //

function create_apartment_tax() {

	$labels = array(
		'name'              => _x( 'Apartments', 'taxonomy general name', 'rentiq' ),
		'singular_name'     => _x( 'Apartment', 'taxonomy singular name', 'rentiq' ),
		'search_items'      => __( 'Search Apartments', 'rentiq' ),
		'all_items'         => __( 'All Apartments', 'rentiq' ),
		'parent_item'       => __( 'Parent Apartment', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Apartment:', 'rentiq' ),
		'edit_item'         => __( 'Edit Apartment', 'rentiq' ),
		'update_item'       => __( 'Update Apartment', 'rentiq' ),
		'add_new_item'      => __( 'Add New Apartment', 'rentiq' ),
		'new_item_name'     => __( 'New Apartment Name', 'rentiq' ),
		'menu_name'         => __( 'Apartment', 'rentiq' ),
	);
	$args = array(
		'labels' => $labels,
		'description' => __( 'Apartments of the building', 'rentiq' ),
		'hierarchical' => false,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => false,
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'show_in_rest' => false,
	);
	register_taxonomy( 'apartment', array( 'reservation', 'invoice_deposit', 'invoice_rental', 'contract_tenant', 'invoice_deporeturn', 'invoice_agent', 'invoice_ownerempty' ), $args );

}
add_action( 'init', 'create_apartment_tax' );


// Register Taxonomy Parking //

function create_parking_tax() {

	$labels = array(
		'name'              => _x( 'Parkings', 'taxonomy general name', 'rentiq' ),
		'singular_name'     => _x( 'Parking', 'taxonomy singular name', 'rentiq' ),
		'search_items'      => __( 'Search Parkings', 'rentiq' ),
		'all_items'         => __( 'All Parkings', 'rentiq' ),
		'parent_item'       => __( 'Parent Parking', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Parking:', 'rentiq' ),
		'edit_item'         => __( 'Edit Parking', 'rentiq' ),
		'update_item'       => __( 'Update Parking', 'rentiq' ),
		'add_new_item'      => __( 'Add New Parking', 'rentiq' ),
		'new_item_name'     => __( 'New Parking Name', 'rentiq' ),
		'menu_name'         => __( 'Parking', 'rentiq' ),
	);
	$args = array(
		'labels' => $labels,
		'description' => __( 'Parkings of the building', 'rentiq' ),
		'hierarchical' => false,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => false,
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'show_in_rest' => false,
	);
	register_taxonomy( 'parking', array( 'reservation', 'invoice_deposit', 'invoice_rental', 'contract_tenant', 'invoice_deporeturn', 'invoice_agent'), $args );

}
add_action( 'init', 'create_parking_tax' );


// Register Taxonomy Owner //

function create_owner_tax() {

	$labels = array(
		'name'              => _x( 'Owners', 'taxonomy general name', 'rentiq' ),
		'singular_name'     => _x( 'Owner', 'taxonomy singular name', 'rentiq' ),
		'search_items'      => __( 'Search Owners', 'rentiq' ),
		'all_items'         => __( 'All Owners', 'rentiq' ),
		'parent_item'       => __( 'Parent Owner', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Owner:', 'rentiq' ),
		'edit_item'         => __( 'Edit Owner', 'rentiq' ),
		'update_item'       => __( 'Update Owner', 'rentiq' ),
		'add_new_item'      => __( 'Add New Owner', 'rentiq' ),
		'new_item_name'     => __( 'New Owner Name', 'rentiq' ),
		'menu_name'         => __( 'Owner', 'rentiq' ),
	);
	$args = array(
		'labels' => $labels,
		'description' => __( 'Owners of the apartments and parking places', 'rentiq' ),
		'hierarchical' => false,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => false,
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'show_in_rest' => false,
	);
	register_taxonomy( 'owner', array( 'invoice_owner', 'reservation', 'invoice_deposit', 'invoice_rental', 'contract_tenant', 'invoice_deporeturn', 'invoice_agent', 'invoice_ownerempty', 'invoice_owner_payout' ), $args );

}
add_action( 'init', 'create_owner_tax' );



// Register Expenses Taxonomy //

function create_expenses_tax() {

	$labels = array(
		'name'              => _x( 'Expense categories', 'taxonomy general name', 'rentiq' ),
		'singular_name'     => _x( 'Expense category', 'taxonomy singular name', 'rentiq' ),
		'search_items'      => __( 'Search Expense categories', 'rentiq' ),
		'all_items'         => __( 'All Expense categories', 'rentiq' ),
		'parent_item'       => __( 'Parent Expense category', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Expense category:', 'rentiq' ),
		'edit_item'         => __( 'Edit Expense category', 'rentiq' ),
		'update_item'       => __( 'Update Expense category', 'rentiq' ),
		'add_new_item'      => __( 'Add New Expense category', 'rentiq' ),
		'new_item_name'     => __( 'New Expense category Name', 'rentiq' ),
		'menu_name'         => __( 'Expense category', 'rentiq' ),
	);
	$args = array(
		'labels' => $labels,
		'description' => __( 'Expense categories for expenses posts', 'rentiq' ),
		'hierarchical' => true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => false,
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'show_in_rest' => false,
	);
	register_taxonomy( 'expensecats', array( 'expenses' ), $args );

}
add_action( 'init', 'create_expenses_tax' );



// Register Payment Taxonomy //

function create_payments_tax() {

	$labels = array(
		'name'              => _x( 'Payment type', 'taxonomy general name', 'rentiq' ),
		'singular_name'     => _x( 'Payment type', 'taxonomy singular name', 'rentiq' ),
		'search_items'      => __( 'Search Payment types', 'rentiq' ),
		'all_items'         => __( 'All Payment types', 'rentiq' ),
		'parent_item'       => __( 'Parent Payment type', 'rentiq' ),
		'parent_item_colon' => __( 'Parent Payment type:', 'rentiq' ),
		'edit_item'         => __( 'Edit Payment type', 'rentiq' ),
		'update_item'       => __( 'Update Payment type', 'rentiq' ),
		'add_new_item'      => __( 'Add New Payment type', 'rentiq' ),
		'new_item_name'     => __( 'New Payment type Name', 'rentiq' ),
		'menu_name'         => __( 'Payment types', 'rentiq' ),
	);
	$args = array(
		'labels' => $labels,
		'description' => __( 'Payment types for income bills', 'rentiq' ),
		'hierarchical' => false,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => false,
		'show_in_quick_edit' => true,
		'show_admin_column' => true,
		'show_in_rest' => false,
	);
	register_taxonomy( 'payment_types', array( 'receipts' ), $args );

}
add_action( 'init', 'create_payments_tax' );