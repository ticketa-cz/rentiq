<?php 

//// reservation statuses ////

function rentiq_reservation_statuses() {

    register_post_status( 'signed', array(
        'label'                     => __( 'Signed', 'rentiq' ),
        'label_count'               => _n_noop( 'Signed <span class="count">(%s)</span>', 'Signed <span class="count">(%s)</span>'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true
    ));
    register_post_status( 'terminated', array(
        'label'                     => __( 'Terminated', 'rentiq' ),
        'label_count'               => _n_noop( 'Terminated <span class="count">(%s)</span>', 'Terminated <span class="count">(%s)</span>'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true
    ));
}
add_action( 'init', 'rentiq_reservation_statuses' );

function add_reservation_statuses_dropdown() {

    global $post;
    if($post->post_type != 'reservation')
    return false;

    $status_signed = ($post->post_status == 'signed') ? "jQuery( '#post-status-display' ).text( 'Signed' ); jQuery( 
    'select[name=\"post_status\"]' ).val('signed');" : '';
    echo "<script>
    jQuery(document).ready( function() {
    jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"signed\">Signed</option>' );
    ".$status_signed."
    });
    </script>";

    $status_terminated = ($post->post_status == 'terminated') ? "jQuery( '#post-status-display' ).text( 'Terminated' ); jQuery( 
    'select[name=\"post_status\"]' ).val('terminated');" : '';
    echo "<script>
    jQuery(document).ready( function() {
    jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"terminated\">Terminated</option>' );
    ".$status_terminated."
    });
    </script>";
}
add_action( 'post_submitbox_misc_actions', 'add_reservation_statuses_dropdown');


function display_reservation_archive_state( $states ) {

    global $post;
    $arg = get_query_var( 'post_status' );
    if($arg != 'signed'){
        if($post->post_status == 'signed'){
            echo "<script>
            jQuery(document).ready( function() {
            jQuery( '#post-status-display' ).text( 'Signed' );
            });
            </script>";
            return array('Signed');
        }
    }
    if($arg != 'terminated'){
        if($post->post_status == 'terminated'){
            echo "<script>
            jQuery(document).ready( function() {
            jQuery( '#post-status-display' ).text( 'Terminated' );
            });
            </script>";
            return array('Terminated');
        }
    }
    return $states;
}
add_filter( 'display_post_states', 'display_reservation_archive_state' );