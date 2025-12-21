<?php 

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//// expenses fields ////

function expenses_fields() {

    if( function_exists('acf_add_local_field_group') ) {

        acf_add_local_field_group( array (
            'key' => 'expenses_fields',
            'title' => __( 'Expense data', 'rentiq' ),
            'fields' => array (
                array (
                    'key' => 'field_expense_category',
                    'label' => __( 'Category', 'rentiq' ),
                    'name' => 'expense_category',
                    'type' => 'taxonomy',
                    'taxonomy' => 'expensecats',
                    'field_type' => 'radio',
                    'add_term' => 0,
                    'load_save_terms' => 1,
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_expense_price',
                    'label' => __( 'Price', 'rentiq' ),
                    'name' => 'expense_price',
                    'type' => 'number',
                    'prepend' => '$',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_expense_date',
                    'label' => __( 'Date', 'rentiq' ),
                    'name' => 'expense_date',
                    'type' => 'date_picker',
                    'prefix' => 'rentiq_',
                ),
                array (
                    'key' => 'field_expense_file',
                    'label' => __( 'PDF of the bill', 'rentiq' ),
                    'name' => 'expense_file',
                    'type' => 'file',
                    'prefix' => 'rentiq_',
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'expenses',
                        ),
                    ),
                ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'permalink',
                1 => 'block_editor',
                2 => 'the_content',
                3 => 'excerpt',
                4 => 'discussion',
                5 => 'comments',
                6 => 'revisions',
                7 => 'slug',
                8 => 'author',
                9 => 'format',
                10 => 'page_attributes',
                11 => 'featured_image',
                12 => 'categories',
                13 => 'tags',
                14 => 'send-trackbacks',
            ),
            'active' => 1,
            'description' => '',
        ));
    }
    
}
add_action('acf/init', 'expenses_fields');

//// setup boxes ////

function expenses_move_meta_boxes() {

    remove_meta_box( 'authordiv', 'expenses', 'normal' );
    remove_meta_box( 'tagsdiv-expensecats', 'expenses', 'side' );

}
add_action('do_meta_boxes', 'expenses_move_meta_boxes');