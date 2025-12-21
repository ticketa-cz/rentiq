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
} else if ( $document_type == 'invoice_owner' ) {
    $heading_tenant = $tenant_name . ' - ' . $next_year;
} else if ( $document_type == 'invoice_ownerempty' ) {
    $heading_tenant = $tenant_name . ' - #' . $apartment_number;
} else {
    $heading_tenant = $tenant_name;
}

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
                        <th class="service">' . __( 'PAYMENT', 'rentiq' ) . '</th>
                        <th class="desc">' . __( 'ON DATE', 'rentiq' ) . '</th>
                        <th>' . __( 'AMOUNT', 'rentiq' ) . '</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="service"><strong>'. __( 'Payments for invoice # ', 'rentiq' ) . $invoice_number . '</strong></td>
                    <td class="desc"><strong>'. __( 'Invoice total: ', 'rentiq' ) .'</strong></td>
                    <td class="total"><strong>'. number_format( $payment_invoice_total, 2 ) . $currency . '</strong></td>
                </tr>';

foreach ( $invoice_items as $item ) {

    if ( is_numeric( $item['price'] ) ) {
        $item_price = number_format( $item['price'], 2 ) . $currency;
    } else {
        $item_price = $item['price'];
    }

    $html .=        '<tr>
                        <td class="service">'. $item['name'] .'</td>
                        <td class="desc">'. Rentiq_class::rentiq_format_date( $item['description'] ) .'</td>
                        <td class="total">'. $item_price . '</td>
                    </tr>';
}
                
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
                        <td colspan="2" class="grand total">' . __( 'INVOICE BALANCE', 'rentiq' ) . '</td>
                        <td class="grand total">' . $invoice_balance .  '</td>
                    </tr>
                </tbody>
                </table>
                <div id="notices">
                    <div class="notice">' . get_field( 'invoice_notice', 'option' ) . '</div>
                </div>
            </main>
            <footer>' . get_field( 'invoice_footer', 'option' ) . '</footer>';
$html .= '</body>';