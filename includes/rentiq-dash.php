<?php 

// setup dashboard widgets //

function remove_dashboard_widgets() {

    global $wp_meta_boxes;
  
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

    wp_add_dashboard_widget('rentiq_income', 'Yearly incomes', 'rentiq_income_widget');
    wp_add_dashboard_widget('rentiq_income_monthly', 'Monthly incomes', 'rentiq_income_monthly_widget');
    wp_add_dashboard_widget('rentiq_expense', 'Monthly expenses', 'rentiq_expense_widget');
    wp_add_dashboard_widget('rentiq_availability', 'Availability', 'rentiq_availability_widget');

} 
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );



//// income statistics ////

function rentiq_income_widget() {

    $year_next = intval( date('Y', strtotime('+1 year')) );
    $year_min = intval( $year_next - 4 );
    $currency = ' $';

    $years_array = array();
    $incomes_management_array = array();
    $incomes_sinkink_array = array();

    $table = '<table class="stats_table">';
    $table .= '<thead><td>'.__( 'Year', 'rentiq' ).'</td><td>'.__( 'Management fees', 'rentiq' ).'</td><td>'.__( 'Sinking fund', 'rentiq' ).'</td><td>'.__( 'Total', 'rentiq' ).'</td></thead>';

    for ( $year = $year_next; $year >= $year_min; $year-- ) {

        $yearly_invoices = get_posts( array(
            'post_type'   => 'invoice_owner',
            'numberposts' => -1,
            'post_status' => 'any',        
            'meta_query' => array(
                array(
                'key'       => 'invoice_year',
                'value'     => $year,
                'compare'   => '='
                )
            ),
        ));

        $management_total = 0;
        $sinkink_total = 0;
        $invoices_total = 0;

        if ( $yearly_invoices ) {
            foreach ( $yearly_invoices as $invoice ) {
                $invoice_total_management = get_post_meta( $invoice->ID, 'management_total', true );
                $invoice_total_sinkink = get_post_meta( $invoice->ID, 'sinkink_total', true );

                if ( $invoice_total_management ) { $management_total += floatval( $invoice_total_management ); }
                if ( $invoice_total_sinkink ) { $sinkink_total += floatval( $invoice_total_sinkink ); }
                $invoices_total++;
            }
        }

        $years_array[] = $year;
        $incomes_management_array[] = $management_total;
        $incomes_sinkink_array[] = $sinkink_total;
        
        $table .= '<tr><td>'. $year .'</td><td>'. number_format( $management_total, 1 ) . $currency . '</td><td>'. number_format( $sinkink_total, 1 ) . $currency . '</td><td><strong>'. number_format( floatval( $management_total + $sinkink_total ), 1 ) . $currency . '</strong></td></tr>';
    }
    
    $table .= '</table>';


    //// graph ////

    $graph_incomes = '<div class="chart-container">
                        <canvas id="graphCanvas_incomes"></canvas>
                    </div>';

    $graph_incomes .= '<script>
                        jQuery(document).ready(function () {
                            showGraph_incomes();
                        });

                function showGraph_incomes() {
                    
                    var chartdata = {
                        labels: '. json_encode( array_reverse($years_array)) .',
                        datasets: [
                            {
                                label: "'.__( 'Management incomes', 'rentiq' ).'",
                                backgroundColor: "#80f3e0",
                                borderColor: "#80f3e0",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($incomes_management_array)) .'
                            },
                            {
                                label: "'.__( 'Sinking fund incomes', 'rentiq' ).'",
                                backgroundColor: "#f8e858",
                                borderColor: "#f8e858",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($incomes_sinkink_array)) .'
                            }
                        ]
                    };

                    var graphTarget = jQuery("#graphCanvas_incomes");

                    var barGraph = new Chart(graphTarget, {
                        type: "line",
                        data: chartdata
                    });

                }
                </script>';

    echo $table;
    echo $graph_incomes;

}


//// expense statistics ////

function rentiq_expense_widget() {
    
    $table = '<table class="stats_table">';
    $table .= '<thead><td>'.__( 'Month', 'rentiq' ).'</td><td>'.__( 'Management fees', 'rentiq' ).'</td><td>'.__( 'Sinking fund', 'rentiq' ).'</td><td>'.__( 'Other', 'rentiq' ).'</td><td>'.__( 'Total', 'rentiq' ).'</td></thead>';

    $this_month = date('m');
    $month_count = 4;
    $currency = ' $';

    $months_array = array();
    $expenses_management_array = array();
    $expenses_sinkink_array = array();
    $expenses_other_array = array();

    for ( $months = 0; $months <= $month_count; $months++ ) {

        $month = date('m', strtotime("-$months month"));
        $month_name = date('M Y', strtotime("-$months month"));

        $first_day_month = date('Y'.$month.'01');
        $last_day_month = date('Y'.$month.'t');

        $expenses_sinkink = get_posts( array(
            'post_type'   => array( 'expenses' ),
            'numberposts' => -1,
            'post_status' => 'any',  
            'tax_query'   => array(
                array(
                    'taxonomy' => 'expensecats',
                    'field'    => 'slug',
                    'terms'    => array( 'sinkink-fund' ),
                )
            ),    
            'meta_query' => array(
                array(
                    'key'       => 'expense_date',
                    'value'     => array( $first_day_month, $last_day_month ),
                    'compare'   => 'BETWEEN',
                    'type'      => 'DATE',
                )
            ),
        ));
        $expenses_management = get_posts( array(
            'post_type'   => array( 'expenses' ),
            'numberposts' => -1,
            'post_status' => 'any',  
            'tax_query'   => array(
                array(
                    'taxonomy' => 'expensecats',
                    'field'    => 'slug',
                    'terms'    => array( 'management-fees' ),
                )
            ),    
            'meta_query' => array(
                array(
                    'key'       => 'expense_date',
                    'value'     => array( $first_day_month, $last_day_month ),
                    'compare'   => 'BETWEEN',
                    'type'      => 'DATE',
                )
            ),     
        ));
        $expenses_other = get_posts( array(
            'post_type'   => array( 'expenses' ),
            'numberposts' => -1,
            'post_status' => 'any',  
            'tax_query'   => array(
                array(
                    'taxonomy' => 'expensecats',
                    'field'    => 'slug',
                    'terms'    => array( 'other' ),
                )
            ),    
            'meta_query' => array(
                array(
                    'key'       => 'expense_date',
                    'value'     => array( $first_day_month, $last_day_month ),
                    'compare'   => 'BETWEEN',
                    'type'      => 'DATE',
                )
            ),     
        ));

        $expenses_sinkink_total = 0;
        $expenses_management_total = 0;
        $expenses_other_total = 0;
        $expense_bills_total = 0;

        if ( $expenses_sinkink ) {
            foreach ( $expenses_sinkink as $expense ) {
                $expense_price = get_post_meta( $expense->ID, 'expense_price', true );

                if (is_numeric( $expense_price )) { $expenses_sinkink_total += $expense_price; }
                $expense_bills_total++;
            }
        }

        if ( $expenses_management ) {
            foreach ( $expenses_management as $expense ) {
                $expense_price = get_post_meta( $expense->ID, 'expense_price', true );

                if (is_numeric( $expense_price )) { $expenses_management_total += $expense_price; }
                $expense_bills_total++;
            }
        }

        if ( $expenses_other ) {
            foreach ( $expenses_other as $expense ) {
                $expense_price = get_post_meta( $expense->ID, 'expense_price', true );

                if (is_numeric( $expense_price )) { $expenses_other_total += $expense_price; }
                $expense_bills_total++;
            }
        }

        $months_array[] = $month_name;
        $expenses_management_array[] = $expenses_management_total;
        $expenses_sinkink_array[] = $expenses_sinkink_total;
        $expenses_other_array[] = $expenses_other_total;

        $table .= '<tr><td>'. $month_name .'</td><td>'. number_format( $expenses_management_total, 1 ) . $currency . '</td><td>'. number_format( $expenses_sinkink_total, 1 ) . $currency . '</td><td>'. number_format( $expenses_other_total, 1 ) . $currency . '</td><td><strong>'. number_format( floatval( $expenses_management_total + $expenses_sinkink_total + $expenses_other_total ), 1 ) . $currency . '</strong></td></tr>';
    }  
    
    $table .= '</table>';

    //// graph ////

    $graph_expenses = '<div class="chart-container">
                        <canvas id="graphCanvas_expenses"></canvas>
                    </div>';

    $graph_expenses .= '<script>
                        jQuery(document).ready(function () {
                            showGraph_expenses();
                        });

                function showGraph_expenses() {
                    
                    var chartdata = {
                        labels: '. json_encode( array_reverse($months_array)) .',
                        datasets: [
                            {
                                label: "'.__( 'Management expenses', 'rentiq' ).'",
                                backgroundColor: "#80f3e0",
                                borderColor: "#80f3e0",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($expenses_management_array)) .'
                            },
                            {
                                label: "'.__( 'Sinking fund expenses', 'rentiq' ).'",
                                backgroundColor: "#f8e858",
                                borderColor: "#f8e858",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($expenses_sinkink_array)) .'
                            },
                            {
                                label: "'.__( 'Other expenses', 'rentiq' ).'",
                                backgroundColor: "#f07faa",
                                borderColor: "#f07faa",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($expenses_other_array)) .'
                            }
                        ]
                    };

                    var graphTarget = jQuery("#graphCanvas_expenses");

                    var barGraph = new Chart(graphTarget, {
                        type: "line",
                        data: chartdata
                    });

                }
                </script>';

    echo $table;
    echo $graph_expenses;

}


//// monthly incomes ////

function rentiq_income_monthly_widget() {

    $table = '<table class="stats_table">';
    $table .= '<thead><td>'.__( 'Month', 'rentiq' ).'</td><td>'.__( 'Rental fee', 'rentiq' ).'</td><td>'.__( 'TV', 'rentiq' ).'</td><td>'.__( 'Internet', 'rentiq' ).'</td><td>'.__( 'Water', 'rentiq' ).'</td><td>'.__( 'Electricity', 'rentiq' ).'</td></thead>';
    
    $this_month = date('m');
    $month_count = 4;
    $currency = ' $';

    $months_array = array();
    $rental_fee_array = array();
    $service_tv_array = array();
    $service_internet_array = array();
    $service_water_array = array();
    $service_electricity_array = array();

    for ( $months = 0; $months <= $month_count; $months++ ) {

        $month = date('m', strtotime("-$months month"));
        $month_name = date('M Y', strtotime("-$months month"));

        $first_day_month = date('Y'.$month.'01');
        $last_day_month = date('Y'.$month.'t');

        $all_monthly_invoices = get_posts( array(
            'post_type'   => array( 'invoice_rental', 'invoice_deposit', 'invoice_deporeturn' ),
            'numberposts' => -1,
            'post_status' => 'any',  
            'date_query' => array(
                array(
                    'after'     => $first_day_month,
                    'before'    => $last_day_month,
                    'inclusive' => true,
                ),
            ),
        ));

        $rental_fee_total = 0;
        $service_tv_total = 0;
        $service_internet_total = 0;
        $service_water_total = 0;
        $service_electricity_total = 0;

        foreach ( $all_monthly_invoices as $invoice_made ) {

            $rental_fee_total += floatval( get_post_meta( $invoice_made->ID, 'invoice_rent', true ) );
            $service_tv_total += floatval( get_post_meta( $invoice_made->ID, 'invoice_tv', true ) );
            $service_internet_total += floatval( get_post_meta( $invoice_made->ID, 'invoice_internet', true ) );
            $service_water_total += floatval( get_post_meta( $invoice_made->ID, 'invoice_water_total', true ) );
            $service_electricity_total += floatval( get_post_meta( $invoice_made->ID, 'invoice_electricity_total', true ) );
            
        }

        $months_array[] = $month_name;
        $rental_fee_array[] = $rental_fee_total;
        $service_tv_array[] = $service_tv_total;
        $service_internet_array[] = $service_internet_total;
        $service_water_array[] = $service_water_total;
        $service_electricity_array[] = $service_electricity_total;

        $table .= '<tr><td>'. $month_name .'</td><td>'. number_format( $rental_fee_total, 1 ) . $currency . '</td><td>'. number_format( $service_tv_total, 1 ) . $currency . '</td><td>'. number_format( $service_internet_total, 1 ) . $currency . '</td><td>'. number_format( $service_water_total, 1 ) . $currency . '</td><td>'. number_format( $service_electricity_total, 1 ) . $currency . '</td></tr>';

    }

    $table .= '</table>';

    //// graph ////

    $graph_rental = '<div class="chart-container">
                        <canvas id="graphCanvas_rental"></canvas>
                    </div>';

    $graph_rental .= '<script>
                jQuery(document).ready(function () {
                    showGraph_rental();
                });

                function showGraph_rental() {
                    
                    var chartdata = {
                        labels: '. json_encode( array_reverse($months_array)) .',
                        datasets: [
                            {
                                label: "'.__( 'Rental fee', 'rentiq' ).'",
                                backgroundColor: "#ff6485",
                                borderColor: "#ff6485",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($rental_fee_array)) .'
                            }
                        ]
                    };

                    var graphTarget = jQuery("#graphCanvas_rental");

                    var barGraph = new Chart(graphTarget, {
                        type: "line",
                        data: chartdata
                    });

                }
                </script>';
    
    $graph_services = '<div class="chart-container">
                            <canvas id="graphCanvas_services"></canvas>
                       </div>';

    $graph_services .= '<script>
                jQuery(document).ready(function () {
                    showGraph_services();
                });

                function showGraph_services() {

                    var chartdata = {
                        labels: '. json_encode( array_reverse($months_array)) .',
                        datasets: [
                            {
                                label: "'.__( 'TV', 'rentiq' ).'",
                                backgroundColor: "#f8e858",
                                borderColor: "#f8e858",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($service_tv_array)) .'
                            },
                            {
                                label: "'.__( 'Internet', 'rentiq' ).'",
                                backgroundColor: "#80f3e0",
                                borderColor: "#80f3e0",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($service_internet_array)) .'
                            },
                            {
                                label: "'.__( 'Water', 'rentiq' ).'",
                                backgroundColor: "#8ad3f5",
                                borderColor: "#8ad3f5",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($service_water_array)) .'
                            },
                            {
                                label: "'.__( 'Electricity', 'rentiq' ).'",
                                backgroundColor: "#f7ba81",
                                borderColor: "#f7ba81",
                                hoverBackgroundColor: "#CCCCCC",
                                hoverBorderColor: "#666666",
                                data: '. json_encode( array_reverse($service_electricity_array)) .'
                            }
                        ]
                    };

                    var graphTarget = jQuery("#graphCanvas_services");

                    var barGraph = new Chart(graphTarget, {
                        type: "line",
                        data: chartdata
                    });

                }
                </script>';

    echo $table;
    echo $graph_rental;
    echo $graph_services;

}




//// availability statistics ////

function rentiq_availability_widget() {

    $currency = ' $';
    $building = 'A';

    $table = '<table class="stats_table">';
    $table .= '<thead><td>'.__( 'Available', 'rentiq' ).'</td><td>'.__( 'Signed', 'rentiq' ).'</td><td>'.__( 'Drafted', 'rentiq' ).'</td><td>'.__( 'Owner living', 'rentiq' ).'</td><td>'.__( 'LZ', 'rentiq' ).'</td></thead>';

    $apartment_args = array(
        'hide_empty'    => false,
        'meta_query'    => array(
            array(
                'key'       => 'apartment_building',
                'value'     => $building,
                'compare'   => '='
            ),
        ),
        'taxonomy'      => 'apartment',
    );
    $apartments = get_terms( $apartment_args );

    $available = 0;
    $multiple = 0;
    $signed = 0;
    $drafted = 0;
    $owner_living = 0;
    $not_sold = 0;
    $occupied = 0;

    if ( $apartments ) {
        foreach ( $apartments as $apartment ) {

            $apartment_reservations = Rentiq_class::get_reservation_by_apartment( $apartment->term_id );

            $apartment_owner = get_term_meta( $apartment->term_id, 'apartment_owner', true );
            if ( $apartment_owner == 560 ) {
                $not_sold++;
            }

            if ( count($apartment_reservations) > 0 ) {
                $occupied++;
            }

            if ( count($apartment_reservations) > 1 ) {

                $multiple++;

            } else if ( count($apartment_reservations) == 1 ) {

                $reservation_id = $apartment_reservations[0]->ID;
                $room_status = $apartment_reservations[0]->post_status;

                switch( $room_status ) {
                    case 'draft': $drafted++; break;
                    case 'signed': 
                        
                        $apartment_tenant = get_post_meta( $reservation_id, 'reservation_tenant_type', true );
                        if ($apartment_tenant == 'owner') {
                            $owner_living++;
                        } else {
                            $signed++;
                        }
                        
                    break;
                }
            
            } else {
                $available++;
            }
        }
    }
    
    $table .= '<tr><td>'. intval( $available ) .'</td><td>'. intval( $signed ) .'</td><td>'. intval( $drafted ) . '</td><td>'. intval( $owner_living ) . '</td><td>'. intval( $not_sold ) . '</td></tr>';


    $apartments_total = count($apartments);
    $available_percentage = ( 100 / $apartments_total ) * $available;
    $occupied_percentage = ( 100 / $apartments_total ) * $occupied;

    $table .= '<tr><td><strong>'.__( 'Available', 'rentiq' ).'<strong></td><td><strong>'. number_format( $available_percentage, 1 ) . '%</strong></td></tr>';
    $table .= '<tr><td><strong>'.__( 'Occupied', 'rentiq' ).'<strong></td><td><strong>'. number_format( $occupied_percentage, 1 ) . '%</strong></td></tr>';

    $table .= '</table>';


    //// graph ////

    $graph_availability = '<div class="chart-container">
                              <canvas id="graphCanvas_availability"></canvas>
                          </div>';

    $graph_availability .= '<script>
                        jQuery(document).ready(function () {
                            showGraph_availability();
                        });

                function showGraph_availability() {
                    
                    var chartdata = {
                        labels: [
                            "'.__( 'Available', 'rentiq' ).'",
                            "'.__( 'Signed', 'rentiq' ).'",
                            "'.__( 'Drafted', 'rentiq' ).'",
                            "'.__( 'Owner living', 'rentiq' ).'",
                        ],
                        datasets: [{
                            label: "'.__( 'Availability', 'rentiq' ).'",
                            data: ['. $available .', '. $signed .', '. $drafted .', '. $owner_living .'],
                            backgroundColor: [ "#c8ffea", "#d9e9ff", "#fff9c1", "#ffbc74" ],
                            hoverOffset: 4,
                            weight: 0.5
                        }]
                    };

                    var graphTarget = jQuery("#graphCanvas_availability");

                    var barGraph = new Chart(graphTarget, {
                        type: "doughnut",
                        data: chartdata,
                    });

                }
                </script>';

    echo $table;
    echo $graph_availability;

}