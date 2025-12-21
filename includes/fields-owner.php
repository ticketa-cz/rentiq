<?php 

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//// contract type fields ////

function rentiq_owner_fields() {

    if( function_exists('acf_add_local_field_group') ) {

        acf_add_local_field_group( array (
            'key' => 'owner_fields',
            'title' => __( 'Owner data', 'rentiq' ),
            'fields' => array (
                array (
                    'key' => 'field_owner_address',
                    'label' => __( 'Address', 'rentiq' ),
                    'name' => 'owner_address',
                    'type' => 'text',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_owner_birthdate',
                    'label' => __( 'Date of birth', 'rentiq' ),
                    'name' => 'owner_birthdate',
                    'type' => 'date_picker',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_owner_country',
                    'label' => __( 'Country', 'rentiq' ),
                    'name' => 'owner_country',
                    'return_format' => 'label',
                    'type' => 'country',
                    'allow_null' => 1,
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_owner_phone',
                    'label' => __( 'Phone number', 'rentiq' ),
                    'name' => 'owner_phone',
                    'type' => 'intl_tel_input',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_owner_email',
                    'label' => __( 'Email', 'rentiq' ),
                    'name' => 'owner_email',
                    'type' => 'email',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_owner_other_contact',
                    'label' => __( 'Other contact', 'rentiq' ),
                    'name' => 'other_contact',
                    'type' => 'text',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_owner_id_number',
                    'label' => __( 'ID number', 'rentiq' ),
                    'name' => 'id_number',
                    'type' => 'text',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_ownersubmit_button',
                    'name' => 'ownersubmit_button',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-owner_submit',
                    'button_value' => __( 'Update owner', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary',
                    'prefix' => 'rentiq_',
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'owner',
                        ),
                    ),
                ),
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ));

        // owner yearly invoices //

        acf_add_local_field_group( array (
            'key' => 'owner_yearly_invoices',
            'title' => __( 'Owner yearly invoices - sinking fund & management fees', 'rentiq' ),
            'fields' => array (
                array (
                    'key' => 'field_reservation_create_invoice_owner_from',
                    'label' => __( 'Owner yearly invoice date from', 'rentiq' ),
                    'name' => 'reservation_create_invoice_owner_from',
                    'type' => 'date_picker',
                    'placeholder' => 'from',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_owner_from',
                        'class' => 'owner_date',
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_invoice_owner_till',
                    'label' => __( 'Owner yearly invoice date till', 'rentiq' ),
                    'name' => 'reservation_create_invoice_owner_till',
                    'type' => 'date_picker',
                    'placeholder' => 'till',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_owner_till',
                        'class' => 'owner_date',
                    ),
                ),
                array (
                    'key' => 'field_owneryearly_button',
                    'name' => 'owneryearly_button',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-invoice_owner',
                    'button_value' => __( 'Create owner fees invoice', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-backup',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_invoice_owner_date',
                    'label' => __( 'Owner yearly invoice date', 'rentiq' ),
                    'name' => 'reservation_create_invoice_owner_date',
                    'type' => 'date_picker',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_owner',
                        'class' => 'document_date',
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_invoice_owner_date',
                    'label' => __( 'Owner yearly invoice date', 'rentiq' ),
                    'name' => 'reservation_create_invoice_owner_date',
                    'type' => 'date_picker',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_owner',
                        'class' => 'document_date',
                    ),
                ),
                array (
                    'key' => 'field_owner_invoice_owner_post',
                    //'instructions' => __( 'Created in batch on Rental settings page', 'rentiq' ),
                    'name' => 'owner_invoice_owner_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'owner_documents',
                        'id' => 'invoice_owner_post',
                    ),
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'owner',
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

        // owner payout invoices //

        acf_add_local_field_group( array (
            'key' => 'owner_payout_invoices',
            'title' => __( 'Owner payout invoices', 'rentiq' ),
            'fields' => array (
                array (
                    'key' => 'field_ownerpayout_button',
                    'name' => 'ownerpayout_button',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-invoice_owner_payout',
                    'button_value' => __( 'Create new payout invoice', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-backup',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_invoice_owner_payout_date',
                    'label' => __( 'Owner payout invoice date', 'rentiq' ),
                    'name' => 'reservation_create_invoice_owner_payout_date',
                    'type' => 'date_picker',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_owner_payout',
                        'class' => 'document_date',
                    ),
                ),
                array (
                    'key' => 'field_owner_invoice_owner_payout_post',
                    'name' => 'owner_invoice_owner_payout_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'owner_documents',
                        'id' => 'invoice_owner_payout_post',
                    ),
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'owner',
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
add_action('acf/init', 'rentiq_owner_fields');



//// year select ////

function sevenYearSelect($field) {
    
    $currentYear = date('Y');
    
    // Create choices array
    $field['choices'] = array();
    
    // Loop through a range of years and add to field 'choices'. Change range as needed.
    foreach(range($currentYear+1, $currentYear-5) as $year) {
            
        $field['choices'][$year] = $year;
            
    }

    // Return the field
    return $field;
    
}

add_filter('acf/load_field/key=field_reservation_create_invoice_owner_payout_year', 'sevenYearSelect');
add_filter('acf/load_field/key=field_reservation_create_invoice_owner_year', 'sevenYearSelect');



//// remove meta boxes ////

function remove_owner_meta_boxes() {

    global $taxonomy;

    if( empty( $taxonomy ) || ! in_array( $taxonomy, array( 'owner' ) ) ) {
        return;
    }

    ?>
        <style>
            .acf-columns-2 { margin-right: 0; }
            .acf-column-2 { display: none; }
        </style>

    <?php

}
add_action( 'admin_head', 'remove_owner_meta_boxes' );



//// load owner invoices ////

$document_types = array( 'invoice_owner', 'invoice_owner_payout' );
foreach( $document_types as $document_type ) {

    add_filter( 'acf/load_field/name=owner_'.$document_type.'_post', function( $field ) use ( $document_type ) {
    
        $field['value'] = Rentiq_class::show_attached_documents( $document_type, Rentiq_class::get_term_being_edited()->term_id, 'term' );
        return $field;

    });
}