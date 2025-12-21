<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class rentiq_setting_page {

    public function __construct()   {
        add_action('init', array( $this, 'register_setting_page'));
        add_action('init', array( $this, 'register_setting_fields'));
    }

    //// create setting pages ////

    public function register_setting_page ()  {

        if( function_exists('acf_add_options_page') ) {
            
            acf_add_options_page(array(
                'page_title' 	=> __( 'Rental general settings', 'rentiq' ),
                'menu_title'	=> __( 'Rental settings', 'rentiq' ),
                'menu_slug' 	=> 'rental-general-settings',
                'capability'	=> 'edit_posts',
                'position'      => '25',
                'icon_url'      => 'dashicons-admin-generic',
                'update_button' => __('Save options', 'rentiq'),
                'updated_message' => __("Options updated", 'rentiq'),
                'redirect'		=> false
            ));
            
        }
    }

    //// create setting fields ////

    public function register_setting_fields ()  {

        if( function_exists('acf_add_local_field_group') ) {

            acf_add_local_field_group( array (
                'key' => 'rentiq_setting_page',
                'title' => __( 'Rental settings', 'rentiq' ),
                'fields' => array (
                    array (
                        'key' => 'field_rentiq_setting-company_logo',
                        'label' => __( 'Company logo', 'rentiq' ),
                        'name' => 'company_logo',
                        'type' => 'image',
                        'prefix' => 'rentiq_',
                        'return_format' => 'url',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-company_name',
                        'label' => __( 'Company name', 'rentiq' ),
                        'name' => 'company_name',
                        'type' => 'text',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-company_address',
                        'label' => __( 'Company address', 'rentiq' ),
                        'name' => 'company_address',
                        'type' => 'text',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-company_phone',
                        'label' => __( 'Company phone', 'rentiq' ),
                        'name' => 'company_phone',
                        'type' => 'intl_tel_input',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-company_email',
                        'label' => __( 'Company email', 'rentiq' ),
                        'name' => 'company_email',
                        'type' => 'email',
                        'prefix' => 'rentiq_',
                    ),array (
                        'key' => 'field_rentiq_setting-company_bank_account',
                        'label' => __( 'Company bank account number', 'rentiq' ),
                        'name' => 'company_bank_account',
                        'type' => 'text',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-property_address',
                        'label' => __( 'Property address', 'rentiq' ),
                        'name' => 'property_address',
                        'type' => 'text',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-invoice_notice',
                        'label' => __( 'Invoice notice', 'rentiq' ),
                        'name' => 'invoice_notice',
                        'type' => 'text',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-invoice_footer',
                        'label' => __( 'Invoice footer text', 'rentiq' ),
                        'name' => 'invoice_footer',
                        'type' => 'text',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-entry_card_price',
                        'label' => __( 'Entry card deposit price', 'rentiq' ),
                        'name' => 'entry_card_price',
                        'type' => 'number',
                        'prepend' => '$',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-rental_tax',
                        'label' => __( 'Goverment rental tax', 'rentiq' ),
                        'name' => 'rental_tax',
                        'type' => 'number',
                        'prepend' => '%',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-rental_service_price',
                        'label' => __( 'Rental service price', 'rentiq' ),
                        'name' => 'rental_service_price',
                        'type' => 'number',
                        'prepend' => '%',
                        'prefix' => 'rentiq_',
                    ),
                    array(
                        'key' => 'field_rentiq_setting-apartment_types',
                        'label' => __( 'Apartment types', 'rentiq' ),
                        'name' => 'apartment_types',
                        'prefix' => 'rentiq_',
                        'instructions' => __( 'Slug is an specific ID without spaces and special characters, for example "apartment-name"', 'rentiq' ),
                        'type' => 'repeater',
                        'sub_fields'   => array(
                            array (
                                'key' => 'field_rentiq_setting-apartment_type_name',
                                'label' => __( 'Apartment name', 'rentiq' ),
                                'name' => 'apartment_type_name',
                                'type' => 'text',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-apartment_type_slug',
                                'label' => __( 'Apartment slug', 'rentiq' ),
                                'name' => 'apartment_type_slug',
                                'type' => 'text',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-apartment_type_size',
                                'label' => __( 'Apartment size', 'rentiq' ),
                                'name' => 'apartment_type_size',
                                'type' => 'number',
                                'prepend' => 'm2',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-apartment_type_housekeeping_fees',
                                'label' => __( 'Apartment housekeeping fees', 'rentiq' ),
                                'name' => 'apartment_type_housekeeping_fees',
                                'type' => 'number',
                                'prepend' => '$',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-apartment_type_sinkinkfund_fees',
                                'label' => __( 'Apartment sinkink fund fees', 'rentiq' ),
                                'name' => 'apartment_type_sinkinkfund_fees',
                                'type' => 'number',
                                'prepend' => '$',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-apartment_type_management_fees',
                                'label' => __( 'Apartment management fees', 'rentiq' ),
                                'name' => 'apartment_type_management_fees',
                                'type' => 'number',
                                'prepend' => '$',
                                'prefix' => 'rentiq_',
                            ),
                        ),
                        'button_label' => __( 'Add apartment type', 'rentiq' ),
                    ),
                    array(
                        'key' => 'field_rentiq_setting-internet_options',
                        'label' => __( 'Internet options', 'rentiq' ),
                        'name' => 'internet_options',
                        'instructions' => __( 'Slug is an specific ID without spaces and special characters, for example "option-name"', 'rentiq' ),
                        'prefix' => 'rentiq_',
                        'type' => 'repeater',
                        'sub_fields'   => array(
                            array (
                                'key' => 'field_rentiq_setting-internet_option_name',
                                'label' => __( 'Option name', 'rentiq' ),
                                'name' => 'internet_option_name',
                                'type' => 'text',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-internet_option_slug',
                                'label' => __( 'Internet option slug', 'rentiq' ),
                                'name' => 'internet_option_slug',
                                'type' => 'text',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-internet_option_price',
                                'label' => __( 'Option price', 'rentiq' ),
                                'name' => 'internet_option_price',
                                'type' => 'number',
                                'prepend' => '$',
                                'prefix' => 'rentiq_',
                            ),
                        ),
                        'button_label' => __( 'Add internet option', 'rentiq' ),
                    ),
                    array(
                        'key' => 'field_rentiq_setting-contract_lengths',
                        'label' => __( 'Contract lengths', 'rentiq' ),
                        'name' => 'contract_lengths',
                        'prefix' => 'rentiq_',
                        'type' => 'repeater',
                        'sub_fields'   => array(
                            array (
                                'key' => 'field_rentiq_setting-contract_length',
                                'label' => __( 'Length', 'rentiq' ),
                                'name' => 'contract_length',
                                'type' => 'number',
                                'prepend' => 'months',
                                'prefix' => 'rentiq_',
                            ),
                        ),
                        'button_label' => __( 'Add length', 'rentiq' ),
                    ),
                    array(
                        'key' => 'field_rentiq_setting-agents',
                        'label' => __( 'Agents', 'rentiq' ),
                        'name' => 'agents',
                        'prefix' => 'rentiq_',
                        'instructions' => __( 'Slug is an specific ID without spaces and special characters, for example "agent-name"', 'rentiq' ),
                        'type' => 'repeater',
                        'sub_fields'   => array(
                            array (
                                'key' => 'field_rentiq_setting-agent_name',
                                'label' => __( 'Name', 'rentiq' ),
                                'name' => 'agent_name',
                                'type' => 'text',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-agent_id',
                                'label' => __( 'Slug', 'rentiq' ),
                                'name' => 'agent_id',
                                'type' => 'text',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-agent_phone',
                                'label' => __( 'Phone', 'rentiq' ),
                                'name' => 'agent_phone',
                                'type' => 'intl_tel_input',
                                'prefix' => 'rentiq_',
                            ),
                            array (
                                'key' => 'field_rentiq_setting-agent_email',
                                'label' => __( 'Email', 'rentiq' ),
                                'name' => 'agent_email',
                                'type' => 'text',
                                'prefix' => 'rentiq_',
                            ),
                        ),
                        'button_label' => __( 'Add agent', 'rentiq' ),
                    ),
                    array (
                        'key' => 'field_rentiq_setting-tv_monthly_price',
                        'label' => __( 'TV monthly price', 'rentiq' ),
                        'name' => 'tv_monthly_price',
                        'type' => 'number',
                        'prepend' => '$',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-utility_deposit_price',
                        'label' => __( 'Utility deposit price', 'rentiq' ),
                        'name' => 'utility_deposit_price',
                        'type' => 'number',
                        'prepend' => '$',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-electricity_price',
                        'label' => __( 'Electricity price', 'rentiq' ),
                        'name' => 'electricity_price',
                        'instructions' => __( 'Price billed to the tenants', 'rentiq' ),
                        'type' => 'number',
                        'prepend' => '$ / kW',
                        'prefix' => 'rentiq_',
                    ),
                    array (
                        'key' => 'field_rentiq_setting-water_price',
                        'label' => __( 'Water price', 'rentiq' ),
                        'name' => 'water_price',
                        'instructions' => __( 'Price billed to the tenants', 'rentiq' ),
                        'type' => 'number',
                        'prepend' => '$ / m2',
                        'prefix' => 'rentiq_',
                    ),
                    /*
                    array (
                        'key' => 'field_rentiq_setting-yearly_owner_summary',
                        'name' => 'yearly_owner_summary_button',
                        'type' => 'acfe_button',
                        'button_id' => 'bt-yearly_owner_summary',
                        'button_value' => __( 'Create yearly invoices for all owners', 'rentiq' ),
                        'button_type' => 'button',
                        'button_class' => 'button button-secondary dashicons-before dashicons-backup',
                        'prefix' => 'rentiq_',
                        'wrapper' => array (
                            'class' => 'document_button',
                        ),
                    ),*/
                    array (
                        'key' => 'field_rentiq_setting-add_rent_prices',
                        'name' => 'add_rent_prices_button',
                        'type' => 'acfe_button',
                        'button_id' => 'bt-add_rent_prices',
                        'button_value' => __( 'Add rent prices', 'rentiq' ),
                        'button_type' => 'button',
                        'button_class' => 'button button-secondary dashicons-before dashicons-backup',
                        'prefix' => 'rentiq_',
                        'wrapper' => array (
                            'class' => 'document_button',
                        ),
                    ),/*
                    array (
                        'key' => 'field_rentiq_setting-yearly_owner_summary',
                        'label' => __( 'Date of yearly owner summary', 'rentiq' ),
                        'name' => 'yearly_owner_summary',
                        'type' => 'date_picker',
                        'prefix' => 'rentiq_',
                    ),*/
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'rental-general-settings',
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

}
$rentiq_setting = new rentiq_setting_page();