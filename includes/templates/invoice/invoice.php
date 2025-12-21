<?php

$html  = '<head><link rel="stylesheet" href="'. RENTIQ_PATH . 'includes/templates/'. $template_type .'/' . 'style.css"><title>' . $document_title . '</title></head><body>
            <header class="clearfix">';
if ( file_exists($company_logo) ) {
    $html .=        '<div id="logo">
                        <img src="' . $company_logo . '"/>
                    </div>';
}
if ( $document_type == 'invoice_owner_payout' ) {
    $heading_tenant = $company_name;
} else if ( $document_type == 'invoice_ownerempty' ) {
    $heading_tenant = $tenant_name . ' - #' . $apartment_number;
} else {
    $heading_tenant = $tenant_name;
}

if ( in_array( $document_type, array('invoice_rental', 'invoice_deposit', 'invoice_deporeturn') ) ) {
    $qr_code = '';
    //$qr_code = '<img class="qrcode" src="' . RENTIQ_PATH . 'assets/img/QR_payment_small.png">';
} else {
    $qr_code = '';
}

//// header ////

$html .=        '<h1>' . $heading_tenant . ' - ' . $invoice_name . '</h1>
                <div id="project">
                    <table>
                        <tr><td>' . __( 'CLIENT', 'rentiq' ) . '</td><td width="65%"></td></tr>
                        <tr><td><span>' . __( 'NAME', 'rentiq' ) . '</span></td><td>' . $tenant_name . '</td></tr>
                        <tr><td><span>' . __( 'ADDRESS', 'rentiq' ) . '</span></td><td>' . $tenant_country . '</td></tr>
                        <tr><td><span>' . __( 'EMAIL', 'rentiq' ) . '</span></td><td> <a href="mailto:' . $tenant_email . '">' . $tenant_email . '</a></td></tr>
                        <tr><td><span>' . __( 'DATE', 'rentiq' ) . '</span></td><td>' . $invoice_date . '</td></tr>
                        <tr><td><span>' . __( 'DOCUMENT NUMBER', 'rentiq' ) . '</span></td><td>' . $document_number . '</td></tr>
                    </table>
                </div>
                <div id="company">
                    <table>
                        <tr><td width="65%"></td><td>' . __( 'COMPANY', 'rentiq' ) . '</td></tr>
                        <tr><td>' . $company_name . '</td><td><span>' . __( 'NAME', 'rentiq' ) . '</span></td></tr>
                        <tr><td>' . $company_address . '</td><td><span>' . __( 'ADDRESS', 'rentiq' ) . '</span></td></tr>
                        <tr><td>' . $company_phone . '</td><td><span>' . __( 'PHONE', 'rentiq' ) . '</span></td></tr>
                        <tr><td><a href="mailto:' . $company_email . '">' . $company_email . '</a></td><td><span>' . __( 'EMAIL', 'rentiq' ) . '</span></td></tr>
                        <tr><td>' . $company_account . '</td><td><span>' . __( 'BANK ACCOUNT', 'rentiq' ) . '</span></td></tr>
                    </table>
                </div>
            </header>
            <main>
                <table id="prices">
                <thead>
                    <tr>
                        <th class="service">' . __( 'SERVICE', 'rentiq' ) . '</th>
                        <th class="desc">' . __( 'DESCRIPTION', 'rentiq' ) . '</th>
                        <th>' . __( 'PRICE', 'rentiq' ) . '</th>
                    </tr>
                </thead>
                <tbody>';

//// items ////

foreach ( $invoice_items as $item ) {

    $show_item = false;

    if ( is_numeric( $item['price'] ) ) {
        $item_price = number_format( $item['price'], 2 ) . $currency;
        if ( $item['price'] && $item['price'] !== 0 ) {
            $show_item = true;
        }
    } else {
        $item_price = $item['price'];
        $show_item = true;
    }

    if ( $show_item == true ) {

        $html .=        '<tr>
                            <td class="service">'. $item['name'] .'</td>
                            <td class="desc">'. $item['description'] .'</td>
                            <td class="total">'. $item_price . '</td>
                        </tr>';
    }
}

//// totals and footer ////
                
if ( $invoice_tax != '0' && $add_tax == true ) {
    $html .=            '<tr>
                            <td colspan="2">' . __( 'SUBTOTAL', 'rentiq' ) . '</td>
                            <td class="total">' . number_format( $invoice_total, 2 ) . $currency .  '</td>
                        </tr>
                        <tr>
                            <td colspan="2">' . __( 'TAX', 'rentiq' ) . '</td>
                            <td class="total">' . number_format( $invoice_tax, 2 ) . $currency . '</td>
                        </tr>';
}
$html .=            '<tr>
                        <td colspan="2" class="grand total">' . __( 'GRAND TOTAL', 'rentiq' ) . '</td>
                        <td class="grand total">' . number_format( $invoice_grand, 2 ) . $currency .  '</td>
                    </tr>
                </tbody>
                </table>
                <div id="notices">
                    <div class="notice">' . get_field( 'invoice_notice', 'option' ) . '</div>
                </div>
            </main>' . $qr_code . '
            <footer>' . get_field( 'invoice_footer', 'option' ) . '</footer>';
$html .= '</body>';