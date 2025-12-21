<?php 

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//// contract type fields ////

function rentiq_apartment_fields() {

    if( function_exists('acf_add_local_field_group') ) {
        
        acf_add_local_field_group( array (
            'key' => 'apartment_fields',
            'title' => __( 'Apartment options', 'rentiq' ),
            'fields' => array (
                array (
                    'key' => 'field_apartment_owner',
                    'label' => __( 'Apartment owner', 'rentiq' ),
                    'name' => 'apartment_owner',
                    'type' => 'taxonomy',
                    'taxonomy' => 'owner',
                    'field_type' => 'select',
                    'allow_null' => 1,
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_apartment_rental_price',
                    'label' => __( 'Rental price', 'rentiq' ),
                    'name' => 'apartment_rental_price',
                    'type' => 'number',
                    'prepend' => '$',
                    'instructions' => __( 'You can change this for a reservation specifically later', 'rentiq' ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_apartment_number',
                    'label' => __( 'Apartment number', 'rentiq' ),
                    'name' => 'apartment_number',
                    'type' => 'number',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_apartment_building',
                    'label' => __( 'Apartment building', 'rentiq' ),
                    'name' => 'apartment_building',
                    'type' => 'radio',
                    'choices' => array(
                        'A'	=> 'A',
                        //'B'	=> 'B',
                    ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_apartment_floor',
                    'label' => __( 'Apartment floor', 'rentiq' ),
                    'name' => 'apartment_floor',
                    'type' => 'number',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_apartment_type',
                    'label' => __( 'Apartment type', 'rentiq' ),
                    'name' => 'apartment_type',
                    'type' => 'select',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_rental_service',
                    'label' => __( 'Rental service', 'rentiq' ),
                    'name' => 'rental_service',
                    'type' => 'radio',
                    'choices' => array(
                        'yes'	=> 'Active',
                        'no'	=> 'No'
                    ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_apartment_tv',
                    'label' => __( 'TV', 'rentiq' ),
                    'name' => 'apartment_tv',
                    'type' => 'radio',
                    'choices' => array(
                        'yes'	=> 'Yes',
                        'no'	=> 'No'
                    ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_apartment_parking_attached',
                    'label' => __( 'Parking space attached to apartment', 'rentiq' ),
                    'name' => 'apartment_parking_attached',
                    'type' => 'taxonomy',
                    'taxonomy' => 'parking',
                    'field_type' => 'select',
                    'allow_null' => 1,
                    'prefix' => 'rentiq_',
                ),
                array(
                    'key' => 'field_apartment_expenses',
                    'label' => __( 'Additional empty period expenses', 'rentiq' ),
                    'name' => 'apartment_expenses',
                    'prefix' => 'rentiq_',
                    'type' => 'repeater',
                    'sub_fields'   => array(
                        array (
                            'key' => 'field_apartment_expense_date',
                            'label' => __( 'Date', 'rentiq' ),
                            'name' => 'apartment_expense_date',
                            'type' => 'date_picker',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_apartment_expense_name',
                            'label' => __( 'Expense name', 'rentiq' ),
                            'name' => 'apartment_expense_name',
                            'type' => 'text',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_apartment_expense_price',
                            'label' => __( 'Expense price', 'rentiq' ),
                            'name' => 'apartment_expense_price',
                            'type' => 'number',
                            'prepend' => '$',
                            'prefix' => 'rentiq_',
                        ),
                    ),
                    'button_label' => __( 'Add expense', 'rentiq' ),
                ),
                array(
                    'key' => 'field_apartment_sales',
                    'label' => __( 'Apartment sales', 'rentiq' ),
                    'name' => 'apartment_sales',
                    'prefix' => 'rentiq_',
                    'type' => 'repeater',
                    'sub_fields'   => array(
                        array (
                            'key' => 'field_apartment_sale_date',
                            'label' => __( 'Date', 'rentiq' ),
                            'name' => 'apartment_sale_date',
                            'type' => 'date_picker',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_apartment_new_owner',
                            'label' => __( 'Previous owner', 'rentiq' ),
                            'name' => 'apartment_new_owner',
                            'type' => 'taxonomy',
                            'taxonomy' => 'owner',
                            'field_type' => 'select',
                            'allow_null' => 1,
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_apartment_sale_price',
                            'label' => __( 'Sale price', 'rentiq' ),
                            'name' => 'apartment_sale_price',
                            'type' => 'number',
                            'prepend' => '$',
                            'prefix' => 'rentiq_',
                        ),
                    ),
                    'button_label' => __( 'Add sale', 'rentiq' ),
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'apartment',
                        ),
                    ),
                ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ));
    }
    
}
add_action('acf/init', 'rentiq_apartment_fields');


// add apartment type choices //

function acf_load_apartment_type_field_choices( $field ) {
    
    $field['choices'] = array();

    if( have_rows('apartment_types', 'option') ) {
        
        while( have_rows('apartment_types', 'option') ) {

            the_row();
            $value = get_sub_field('apartment_type_slug');
            $label = get_sub_field('apartment_type_name') . ' - ' . get_sub_field('apartment_type_size') . 'm2';
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
    }
    return $field;
}

add_filter('acf/load_field/name=apartment_type', 'acf_load_apartment_type_field_choices');