<?php 

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//// contract type fields ////

function rentiq_parking_fields() {

    if( function_exists('acf_add_local_field_group') ) {

        acf_add_local_field_group( array (
            'key' => 'parking_fields',
            'title' => __( 'Parking options', 'rentiq' ),
            'fields' => array (
                array (
                    'key' => 'field_parking_owner',
                    'label' => __( 'Parking owner', 'rentiq' ),
                    'name' => 'parking_owner',
                    'type' => 'taxonomy',
                    'taxonomy' => 'owner',
                    'field_type' => 'select',
                    'allow_null' => 1,
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_parking_rental_price',
                    'label' => __( 'Rental price', 'rentiq' ),
                    'name' => 'parking_rental_price',
                    'type' => 'number',
                    'prepend' => '$',
                    'instructions' => __( 'You can change this for a reservation specifically later', 'rentiq' ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_parking_number',
                    'label' => __( 'Parking number', 'rentiq' ),
                    'name' => 'parking_number',
                    'type' => 'number',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_parking_location',
                    'label' => __( 'Parking location', 'rentiq' ),
                    'name' => 'parking_location',
                    'type' => 'select',
                    'choices' => array(
                        'outside'	    => __( 'Outside', 'rentiq' ),
                        'building_A'	=> __( 'Inside', 'rentiq' ),
                        //'building_B'	=> __( 'Building B', 'rentiq' ),
                    ),
                    'prefix' => 'rentiq_',
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'parking',
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
add_action('acf/init', 'rentiq_parking_fields');