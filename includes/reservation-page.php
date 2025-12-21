<?php


if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//// reservation fields ////

function rentiq_reservation_fields() {

    if( function_exists('acf_add_local_field_group') ) {
        
        // reservation fields //

        acf_add_local_field_group( array (
            'key' => 'reservation_fields',
            'title' => __( 'Reservation', 'rentiq' ),
            'fields' => array (

                // reservation tab //
                array(
                    'key' => 'field_reservation_tab_apartment',
                    'label' => __( 'Apartment / Parking', 'rentiq' ),
                    'name' => 'reservation_tab_apartment',
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                    'icon_class' => 'accordions-home',
			        'show_icon_only' => 0,
                ),
                array(
                    'key' => 'field_reservation_type',
                    'label' => __( 'Reservation type', 'rentiq' ),
                    'name' => 'reservation_type',
                    'type' => 'radio',
                    'placement' => 'top',
			        'choices' => array(
                        //'both' => __( 'Apartment & Parking', 'rentiq' ),
                        'apartment' => __( 'Apartment', 'rentiq' ),
                        'parking' => __( 'Parking', 'rentiq' ),
                    ),
                    'default' => 'both',
                ),
                array (
                    'key' => 'field_reservation_apartment',
                    'label' => __( 'Apartment', 'rentiq' ),
                    'name' => 'reservation_apartment',
                    'type' => 'taxonomy',
                    'field_type' => 'select',
                    'taxonomy' => 'apartment',
                    'load_save_terms' => 1,
                    'add_term' => 0,
                    'allow_null' => 1,
                    'ui' => 0,
			        'ajax' => 0,
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_parking',
                    'label' => __( 'Parking', 'rentiq' ),
                    'name' => 'reservation_parking',
                    'type' => 'taxonomy',
                    'field_type' => 'select',
                    'taxonomy' => 'parking',
                    'load_save_terms' => 1,
                    'add_term' => 0,
                    'allow_null' => 1,
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'parking',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_apartment_price',
                    'label' => __( 'Apartment price', 'rentiq' ),
                    'name' => 'reservation_apartment_price',
                    'type' => 'number',
                    'prepend' => '$',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_parking_price',
                    'label' => __( 'Parking price', 'rentiq' ),
                    'name' => 'reservation_parking_price',
                    'type' => 'number',
                    'prepend' => '$',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'parking',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_contract_type',
                    'label' => __( 'Contract type', 'rentiq' ),
                    'name' => 'reservation_contract_type',
                    'type' => 'select',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_contract_length',
                    'label' => __( 'Contract length', 'rentiq' ),
                    'name' => 'reservation_contract_length',
                    'type' => 'select',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_date_from',
                    'label' => __( 'Date from', 'rentiq' ),
                    'name' => 'reservation_date_from',
                    'type' => 'date_picker',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_reservation_parking_img',
                    'label' => __( 'Parking picture', 'becko' ),
                    'name' => 'reservation_parking_img',
                    'type' => 'image',
                    'return_format' => 'url',
                    'library' => 'uploadedTo',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'parking',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_parking_plate',
                    'label' => __( 'Plate number', 'rentiq' ),
                    'name' => 'reservation_parking_plate',
                    'type' => 'text',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'parking',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_agent',
                    'label' => __( 'Agent', 'rentiq' ),
                    'name' => 'reservation_agent',
                    'type' => 'select',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_internet',
                    'label' => __( 'Internet option', 'rentiq' ),
                    'name' => 'reservation_internet',
                    'type' => 'select',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_internet_custom_price',
                    'label' => __( 'Custom internet price', 'rentiq' ),
                    'name' => 'reservation_internet_custom_price',
                    'type' => 'number',
                    'prepend' => '$',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_internet',
                                'operator' => '==',
                                'value' => 'custom-net',
                            ),
                        )
                    ),
                ),
                array (
                    'key' => 'field_reservation_tv',
                    'label' => __( 'TV', 'rentiq' ),
                    'name' => 'reservation_tv',
                    'type' => 'radio',
                    'choices' => array(
                        'no'	=> 'No',
                        'yes'	=> 'Yes',
                    ),
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_housekeeping',
                    'label' => __( 'Housekeeping', 'rentiq' ),
                    'name' => 'reservation_housekeeping',
                    'type' => 'radio',
                    'choices' => array(
                        'no'	=> 'No',
                        'yes'	=> 'Yes',
                    ),
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_deposit_count',
                    'label' => __( 'Number of deposits', 'rentiq' ),
                    'name' => 'reservation_deposit_count',
                    'type' => 'select',
                    'choices' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                    ),
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_deposit_utility',
                    'label' => __( 'Utility deposit amount', 'rentiq' ),
                    'name' => 'reservation_deposit_utility',
                    'type' => 'number',
                    'prepend' => '$',
                    'default_value' => get_field('utility_deposit_price', 'option'),
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_entry_cards',
                    'label' => __( 'How many entry cards?', 'rentiq' ),
                    'name' => 'reservation_entry_cards',
                    'type' => 'select',
                    'choices' => array( '0'	=> '0','1'	=> '1','2'	=> '2','3'	=> '3','4'	=> '4','5'	=> '5' ),
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_water_value',
                    'label' => __( 'Water value', 'rentiq' ),
                    'instructions' => __( 'At the beginning of contract', 'rentiq' ),
                    'name' => 'reservation_water_value',
                    'type' => 'number',
                    'prepend' => 'm2',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_electricity_value',
                    'label' => __( 'Electricity value', 'rentiq' ),
                    'instructions' => __( 'At the beginning of contract', 'rentiq' ),
                    'name' => 'reservation_electricity_value',
                    'type' => 'number',
                    'prepend' => 'kW',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),

                // tenant tab //
                array(
                    'key' => 'field_reservation_tab_tenant',
                    'label' => __( 'Tenant', 'rentiq' ),
                    'name' => 'reservation_tab_tenant',
                    'type' => 'tab',
                    'endpoint' => 0,
                    'icon_class' => 'accordions-vCard',
			        'show_icon_only' => 0,
                ),
                array (
                    'key' => 'field_reservation_tenant_type',
                    'label' => __( 'Tenant or owner as tenant?', 'rentiq' ),
                    'name' => 'reservation_tenant_type',
                    'type' => 'radio',
                    'choices' => array(
                        'tenant' => __( 'Tenant', 'rentiq' ),
                        'owner' => __( 'Owner', 'rentiq' ),
                    ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_reservation_tenant_name',
                    'label' => __( 'Tenant name', 'rentiq' ),
                    'name' => 'reservation_tenant_name',
                    'type' => 'text',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_reservation_tenant_country',
                    'label' => __( 'Tenant nationality', 'rentiq' ),
                    'name' => 'reservation_tenant_country',
                    'return_format' => 'label',
                    'type' => 'country',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_tenant_type',
                                'operator' => '==',
                                'value' => 'tenant',
                            ),
                        ),
                    ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_reservation_tenant_birthdate',
                    'label' => __( 'Tenant date of birth', 'rentiq' ),
                    'name' => 'reservation_tenant_birthdate',
                    'type' => 'date_picker',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_tenant_type',
                                'operator' => '==',
                                'value' => 'tenant',
                            ),
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_reservation_tenant_email',
                    'label' => __( 'Tenant contact', 'rentiq' ),
                    'name' => 'reservation_tenant_email',
                    'type' => 'text',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_tenant_type',
                                'operator' => '==',
                                'value' => 'tenant',
                            ),
                        ),
                    ),
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_reservation_tenant_phone',
                    'label' => __( 'Tenant phone', 'rentiq' ),
                    'name' => 'reservation_tenant_phone',
                    'type' => 'intl_tel_input',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_tenant_type',
                                'operator' => '==',
                                'value' => 'tenant',
                            ),
                        ),
                    ),
                    'prefix' => 'rentiq_',
                ),

                
                // additional expenses //
                array(
                    'key' => 'field_reservation_tab_expenses',
                    'label' => __( 'Energies & Expenses', 'rentiq' ),
                    'name' => 'reservation_tab_expenses',
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                    'icon_class' => 'accordions-price-tag',
			        'show_icon_only' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_reservation-energies',
                    'label' => __( 'Monthly water & electricity log', 'rentiq' ),
                    'name' => 'reservation-energies',
                    'prefix' => 'rentiq_',
                    'type' => 'repeater',
                    'sub_fields'   => array(
                        array (
                            'key' => 'field_reservation-monthly_energies_date',
                            'label' => __( 'Date', 'rentiq' ),
                            'name' => 'reservation-monthly_energies_date',
                            'type' => 'date_picker',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_reservation-monthly_water_value',
                            'label' => __( 'Water value', 'rentiq' ),
                            'name' => 'reservation-monthly_water_value',
                            'type' => 'number',
                            'prepend' => 'm2',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_reservation-monthly_electricity_value',
                            'label' => __( 'Electricity value', 'rentiq' ),
                            'name' => 'reservation-monthly_electricity_value',
                            'type' => 'number',
                            'prepend' => 'kW',
                            'prefix' => 'rentiq_',
                        ),
                    ),
                    'button_label' => __( 'Add monthly log', 'rentiq' ),
                ),
                array(
                    'key' => 'field_reservation-expenses',
                    'label' => __( 'Additional expenses', 'rentiq' ),
                    'name' => 'reservation-expenses',
                    'prefix' => 'rentiq_',
                    'type' => 'repeater',
                    'sub_fields'   => array(
                        array (
                            'key' => 'field_reservation-expense_date',
                            'label' => __( 'Date', 'rentiq' ),
                            'name' => 'reservation-expense_date',
                            'type' => 'date_picker',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_reservation-expense_name',
                            'label' => __( 'Expense name', 'rentiq' ),
                            'name' => 'reservation-expense_name',
                            'type' => 'text',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_reservation-expense_price',
                            'label' => __( 'Expense price', 'rentiq' ),
                            'name' => 'reservation-expense_price',
                            'type' => 'number',
                            'prepend' => '$',
                            'prefix' => 'rentiq_',
                        ),
                    ),
                    'button_label' => __( 'Add expense', 'rentiq' ),
                ),

                // termination fields //
                array(
                    'key' => 'field_reservation_tab_termination',
                    'label' => __( 'Termination', 'rentiq' ),
                    'name' => 'reservation_tab_termination',
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                    'icon_class' => 'accordions-close-filled',
			        'show_icon_only' => 0,
                ),
                array (
                    'key' => 'field_termination_date',
                    'label' => __( 'Termination date', 'rentiq' ),
                    'name' => 'termination_date',
                    'type' => 'date_picker',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_termination_water_value',
                    'label' => __( 'Water value', 'rentiq' ),
                    'instructions' => __( 'At the end of contract', 'rentiq' ),
                    'name' => 'termination_water_value',
                    'type' => 'number',
                    'prepend' => 'm2',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_termination_electricity_value',
                    'label' => __( 'Electricity value', 'rentiq' ),
                    'instructions' => __( 'At the end of contract', 'rentiq' ),
                    'name' => 'termination_electricity_value',
                    'type' => 'number',
                    'prepend' => 'kW',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_termination_leaving_early',
                    'label' => __( 'Leaving early', 'rentiq' ),
                    'instructions' => __( 'If checked, the deposit will not be returned.', 'rentiq' ),
                    'name' => 'termination_leaving_early',
                    'type' => 'checkbox',
                    'choices' => array(
                        'yes' => __( 'Yes', 'rentiq' ),
                    ),
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_termination-expenses',
                    'label' => __( 'Termination expenses', 'rentiq' ),
                    'name' => 'termination-expenses',
                    'prefix' => 'rentiq_',
                    'type' => 'repeater',
                    'sub_fields'   => array(
                        array (
                            'key' => 'field_termination-expense_name',
                            'label' => __( 'Expense name', 'rentiq' ),
                            'name' => 'termination-expense_name',
                            'type' => 'text',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_termination-expense_price',
                            'label' => __( 'Expense price', 'rentiq' ),
                            'name' => 'termination-expense_price',
                            'type' => 'number',
                            'prepend' => '$',
                            'prefix' => 'rentiq_',
                        ),
                    ),
                    'button_label' => __( 'Add expense', 'rentiq' ),
                ),
                array(
                    'key' => 'field_settlement-expenses',
                    'label' => __( 'Settlement expenses', 'rentiq' ),
                    'name' => 'settlement-expenses',
                    'prefix' => 'rentiq_',
                    'type' => 'repeater',
                    'sub_fields'   => array(
                        array (
                            'key' => 'field_settlement-expense_name',
                            'label' => __( 'Expense name', 'rentiq' ),
                            'name' => 'settlement-expense_name',
                            'type' => 'text',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_settlement-expense_desc',
                            'label' => __( 'Expense description', 'rentiq' ),
                            'name' => 'settlement-expense_desc',
                            'type' => 'text',
                            'prefix' => 'rentiq_',
                        ),
                        array (
                            'key' => 'field_settlement-expense_price',
                            'label' => __( 'Expense price', 'rentiq' ),
                            'name' => 'settlement-expense_price',
                            'type' => 'number',
                            'prepend' => '$',
                            'prefix' => 'rentiq_',
                        ),
                    ),
                    'button_label' => __( 'Add expense', 'rentiq' ),
                ),

                // files //
                array(
                    'key' => 'field_reservation_tab_files',
                    'label' => __( 'Contract & invoices', 'rentiq' ),
                    'name' => 'reservation_tab_files',
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 0,
                    'icon_class' => 'accordions-attachment',
			        'show_icon_only' => 0,
                ),
                array(
                    'key' => 'field_reservation_documents_message',
                    'name' => 'reservation_documents_message',
                    'type' => 'message',
                    'wrapper' => array(
                        'class' => 'reservation_documents_message',
                    ),
                    'message' => __( 'Always save your reservation first before creating a document.', 'rentiq' ),
                    'new_lines' => 'wpautop',
                ),
                array (
                    'key' => 'field_reservation_create_contract',
                    'name' => 'reservation_create_contract',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-contract_tenant',
                    'button_value' => __( 'Create contract', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-book-alt',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_contract_date',
                    'label' => __( 'Contract date', 'rentiq' ),
                    'name' => 'reservation_create_contract_date',
                    'type' => 'date_picker',
                    'placeholder' => __( 'Contract date', 'rentiq' ),
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-contract_tenant',
                        'class' => 'document_date',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_contract_tenant_post',
                    'label' => __( 'Contract', 'rentiq' ),
                    'name' => 'reservation_contract_tenant_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'contract_tenant_post',
                        'class' => 'reservation_documents',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_file_separator_0',
                    'name' => 'reservation_file_separator_0',
                    'type' => 'separator',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_extension_from',
                    'label' => __( 'Extension from', 'rentiq' ),
                    'name' => 'reservation_create_extension_from',
                    'type' => 'date_picker',
                    'placeholder' => 'from',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-extension_from',
                        'class' => 'extension_date',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_extension_till',
                    'label' => __( 'Extension till', 'rentiq' ),
                    'name' => 'reservation_create_extension_till',
                    'type' => 'date_picker',
                    'placeholder' => 'till',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-extension_till',
                        'class' => 'extension_date',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_contract_extension',
                    'name' => 'reservation_create_contract_extension',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-contract_extension',
                    'button_value' => __( 'Create contract extension', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-book-alt',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_contract_extension_date',
                    'label' => __( 'Contract extension date', 'rentiq' ),
                    'name' => 'reservation_create_contract_extension_date',
                    'type' => 'date_picker',
                    'placeholder' => __( 'Contract extension date', 'rentiq' ),
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-contract_extension',
                        'class' => 'document_date',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_contract_extension_post',
                    'label' => __( 'Contract extensions', 'rentiq' ),
                    'name' => 'reservation_contract_extension_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'contract_extension_post',
                        'class' => 'reservation_documents',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                /*array (
                    'key' => 'field_reservation_signed_contract_file',
                    'label' => __( 'Signed contract', 'rentiq' ),
                    'name' => 'reservation_signed_contract_file',
                    'type' => 'file',
                    'library' => 'uploadedTo',
                    //'mime_types' => 'pdf',
                    'prefix' => 'rentiq_',
                ),*/
                array (
                    'key' => 'field_reservation_file_separator_1',
                    'name' => 'reservation_file_separator_1',
                    'type' => 'separator',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_deposit_invoice',
                    'name' => 'reservation_create_deposit_invoice',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-invoice_deposit',
                    'button_value' => __( 'Create deposit invoice', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-lightbulb',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_deposit_invoice_date',
                    'label' => __( 'Deposit invoice date', 'rentiq' ),
                    'name' => 'reservation_create_deposit_invoice_date',
                    'type' => 'date_picker',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_deposit',
                        'class' => 'document_date',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_invoice_deposit_post',
                    'label' => __( 'Deposit invoice', 'rentiq' ),
                    'name' => 'reservation_invoice_deposit_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'invoice_deposit_post',
                        'class' => 'reservation_documents',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_file_separator_2',
                    'name' => 'reservation_file_separator_2',
                    'type' => 'separator',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_monthly_invoice',
                    'name' => 'reservation_create_monthly_invoice',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-invoice_rental',
                    'button_value' => __( 'Create new monthly invoice', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-backup',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_monthly_invoice_date',
                    'label' => __( 'Monthly rental invoice date', 'rentiq' ),
                    'name' => 'reservation_create_monthly_invoice_date',
                    'type' => 'date_picker',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_rental',
                        'class' => 'document_date',
                    ),
                ),
                array (
                    'key' => 'field_reservation_invoice_rental_post',
                    'label' => __( 'Rental invoices', 'rentiq' ),
                    'name' => 'reservation_invoice_rental_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'invoice_rental_post',
                        'class' => 'reservation_documents',
                    ),
                ),
                array (
                    'key' => 'field_reservation_file_separator_3',
                    'name' => 'reservation_file_separator_3',
                    'type' => 'separator',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_termination_button',
                    'name' => 'termination_button',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-invoice_deporeturn',
                    'button_value' => __( 'Create termination invoice', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-migrate',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_invoice_deporeturn_date',
                    'label' => __( 'Termination date', 'rentiq' ),
                    'name' => 'reservation_create_invoice_deporeturn_date',
                    'type' => 'date_picker',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_deporeturn',
                        'class' => 'document_date',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_invoice_deporeturn_post',
                    'label' => __( 'Termination invoice', 'rentiq' ),
                    'name' => 'reservation_invoice_deporeturn_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'invoice_deporeturn_post',
                        'class' => 'reservation_documents',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_file_separator_6',
                    'name' => 'reservation_file_separator_6',
                    'type' => 'separator',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_settlement_button',
                    'name' => 'settlement_button',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-invoice_settlement',
                    'button_value' => __( 'Create settlement invoice', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-migrate',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_invoice_settlement_date',
                    'label' => __( 'Settlement date', 'rentiq' ),
                    'name' => 'reservation_create_invoice_settlement_date',
                    'type' => 'date_picker',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_settlement',
                        'class' => 'document_date',
                    ),
                ),
                array (
                    'key' => 'field_reservation_invoice_settlement_post',
                    'label' => __( 'Settlement invoice', 'rentiq' ),
                    'name' => 'reservation_invoice_settlement_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'invoice_settlement_post',
                        'class' => 'reservation_documents',
                    ),
                ),
                array (
                    'key' => 'field_reservation_file_separator_4',
                    'name' => 'reservation_file_separator_4',
                    'type' => 'separator',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_agent_button',
                    'name' => 'agent_button',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-invoice_agent',
                    'button_value' => __( 'Create agent commission invoice', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-admin-users',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_create_invoice_agent_date',
                    'label' => __( 'Agent invoice date', 'rentiq' ),
                    'name' => 'reservation_create_invoice_agent_date',
                    'type' => 'date_picker',
                    'prefix' => 'rentiq_',
                    'hide_label' => true,
                    'wrapper' => array (
                        'id' => 'date-invoice_agent',
                        'class' => 'document_date',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_invoice_agent_post',
                    'label' => __( 'Agent invoice', 'rentiq' ),
                    'name' => 'reservation_invoice_agent_post',
                    'type' => 'read_only',
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'invoice_agent_post',
                        'class' => 'reservation_documents',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_reservation_file_separator_5',
                    'name' => 'reservation_file_separator_5',
                    'type' => 'separator',
                    'prefix' => 'rentiq_',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                array (
                    'key' => 'field_emptyinvoice_button',
                    'name' => 'emptyinvoice_button',
                    'type' => 'acfe_button',
                    'button_id' => 'bt-invoice_ownerempty',
                    'button_value' => __( 'Create empty period owner invoice', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-secondary dashicons-before dashicons-controls-pause',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'class' => 'document_button',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),
                /*
                array (
                    'key' => 'field_reservation_create_invoice_ownerempty_date',
                    'label' => __( 'Empty period invoice date', 'rentiq' ),
                    'name' => 'reservation_create_invoice_ownerempty_date',
                    'type' => 'date_picker',
                    'hide_label' => true,
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'date-invoice_ownerempty',
                        'class' => 'document_date',
                    ),
                ),*/
                array (
                    'key' => 'field_reservation_invoice_ownerempty_post',
                    'label' => __( 'Empty period owner invoice', 'rentiq' ),
                    'name' => 'reservation_invoice_ownerempty_post',
                    'type' => 'read_only',
                    'instructions' => __( 'This invoice values will be based on water & electricity values inserted here on beginning of the contract, water & electricity values at termination of the last reservation, and the additional expenses added on the Apartment page.', 'rentiq' ),
                    'display_type' => 'text',
                    'prefix' => 'rentiq_',
                    'wrapper' => array (
                        'id' => 'invoice_ownerempty_post',
                        'class' => 'reservation_documents',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_reservation_type',
                                'operator' => '==',
                                'value' => 'apartment',
                            ),
                        ),
                    ),
                ),

                // save reservation //
                array(
                    'key' => 'field_reservation_tab_save',
                    'label' => __( 'Contract and invoices', 'rentiq' ),
                    'name' => 'reservation_tab_save',
                    'type' => 'tab',
                    'placement' => 'top',
                    'endpoint' => 1,
                    'icon_class' => 'accordions-computer-disk-save',
			        'show_icon_only' => 1,
                ),
                array(
                    'key' => 'field_reservation_status',
                    'label' => __( 'Reservation status', 'rentiq' ),
                    'name' => 'reservation_status',
                    'type' => 'acfe_post_statuses',
                    'post_status' => array(
                        0 => 'draft',
                        1 => 'signed',
                        2 => 'terminated',
                    ),
                    'field_type' => 'select',
                    'return_format' => 'object',
                    'multiple' => 0,
                    'ui' => 0,
                    'choices' => array(
                    ),
                    'toggle' => 0,
                    'allow_custom' => 1,
                ),
                array(
                    'key' => 'field_reservation_save',
                    'label' => __( 'Save reservation', 'rentiq' ),
                    'name' => 'reservation_save',
                    'type' => 'acfe_button',
                    'button_value' => __( 'Save reservation', 'rentiq' ),
                    'button_type' => 'button',
                    'button_class' => 'button button-primary',
                    'button_id' => '',
                    'button_ajax' => 0,
                ),

            ),
            'location' => array (
                array (
                    array (
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'reservation',
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
add_action('acf/init', 'rentiq_reservation_fields');


//// add internet options choices ////

function acf_load_internet_options_field_choices( $field ) {
    
    $field['choices'] = array();

    if( have_rows('internet_options', 'option') ) {
        
        while( have_rows('internet_options', 'option') ) {

            the_row();
            $value = get_sub_field('internet_option_slug');
            $label = get_sub_field('internet_option_name') . ' - ' . get_sub_field('internet_option_price') . '$';
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
    }
    return $field;
}
add_filter('acf/load_field/name=reservation_internet', 'acf_load_internet_options_field_choices');


//// add contract length choices ////

function acf_load_contract_length_options_field_choices( $field ) {
    
    $field['choices'] = array();

    if( have_rows('contract_lengths', 'option') ) {
        
        while( have_rows('contract_lengths', 'option') ) {

            the_row();
            $value = get_sub_field('contract_length');
            $label = get_sub_field('contract_length') . ' ' . __( 'Months', 'rentiq' ) ;
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
    }
    return $field;
}
add_filter('acf/load_field/name=reservation_contract_length', 'acf_load_contract_length_options_field_choices');


//// add contract type choices ////

function acf_load_contract_options_field_choices( $field ) {
    
    $field['choices'] = array();

    $contract_types = get_posts( array(
        'post_type'      => 'contract_type',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ));
     
    if ( $contract_types ) {
        foreach ( $contract_types as $post ) {

            // append to choices
            $field['choices'][ $post->ID ] = $post->post_title;
            
        }
    }
    return $field;
}
add_filter('acf/load_field/name=reservation_contract_type', 'acf_load_contract_options_field_choices');


//// add agent choices ////

function acf_load_agent_choices( $field ) {
    
    $field['choices'] = array();

    if( have_rows('agents', 'option') ) {
        
        while( have_rows('agents', 'option') ) {

            the_row();
            $value = get_sub_field('agent_id');
            $label = get_sub_field('agent_name');
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
    }
    return $field;
}
add_filter('acf/load_field/name=reservation_agent', 'acf_load_agent_choices');


//// setup boxes ////

function reservation_move_meta_boxes() {

    remove_meta_box( 'authordiv', 'reservation', 'normal' );
    remove_meta_box( 'submitdiv', 'reservation', 'side' );
    remove_meta_box( 'tagsdiv-owner', 'reservation', 'side' );
    remove_meta_box( 'tagsdiv-apartment', 'reservation', 'side' );
    remove_meta_box( 'tagsdiv-parking', 'reservation', 'side' );

}
add_action('do_meta_boxes', 'reservation_move_meta_boxes');


// disable acfe author box //

function rentiq_acfe_modules(){
    acfe_update_setting('modules/author', false); 
}
add_action('acfe/init', 'rentiq_acfe_modules');

/*
function reservation_one_column_for_all( $order ) {
    return array(
        'normal' => join( ",", array(
            'acf-reservation_fields',
            //'submitdiv',
        ) ),
        'side'     => '',
        'advanced' => '',
    );
}
add_filter( 'get_user_option_meta-box-order_reservation', 'reservation_one_column_for_all' );
*/

$onecolumnfunc = function( $result, $option, $user ){
    return '1';
};
add_filter( "get_user_option_screen_layout_reservation", $onecolumnfunc, 10, 3 );



//// auto generate titles ////

function rentiq_save_reservation_title( $post_id, $post, $update ) {
     
    $post_type = 'reservation';
     
    if ( $post_type == get_post_type ( $post_id ) ) {

        if ( $post->post_status !== 'trash' ) {
            $post_status = get_post_meta( $post_id, 'reservation_status', true );
        } else {
            $post_status = 'trash';
        }
         
        $tenant_name = get_post_meta( $post_id, 'reservation_tenant_name', true );
        $owners = Rentiq_class::get_reservation_owners( $post_id );

        $my_post = array(
            'ID'         => $post_id,
            'post_title' => $tenant_name,
            'post_status'=> $post_status,
            'tax_input'  => array(
                'owner' => $owners,
            ),
        );
        remove_action('save_post', 'rentiq_save_reservation_title'); //Avoid the infinite loop
        wp_update_post( $my_post );     
         
    }
}
add_action( 'save_post', 'rentiq_save_reservation_title', 10,3 );



//// load documents ////

$document_types = array( 'contract_tenant', 'contract_extension', 'invoice_deposit', 'invoice_rental', 'invoice_deporeturn', 'invoice_settlement', 'invoice_agent', 'invoice_ownerempty' );
foreach( $document_types as $document_type ) {

    add_filter( 'acf/load_field/name=reservation_'.$document_type.'_post', function( $field ) use ( $document_type ) {
    
        $field['value'] = Rentiq_class::show_attached_documents( $document_type, get_the_ID(), 'meta' );
        return $field;

    });
}