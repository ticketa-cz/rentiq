<?php 

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//// contract type fields ////

function contract_type_fields() {

    if( function_exists('acf_add_local_field_group') ) {

        acf_add_local_field_group( array (
            'key' => 'contract_type_fields',
            'title' => __( 'Contract type setting', 'rentiq' ),
            'fields' => array (
                /*
                array (
                    'key' => 'field_contract_english',
                    'label' => __( 'Contract in ENGLISH', 'rentiq' ),
                    'name' => 'contract_english',
                    'type' => 'wysiwyg',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_contract_khmer',
                    'label' => __( 'Contract in KHMER', 'rentiq' ),
                    'name' => 'contract_khmer',
                    'type' => 'wysiwyg',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_contract_chinese',
                    'label' => __( 'Contract in CHINESE', 'rentiq' ),
                    'name' => 'contract_chinese',
                    'type' => 'wysiwyg',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_contract_length',
                    'label' => __( 'Length of contract', 'rentiq' ),
                    'name' => 'contract_length',
                    'type' => 'number',
                    'prepend' => 'months',
                    'prefix' => 'rentiq_',
                ),*/
                array (
                    'key' => 'field_contract_commision_agent',
                    'label' => __( 'Commision agent', 'rentiq' ),
                    'name' => 'contract_commision_agent',
                    'type' => 'number',
                    'prepend' => '%',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_contract_commision_landlord',
                    'label' => __( 'Commision landlord', 'rentiq' ),
                    'name' => 'contract_commision_landlord',
                    'type' => 'number',
                    'prepend' => '%',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_contract_commision_manager',
                    'label' => __( 'Commision manager', 'rentiq' ),
                    'name' => 'contract_commision_manager',
                    'type' => 'number',
                    'prepend' => '%',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_contract_deposit_fund',
                    'label' => __( 'Deposit to fund', 'rentiq' ),
                    'name' => 'contract_deposit_fund',
                    'type' => 'number',
                    'prepend' => 'month rent',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_contract_logo',
                    'label' => __( 'Logo for the contract', 'rentiq' ),
                    'name' => 'contract_logo',
                    'type' => 'image',
                    'instructions' => __( '300 x 300 px', 'rentiq' ),
                    'prefix' => 'rentiq_',
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'contract_type',
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
add_action('acf/init', 'contract_type_fields');