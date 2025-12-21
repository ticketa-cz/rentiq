<?php

$contract_content = get_the_content('', '', $contract_type_id );
$contract_with_id = str_replace( "rentiq_contract", "rentiq_contract reservation_id=".$reservation_id." document_id=".$document_id, $contract_content );

$html  = '<head><link rel="stylesheet" href="'. RENTIQ_PATH . '/includes/templates/'. $template_type .'/' . 'style.css"></head><body>';
$html .=  do_shortcode( $contract_with_id );
$html .= '</body>';